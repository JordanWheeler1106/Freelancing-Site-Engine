<?php

class ET_GoogleAuth extends ET_SocialAuth {
	private $state;
	private $gplus_secret_key;
	protected $gplus_client_id;
	protected $gplus_base_url;
	protected $gplus_exchange_url;
	protected $gplus_token_info_url;

	public function __construct() {
		parent::__construct( 'google', 'et_google_id', array(
			'title' => __( 'Sign Up With Google+', ET_DOMAIN ),
		) );
		$this->add_ajax( 'ae_gplus_auth', 'ae_gplus_redirect' );
		$this->gplus_client_id      = ae_get_option( 'gplus_client_id' );
		$this->gplus_secret_key     = ae_get_option( 'gplus_secret_id' );
		$this->gplus_base_url       = 'https://accounts.google.com/o/oauth2/auth';
		$this->gplus_exchange_url   = 'https://www.googleapis.com/oauth2/v3/token';
		$this->gplus_token_info_url = 'https://www.googleapis.com/oauth2/v1/userinfo';
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'gplus_auth_callback' ) {
			if ( ! empty( $this->gplus_client_id ) && ! empty( $this->gplus_secret_key ) && ! is_user_logged_in() ) {
				$this->auth_google();
			} else {
				_e( 'Please enter your Google client id and secret key in setting page!', ET_DOMAIN );
				exit();
			}
		}
	}

	// implement abstract method
	protected function send_created_mail( $user_id ) {
		do_action( 'et_after_register', $user_id );
	}

	public function ae_gplus_redirect() {
		try {
			// turn on session
			if ( ! isset( $_SESSION ) ) {
				ob_start();
				@session_start();
			}
			$this->state  = md5( uniqid() );
			$redirect_uri = home_url( '?action=gplus_auth_callback' );
			$link         = $this->gplus_base_url . '?';
			$link         .= 'scope=https://www.googleapis.com/auth/userinfo.email&';
			$link         .= 'state=' . $this->state . '&';
			$link         .= 'redirect_uri=' . $redirect_uri . '&';
			$link         .= 'client_id=' . $this->gplus_client_id . '&';
			$link         .= 'response_type=code&';
			$resp         = array(
				'success'  => true,
				'msg'      => __( 'success', ET_DOMAIN ),
				'redirect' => $link,
			);

		} catch ( Exception $e ) {

			$resp = array(
				'success' => false,
				'msg'     => $e->getMessage()
			);

		}
		wp_send_json( $resp );
	}

	public function auth_google() {
		if ( ( isset( $_REQUEST['code'] ) && ! empty( $_REQUEST['code'] ) ) && ( isset( $_REQUEST['state'] ) || $_REQUEST['state'] == $this->state ) ) {
			try {
				// turn on session
				if ( ! isset( $_SESSION ) ) {
					ob_start();
					@session_start();
				}
				/**
				 * Exchange authorization code for tokens
				 */
				$redirect_uri = home_url( '?action=gplus_auth_callback' );
				$args         = array(
					'method' => 'POST',
					'body'   => array(
						'grant_type'    => 'authorization_code',
						'code'          => $_REQUEST['code'],
						'redirect_uri'  => $redirect_uri,
						'client_id'     => $this->gplus_client_id,
						'client_secret' => $this->gplus_secret_key
					)
				);
				$remote_post  = wp_remote_post( $this->gplus_exchange_url, $args );
				if ( isset( $remote_post ['body'] ) ) {
					$data = json_decode( $remote_post ['body'] );
					if ( isset( $data->refresh_token ) ) {
						$secure = ( 'https' === parse_url( site_url(), PHP_URL_SCHEME ) && 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
						setcookie( 'refresh_token', $data->refresh_token, time() + 3600 * 24 * 7, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
					}
					if ( isset( $data->error ) && $data->error == 'invalid_grant' ) {
						$args        = array(
							'method' => 'POST',
							'body'   => array(
								'grant_type'    => 'refresh_token',
								'code'          => $_REQUEST['code'],
								'redirect_uri'  => $redirect_uri,
								'client_id'     => $this->gplus_client_id,
								'client_secret' => $this->gplus_secret_key,
								'refresh_token' => $_COOKIE['refresh_token']
							)
						);
						$remote_post = wp_remote_post( $this->gplus_exchange_url, $args );
						$data        = json_decode( $remote_post ['body'] );
					}
				} else {
					_e( 'Error to connect to Google Server!', ET_DOMAIN );
					exit();
				}
				/**
				 * Get user information
				 */
				if ( isset( $data->access_token ) ) {
					$userinfor = wp_remote_get( $this->gplus_token_info_url . '?access_token=' . $data->access_token );
					$userinfor = json_decode( $userinfor['body'] );
				} else {
					_e( 'Error to connect to Google', ET_DOMAIN );
					exit();
				}
				if ( ! isset( $userinfor->id ) || empty( $userinfor->id ) ) {
					_e( 'Error to connect to Google Server!', ET_DOMAIN );
					exit();
				}
				// if user is already authenticated before
				if ( $this->get_user( $userinfor->id ) ) {
					$user     = $this->get_user( $userinfor->id );
					$result   = $this->logged_user_in( $userinfor->id );
					$ae_user  = AE_Users::get_instance();
					$userdata = $ae_user->convert( $user );
					$nonce    = array(
						'reply_thread' => wp_create_nonce( 'insert_reply' ),
						'upload_img'   => wp_create_nonce( 'et_upload_images' ),
					);
				} else {
					// avatar
					$ava_response = isset( $userinfor->picture ) ? $userinfor->picture : '';
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
					//$userinfor->name = str_replace( ' ', '', sanitize_user( $userinfor->name ) );
					$emails          = explode( "@", $userinfor->email );
					$userinfor->name = $username = $emails[0];
					$username        = $userinfor->name;
					$params          = array(
						'user_login' => $username,
						'user_email' => isset( $userinfor->email ) ? $userinfor->email : false,
						'et_avatar'  => $avatars
					);
					//remove avatar if cant fetch avatar
					foreach ( $params as $key => $param ) {
						if ( $param == false ) {
							unset( $params[ $key ] );
						}
					}
					$_SESSION['et_auth']      = serialize( $params );
					$_SESSION['et_social_id'] = $userinfor->id;
					$_SESSION['et_auth_type'] = 'google';

					et_write_session( 'et_auth', serialize( $params ) );
					et_write_session( 'et_social_id', $userinfor->id );
					et_write_session( 'et_auth_type', 'google' );
				}
				header( 'Location: ' . $this->auth_url );
				exit();
			} catch ( Exception $e ) {
				_e( 'Error to connect to Google Server', ET_DOMAIN );
				exit();
			}
		}
	}
}