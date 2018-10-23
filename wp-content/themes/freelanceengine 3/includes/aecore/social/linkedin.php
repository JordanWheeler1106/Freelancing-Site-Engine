<?php

/**
 *   Linkedin authication
 * @author Quang Ð?t
 */
class ET_LinkedInAuth extends ET_SocialAuth {
	private $state;
	private $linkedin_secret_key;
	protected $linkedin_api_key;
	protected $linkedin_base_url;
	protected $linkedin_token_url;
	protected $linkedin_people_url;

	public function __construct() {
		parent::__construct( 'linkedin', 'et_linkedin_id', array(
			'title' => __( 'Sign Up With Linkedin', ET_DOMAIN ),
		) );
		$this->state = md5( uniqid() );
		$this->add_ajax( 'ae_linked_auth', 'lkin_redirect' );
		$this->linkedin_api_key    = ae_get_option( 'linkedin_api_key' );
		$this->linkedin_secret_key = ae_get_option( 'linkedin_secret_key' );
		$this->linkedin_base_url   = 'https://www.linkedin.com/uas/oauth2/authorization';
		$this->linkedin_token_url  = 'https://www.linkedin.com/uas/oauth2/accessToken';
		$this->linkedin_people_url = 'https://api.linkedin.com/v1/people/~:(id,location,picture-url,specialties,public-profile-url,email-address,formatted-name)?format=json';
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'linked_auth_callback' ) {
			if ( ! empty( $this->linkedin_api_key ) && ! empty( $this->linkedin_secret_key ) && ! is_user_logged_in() ) {
				$this->linked_auth();
			} else {
				_e( 'Please enter your Linkedin App id and secret key!', ET_DOMAIN );
				exit();
			}
		}
	}

	// implement abstract method
	protected function send_created_mail( $user_id ) {
		do_action( 'et_after_register', $user_id );
	}

	/**
	 * When user click login button Linkedin, it will execution function bellow
	 * @return $link
	 */
	public function lkin_redirect() {
		try {
			// turn on session
			if ( ! isset( $_SESSION ) ) {
				ob_start();
				@session_start();
			}
			/**
			 * Step1: Request an Authorization Code
			 */
			$redirect_uri = home_url( '?action=linked_auth_callback' );
			$link         = $this->linkedin_base_url . '?';
			$link         .= 'response_type=code&';
			$link         .= 'client_id=' . $this->linkedin_api_key . '&';
			$link         .= 'redirect_uri=' . $redirect_uri . '&';
			$link         .= 'state=' . $this->state . '&';
			$link         .= 'scope=r_basicprofile r_emailaddress';
			// wp_set_auth_cookie($link);
			$resp = array(
				'success'  => true,
				'msg'      => 'Success',
				'redirect' => $link
			);
		} catch ( Exception $e ) {
			$resp = array(
				'success' => false,
				'msg'     => $e->getMessage()
			);

		}
		wp_send_json( $resp );
	}

	/**
	 * function handle after linkedin callback
	 */
	public function linked_auth() {
		if ( ( isset( $_REQUEST['code'] ) && ! empty( $_REQUEST['code'] ) ) && ( isset( $_REQUEST['state'] ) || $_REQUEST['state'] == $this->state ) ) {
			try {
				/**
				 * Step2: Exchange Authorization Code for a Request Token
				 */
				$request      = $_REQUEST;
				$redirect_uri = home_url( '?action=linked_auth_callback' );
				$args         = array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'grant_type'    => 'authorization_code',
						'code'          => $request['code'],
						'redirect_uri'  => $redirect_uri,
						'client_id'     => $this->linkedin_api_key,
						'client_secret' => $this->linkedin_secret_key
					),
					'cookies'     => array()
				);
				$remote_post  = wp_remote_post( $this->linkedin_token_url, $args );
				if ( isset( $remote_post ['body'] ) && ! empty( $remote_post ['body'] ) ) {
					$data = json_decode( $remote_post ['body'] );
				} else {
					_e( 'Error to connect to Linkedin server!', ET_DOMAIN );
					exit();
				}
				if ( ! isset( $data->access_token ) || empty( $data->access_token ) ) {
					_e( 'Can not get the access token from Linkedin server!', ET_DOMAIN );
					exit();
				}
				/**
				 * Step3: Make authenticated requests and get user's informations
				 */
				$args1      = array(
					'timeout'     => 120,
					'httpversion' => '1.1',
					'headers'     => array(
						'Authorization' => 'Bearer ' . $data->access_token
					)
				);
				$remote_get = wp_remote_get( $this->linkedin_people_url, $args1 );
				if ( isset( $remote_get['body'] ) && ! empty( $remote_get['body'] ) ) {
					$data_user = json_decode( $remote_get['body'] );
				} else {
					_e( 'Error to connect to Linkedin server2!', ET_DOMAIN );
					exit();
				}
				if ( ! isset( $data_user->id ) || empty( $data_user->id ) ) {
					_e( 'Can not get user information from Linkedin server!', ET_DOMAIN );
					exit();
				}
				// if user is already authenticated before
				if ( $this->get_user( $data_user->id ) ) {
					$user     = $this->get_user( $data_user->id );
					$result   = $this->logged_user_in( $data_user->id );
					$ae_user  = AE_Users::get_instance();
					$userdata = $ae_user->convert( $user );
					$nonce    = array(
						'reply_thread' => wp_create_nonce( 'insert_reply' ),
						'upload_img'   => wp_create_nonce( 'et_upload_images' ),
					);
				} else {
					// avatar
					$ava_response = isset( $data_user->pictureUrl ) ? $data_user->pictureUrl : '';
					$sizes        = get_intermediate_image_sizes();
					$avatars      = array();
					if ( $ava_response ) {
						foreach ( $sizes as $size ) {
							$avatars[ $size ] = array(
								$ava_response
							);
						}
					} else {
						$avatars = false;
					}
					$data_user->formattedName = str_replace( ' ', '', sanitize_user( $data_user->formattedName ) );
					$username                 = $data_user->formattedName;
					$params                   = array(
						'user_login' => $username,
						'user_email' => isset( $data_user->emailAddress ) ? $data_user->emailAddress : false,
						'et_avatar'  => $avatars
					);
					//remove avatar if cant fetch avatar
					foreach ( $params as $key => $param ) {
						if ( $param == false ) {
							unset( $params[ $key ] );
						}
					}
					// turn on session
					if ( ! isset( $_SESSION ) ) {
						ob_start();
						@session_start();
					}
					/**
					 * set value into session for save later
					 *
					 */
					$_SESSION['et_auth']      = serialize( $params );
					$_SESSION['et_social_id'] = $data_user->id;
					$_SESSION['et_auth_type'] = 'linkedin';

					et_write_session( 'et_auth', serialize( $params ) );
					et_write_session( 'et_social_id', $data_user->id );
					et_write_session( 'et_auth_type', 'linkedin' );

				}
				header( 'Location: ' . $this->auth_url );
				exit();
			} catch ( Exception $e ) {
				_e( 'Error to connect to Linkedin server', ET_DOMAIN );
				exit();
			}
		}
	}
}