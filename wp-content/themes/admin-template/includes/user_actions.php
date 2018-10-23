<?php

/**
 *
 * Class Handle User Actions
 *
 * @param int $result : user id
 *
 * @author Dakachi
 * @version 1.0
 * @copyright enginethemes.com team
 * @package white panda
 *
 *
 */
class AE_User_Front_Actions extends AE_Base {
	function __construct( AE_Users $user ) {
		$this->user = $user;
		$this->mail = AE_Mailing::get_instance();
		// $this->add_action('init', 'confirm');
		$this->add_action( 'ae_insert_user', 'after_register' );
		$this->add_action( 'ae_social_insert_user', 'after_register_social' );
		$this->add_action( 'ae_user_forgot', 'user_forgot', 10, 2 );
		$this->add_action( 'ae_user_inbox', 'user_inbox', 10, 2 );
		$this->add_action( 'ae_upload_image', 'change_avatar', 10, 2 );

		$this->add_action( 'save_post', 'update_user_profile_id' );

		//$this->add_ajax('ae_send_contact', 'ae_send_contact');
		$this->add_ajax( 'ae-send-invite', 'ae_send_invite' );

		$this->add_ajax( 'fre_crop_avatar', 'crop_avatar' );
	}

	/*
	 * confirm user
	*/
	function confirm() {

	}

	/*
	 * send private message between 2 users
	*/
	function user_inbox( $author, $message ) {
		$this->mail->inbox_mail( $author, $message );
	}

	/*
	 * send email forgot to user
	*/
	function user_forgot( $result, $key ) {

		/* === Send Email Forgot === */
		$this->mail->forgot_mail( $result, $key );
	}

	/*
	 * check if confirm email is active
	 * update user status
	*/
	function after_register( $result ) {
		$user = new WP_User( $result );

		// add key confirm for user
		if ( ae_get_option( 'user_confirm' ) ) {
			update_user_meta( $result, 'register_status', 'unconfirm' );
			update_user_meta( $result, 'key_confirm', md5( $user->user_email ) );
		}

		/* === Send Email Register === */
		$this->mail->register_mail( $result );
	}

	/*
	 * check if confirm email is active
	 * update user status
	*/
	function after_register_social( $params ) {
		$user = new WP_User( $params['id'] );

		// add key confirm for user
		if ( ae_get_option( 'user_confirm' ) ) {
			update_user_meta( $params['id'], 'register_status', 'unconfirm' );
			update_user_meta( $params['id'], 'key_confirm', md5( $user->user_email ) );
		}

		/* === Send Email Register === */
		$this->mail->social_register_mail( $params );
	}


	public function crop_avatar() {
		global $current_user;
		$request = $_REQUEST;

		// Check valid image
		if(!isset($request['attach_id']) || empty($request['attach_id'])) {
			wp_send_json(array(
				'success' => false,
				'msg' => __('Invalid image!', ET_DOMAIN)
			));
		}

		// Check valid user
		if($request['user_id'] != $current_user->ID) {
			wp_send_json(array(
				'success' => false,
				'msg' => __('Invalid user!', ET_DOMAIN)
			));
		}

		$des_file = wp_crop_image(
			$request['attach_id'],
			$request['crop_x'],
			$request['crop_y'],
			$request['crop_width'],
			$request['crop_height'],
			$request['crop_width'],
			$request['crop_height']
		);

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype(basename( $des_file ), null);

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename($des_file),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($des_file)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment($attachment, $des_file);

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata($attach_id, $des_file);
		wp_update_attachment_metadata($attach_id, $attach_data);

		$attach_data = et_get_attachment_data($attach_id);

		if (!isset($request['user_id'])) return;

		$ae_users = AE_Users::get_instance();

		//update user avatar
		$user = $ae_users->update(array(
			'ID' => $request['user_id'],
			'et_avatar' => $attach_data['attach_id'],
			'et_avatar_url' => $attach_data['thumbnail'][0]
		));

		wp_send_json(array(
			'success' => true,
			'msg' => __('Your profile picture has been uploaded successfully.', ET_DOMAIN) ,
			'data' => $attach_data
		));
	}

	/**
	 * update user avatar
	 */
	public function change_avatar( $attach_data, $data ) {
		//if no author ID return false;
		if ( ! isset( $data['author'] ) ) {
			return;
		}
		//update user avatar only
		if ( $data['method'] == "change_avatar" ) {
			$ae_users = AE_Users::get_instance();
			//update user avatar
			$user = $ae_users->update( array(
				'ID'            => $data['author'],
				'et_avatar'     => $attach_data['attach_id'],
				'et_avatar_url' => $attach_data['thumbnail'][0]
			) );
		}
	}

	function update_user_profile_id( $post_id ) {
		$post = get_post( $post_id );
		if ( $post->post_type == PROFILE ) {
			update_user_meta( $post->post_author, 'user_profile_id', $post->ID );
		}

	}

	function ae_send_invite() {
		global $user_ID;
		try {
			if ( isset( $_POST['data'] ) && $_POST['data'] ) {
				$this->mail   = Fre_Mailing::get_instance();
				$mail_success = $this->mail->invite_mail( $_POST['user_id'], $_POST['data']['project_invites'] );
				if ( $mail_success || true ) {

					$invited        = $_POST['user_id'];
					$send_invite    = $user_ID;
					$invite_project = $_POST['data']['project_invites'];
					/**
					 * do action when user have a new invite
					 *
					 * @param int $invited invited user id
					 * @param int $send_invite user send invite
					 * @param Array $invite_project list of projects
					 *
					 * @since 1.3
					 * @author Dakachi
					 */
					foreach ( $invite_project as $key => $value ) {
						do_action( 'fre_new_invite', $invited, $send_invite, $value );
					}


					$resp = array(
						'success' => true,
						'msg'     => __( 'Your invite has been sent!', ET_DOMAIN )
					);
				} else {
					$resp = array(
						'success' => false,
						'msg'     => __( 'Currently, you do not have any project available to invite this user.', ET_DOMAIN )
					);
				}
			} else {
				$resp = array(
					'success' => false,
					'msg'     => __( "Please choose at least one project!", ET_DOMAIN )
				);
			}
		} catch ( Exception $e ) {
			$resp = array(
				'success' => false,
				'msg'     => $e->getMessage()
			);
		}
		wp_send_json( $resp );
	}
}