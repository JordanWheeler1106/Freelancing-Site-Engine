<?php
if ( ! function_exists( 'fre_check_register' ) ) {
	/**
	 * check register
	 * @return bool|mixed|void $re
	 */
	function fre_check_register() {
		$re = false;
		if ( is_wp_error( MULTISITE ) && MULTISITE ) {
			$re = users_can_register_signup_filter();

		} else {
			$re = get_option( 'users_can_register', 0 );
		}

		return $re;
	}
}
if ( ! function_exists( 'fre_project_demonstration' ) ) {

	/**
	 * render project desmonstration settings in hompage
	 *
	 * @param bool $home if true render home page desmonstration/ false render list project demonstration
	 *
	 * @since v1.0
	 * @author Dakachi
	 */
	function fre_project_demonstration( $home = false ) {
		$project_demonstration = ae_get_option( 'project_demonstration' );
		if ( $home ) {
			echo $project_demonstration['home_page'];

			return;
		}
		echo $project_demonstration['list_project'];
	}
}

if ( ! function_exists( 'fre_profile_demonstration' ) ) {

	/**
	 * render profile desmonstration settings in header
	 *
	 * @param bool $home if true render home page desmonstration/ false render list project demonstration
	 *
	 * @since v1.0
	 * @author Dakachi
	 */
	function fre_profile_demonstration( $home = false ) {
		$project_demonstration = ae_get_option( 'profile_demonstration' );
		if ( $home ) {
			echo $project_demonstration['home_page'];

			return;
		}
		echo $project_demonstration['list_profile'];
	}
}

if ( ! function_exists( 'fre_logo' ) ) {

	/**
	 * render site logo image get from option
	 * @author tam
	 * @return void
	 */
	function fre_logo( $option_name = '' ) {
		if ( $option_name == '' ) {
			if ( is_front_page() ) {
				$option_name = 'site_logo_white';
			} else {
				$option_name = 'site_logo_black';
			}
		}
		switch ( $option_name ) {
			case 'site_logo':
				$img = get_template_directory_uri() . "/img/logo-fre.png";
				break;
			case 'site_logo_black':
				$img = get_template_directory_uri() . "/img/logo-fre-black.png";
				break;

			case 'site_logo_white':
				$img = get_template_directory_uri() . "/img/logo-fre-white.png";
				break;

			case 'site_logo_white_footer':
				$img = get_template_directory_uri() . "/img/logo-fre-white-footer.png";
				break;

			default:
				$img = get_template_directory_uri() . "/img/logo-fre-black.png";
				break;
		}
		$options = AE_Options::get_instance();

		// save this setting to theme options
		$site_logo = $options->$option_name;
		if ( ! empty( $site_logo ) ) {
			$img = $site_logo['large'][0];
		}
		echo '<img alt="' . $options->blogname . '" src="' . $img . '" />';
	}
}

if ( ! function_exists( 'fre_logo_mobile' ) ) {
	/**
	 * render site mobile logo image get from option
	 * @author Tuandq
	 * @return void
	 */
	function fre_logo_mobile() {
		$img     = get_template_directory_uri() . "/img/logo-fre-white.png";
		$options = AE_Options::get_instance();
		// save this setting to theme options
		$mobile_site_logo = $options->site_logo;
		if ( ! empty( $mobile_site_logo ) ) {
			$img = $mobile_site_logo['large'][0];
		} else {
			$img = get_template_directory_uri() . "/img/logo-fre-white.png";
		}
		echo '<img alt="' . $options->blogname . '" src="' . $img . '" />';
	}
}

/**
 * check site option shared role or not
 * @since 1.2
 * @author Dakachi
 */
function fre_share_role() {
	$options = AE_Options::get_instance();

	// save this setting to theme options
	return $options->fre_share_role;
}

/**
 * allow user to upload a video file
 * @author tam
 *
 */
add_filter( 'upload_mimes', 'fre_add_mime_types' );
add_filter( 'et_upload_file_upload_mimes', 'fre_add_mime_types' );
function fre_add_mime_types( $mimes ) {
	/**
	 * admin can add more file extension
	 */
	if ( current_user_can( 'manage_options' ) ) {
		return array_merge( $mimes, array(
			'ac3'                  => 'audio/ac3',
			'mpa'                  => 'audio/MPA',
			'flv'                  => 'video/x-flv',
			'svg'                  => 'image/svg+xml',
			'mp4'                  => 'video/MP4',
			'doc|docx'             => 'application/msword',
			'pdf'                  => 'application/pdf',
			'zip'                  => 'multipart/x-zip',
			'xla|xls|xlt|xlw|xlsx' => 'application/vnd.ms-excel',
		) );
	}
	// if user is normal user
	$mimes = array_merge( $mimes, array(
		'xla|xls|xlt|xlw|xlsx' => 'application/vnd.ms-excel',
		'doc|docx'             => 'application/msword',
		'pdf'                  => 'application/pdf',
		'zip'                  => 'multipart/x-zip'
	) );

	return $mimes;
}

/**
 * get content current currency sign (icon)
 *
 * @param $echo bool
 *
 * @author Dakachi
 */
function fre_currency_sign( $echo = true ) {

	// get option instance to retrieve option value
	$options = AE_Options::get_instance();
	if ( $echo ) // echo option
	{
		echo $options->currency['icon'];
	} else // return an option value
	{
		return $options->currency['icon'];
	}
}

function fre_price_format( $amount, $style = '<sup>' ) {

	$currency = ae_get_option( 'currency', array(
		'align' => 'left',
		'code'  => 'USD',
		'icon'  => '$'
	) );

	$align = $currency['align'];

	// dafault = 0 == right;

	$currency     = $currency['icon'];
	$price_format = get_theme_mod( 'decimal_point', 1 );
	$format       = '%1$s';

	switch ( $style ) {
		case 'sup':
			$format = '<sup>%s</sup>';
			break;

		case 'sub':
			$format = '<sub>%s</sub>';
			break;

		default:
			$format = '%s';
			break;
	}

	$number_format = ae_get_option( 'number_format' );
	$decimal       = ( isset( $number_format['et_decimal'] ) ) ? $number_format['et_decimal'] : get_theme_mod( 'et_decimal', 2 );
	$decimal_point = ( isset( $number_format['dec_point'] ) && $number_format['dec_point'] ) ? $number_format['dec_point'] : get_theme_mod( 'et_decimal_point', '.' );
	$thousand_sep  = ( isset( $number_format['thousand_sep'] ) && $number_format['thousand_sep'] ) ? $number_format['thousand_sep'] : get_theme_mod( 'et_thousand_sep', ',' );

	if ( $align != "0" ) {
		$format = $format . '%s';

		return sprintf( $format, $currency, number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep ) );
	} else {
		$format = '%s' . $format;

		return sprintf( $format, number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep ), $currency );
	}
}

function price_about_format( $price ) {
	$currency = ae_get_option( 'currency', array(
		'align' => 'left',
		'code'  => 'USD',
		'icon'  => '$'
	) );

	$align    = $currency['align'];
	$currency = $currency['icon'];
	$format   = '%s';

	$price_about = $price;
	if ( $price > 100 && $price <= 1000 ) {
		$price_about = '100+';
	}
	if ( $price > 1000 && $price <= 10000 ) {
		$price_about = '1k+';
	}
	if ( $price > 10000 ) {
		$price_about = '10k+';
	}

	if ( $align != "0" ) {
		$format = $format . '%s';

		return sprintf( $format, $currency, $price_about );
	} else {
		$format = '%s' . $format;

		return sprintf( $format, $price_about, $currency );
	}

}

function timeFormatRemoveDate( $date_fr_option ) {
	if ( preg_match( '/j/', $date_fr_option ) ) {
		$date_fr_option = str_replace( ' j,', '', $date_fr_option );
		$date_fr_option = str_replace( 'j,', '', $date_fr_option );
		$date_fr_option = str_replace( 'j', '', $date_fr_option );
	}

	if ( preg_match( '/d/', $date_fr_option ) ) {
		$date_fr_option = str_replace( 'd/', '', $date_fr_option );
		$date_fr_option = str_replace( '/d', '', $date_fr_option );
		$date_fr_option = str_replace( 'd-', '', $date_fr_option );
		$date_fr_option = str_replace( '-d', '', $date_fr_option );
		$date_fr_option = str_replace( 'd', '', $date_fr_option );
	}

	return $date_fr_option;
}

function fre_number_format( $amount, $echo = true ) {
	$number_format = ae_get_option( 'number_format' );
	$decimal       = ( isset( $number_format['et_decimal'] ) ) ? $number_format['et_decimal'] : get_theme_mod( 'et_decimal', 2 );
	$decimal_point = ( isset( $number_format['dec_point'] ) && $number_format['dec_point'] ) ? $number_format['dec_point'] : get_theme_mod( 'et_decimal_point', '.' );
	$thousand_sep  = ( isset( $number_format['thousand_sep'] ) && $number_format['thousand_sep'] ) ? $number_format['thousand_sep'] : get_theme_mod( 'et_thousand_sep', ',' );
	if ( $echo ) {
		return number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep );
	} else {
		return number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep );
	}
}

/**
 *
 * Function add filter orderby post status
 *
 *
 */
function fre_order_by_bid_status( $orderby ) {
	global $wpdb;
	$orderby = " case {$wpdb->posts}.post_status
                         when 'complete' then 0
                         when 'accept' then 1
                         when 'publish' then 2
                         when 'unaccept' then 3
                         end,
            {$wpdb->posts}.post_date DESC";

	return $orderby;
}

/**
 *
 * Function add filter orderby project post status
 *
 *
 */
function fre_order_by_project_status( $orderby ) {
	global $wpdb;
	// NEW VERSION
	$orderby = "{$wpdb->posts}.post_date DESC";
	// OLD VERSION
	/*
	$orderby = " case {$wpdb->posts}.post_status
							when 'disputing' then 0
							when 'reject' then 1
							when 'pending' then 2
							when 'publish' then 3
							when 'close' then 4
							when 'complete' then 5
							when 'draft' then 6
							when 'archive' then 7
							when 'disputed' then 8

						end,
						{$wpdb->posts}.post_date DESC";
	*/

	return $orderby;
}

/**
 * Function add filter orderby project post status
 */
function fre_reset_order_by_project_status( $orderby ) {
	global $wpdb;
	$orderby = "{$wpdb->posts}.post_date DESC";

	return $orderby;
}

function fre_where_current_bid( $where ) {
	global $wpdb;
	$result = $wpdb->get_col( "SELECT * FROM $wpdb->posts 
        WHERE 1=1 
        AND post_type = 'project' 
        AND post_status IN ('publish', 'close', 'archive', 'disputing' )" );
	if ( ! empty( $result ) ) {
		$where .= "AND {$wpdb->posts}.post_parent IN (" . implode( ',', $result ) . ")";
	} else {
		$where .= "AND {$wpdb->posts}.post_parent";
	}

	return $where;
}

/**
 * Function add filter where project post status
 * Work history and review of freelance
 */
function fre_filter_where_bid( $WHERE ) {
	global $wpdb;
	$result = $wpdb->get_col( "SELECT * FROM $wpdb->posts 
            WHERE 1=1 
            AND post_type = 'project' 
            AND post_status IN ('complete', 'disputed')" );
	if ( ! empty( $result ) ) {
		$WHERE .= "AND {$wpdb->posts}.post_parent IN (" . implode( ',', $result ) . ")";
	} else {
		$WHERE .= "AND {$wpdb->posts}.post_parent";
	}

	return $WHERE;
}

add_action( 'wp_ajax_ae_upload_files', 'fre_upload_file' );
function fre_upload_file() {
	$res = array(
		'success' => false,
		'msg'     => __( 'There is an error occurred', ET_DOMAIN ),
		'code'    => 400,
	);

	// check fileID
	if ( ! isset( $_POST['fileID'] ) || empty( $_POST['fileID'] ) ) {
		$res['msg'] = __( 'Missing image ID', ET_DOMAIN );
	} else {
		$fileID     = $_POST["fileID"];
		$imgType    = $_POST['imgType'];
		$project_id = $_POST['project_id'];
		$author_id  = $_POST['author_id'];

		$lock_status = get_post_meta( $project_id, 'lock_file', true );

		if ( $imgType == 'file' && $lock_status == 'lock' ) {
			$res['msg'] = __( 'You cannot upload a new file since partner locked this section. Please refresh the page.', ET_DOMAIN );
		} else {
			// check ajax nonce
			if ( ! de_check_ajax_referer( 'file_et_uploader', false, false ) && ! check_ajax_referer( 'file_et_uploader', false, false ) ) {
				$res['msg'] = __( 'Security error!', ET_DOMAIN );
			} elseif ( isset( $_FILES[ $fileID ] ) ) {

				// handle file upload
				$attach_id = et_process_file_upload( $_FILES[ $fileID ], 0, 0, array(
					'jpg|jpeg|jpe'     => 'image/jpeg',
					'gif'              => 'image/gif',
					'png'              => 'image/png',
					'bmp'              => 'image/bmp',
					'tif|tiff'         => 'image/tiff',
					'pdf'              => 'application/pdf',
					'doc'              => 'application/msword',
					'docx'             => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					'odt'              => 'application/vnd.oasis.opendocument.text',
					'zip'              => 'application/zip',
					'rar'              => 'application/rar',
					'xla|xls|xlt|xlw|' => 'application/vnd.ms-excel',
					'xlsx'             => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
					'gz|gzip'          => 'application/x-gzip',
				) );

				if ( ! is_wp_error( $attach_id ) ) {

					try {
						$attach_data = et_get_attachment_data( $attach_id );

						$options = AE_Options::get_instance();
						global $current_user;
						$comment_id = wp_insert_comment( array(
							'comment_post_ID'      => $project_id,
							'comment_author'       => $current_user->data->user_login,
							'comment_author_email' => $current_user->data->user_email,
							'comment_content'      => sprintf( __( "%s has successfully uploaded a file", ET_DOMAIN ), $current_user->data->display_name ),
							'comment_type'         => 'message',
							'user_id'              => $current_user->data->ID,
							'comment_approved'     => 1
						) );
						$file_arr   = array( $attach_id );
						if ( $imgType == 'file' ) {
							update_comment_meta( $comment_id, 'fre_comment_file', $file_arr );
						} else if ( $imgType == 'attach' ) {
							update_comment_meta( $comment_id, 'fre_comment_file_attach', $file_arr );
						}
						update_post_meta( $attach_id, 'comment_file_id', $comment_id );
						$project = get_post( $project_id );
						Fre_MessageAction::fre_update_project_meta( $project );
						// save this setting to theme options
						// $options->$imgType = $attach_data;
						// $options->save();
						/**
						 * do action to control how to store data
						 *
						 * @param $attach_data the array of image data
						 * @param $request ['data']
						 * @param $attach_id the uploaded file id
						 */

						//do_action('ae_upload_image' , $attach_data , $_POST['data'], $attach_id );
						$attachment             = get_post( $attach_id );
						$attachment->post_date  = get_the_date( 'F j, Y g:i A', $attachment->ID );
						$attachment->project_id = $project_id;
						$attachment->comment_id = $comment_id;
						$attachment->avatar     = get_avatar( $author_id );
						$attachment->file_size  = size_format( filesize( get_attached_file( $attachment->ID ) ) );
						$file_type              = wp_check_filetype( get_attached_file( $attachment->ID ) );
						$attachment->file_type  = $file_type['ext'];
						$res                    = array(
							'success'    => true,
							'msg'        => __( 'File has been uploaded successfully', ET_DOMAIN ),
							'data'       => $attach_data,
							'attachment' => $attachment
						);
					} catch ( Exception $e ) {
						$res['msg'] = __( 'Error when updating settings.', ET_DOMAIN );
					}
				} else {
					$res['msg'] = $attach_id->get_error_message();
				}
			} else {
				$res['msg'] = __( 'Uploaded file not found', ET_DOMAIN );
			}
		}
	}

	// send json to client
	wp_send_json( $res );
}

/**
 * Check post type to use pending post
 *
 * @since 1.5.2
 *
 * @author Tambh
 */
add_filter( 'use_pending', 'filter_post_type_use_pending', 10, 2 );
function filter_post_type_use_pending( $pending, $post_type ) {
	if ( $post_type == PROFILE || $post_type == PORTFOLIO ) {
		$pending = false;
	}

	return $pending;
}

function mail_logo( $logo ) {
	if ( empty( $logo ) ) {
		$logo = get_template_directory_uri() . "/img/logo-fre-black.png";
	}

	return $logo;

}

add_filter( 'ae_mail_logo_url', 'mail_logo' );

if ( ! function_exists( 'fre_show_credit' ) ):

	/**
	 * conver credit number of curent user to number can bid and display as html.
	 * @since   1.7.9
	 * @author danng
	 * @return  void
	 */
	function fre_show_credit( $user_role ) {
		global $user_ID, $ae_post_factory, $post;
		/*
		* only show credit number if current user is freelancer or share role and employer role
		 */
		if ( ( $user_role == FREELANCER || ( fre_share_role() && in_array( $user_role, array(
						FREELANCER,
						EMPLOYER
					) ) ) ) && ae_get_option( 'pay_to_bid', false )
		) {

			$credits_a_bid   = (int) ae_get_option( 'ae_credit_number', 1 );
			$credits         = get_user_credit_number( $user_ID );
			$credits_pending = get_user_credit_number_pending( $user_ID );
			// Check user profile
			$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
			$profile    = get_post( $profile_id );
			?>

            <div class="fre-work-package-wrap">
                <div class="fre-work-package">
					<?php
					if ( $credits > 0 ) {
						printf( __( '<p>You have <span class="number"><b>%s</b></span> available bid(s).</p>', ET_DOMAIN ), $credits );
						if ( $credits_pending > 0 ) {
							printf( __( '<p><span class="number">%s</span> pending bid(s) are under admin review.</p>', ET_DOMAIN ), $credits_pending );
						}
					} else {
						if ( $credits_pending > 0 ) {
							printf( __( '<p>You have <span class="number"><b>0</b></span> available bid(s).</p>', ET_DOMAIN ) );
							printf( __( '<p><span class="number">%s</span> pending bid(s) are under admin review.</p>', ET_DOMAIN ), $credits_pending );
						} else {
							if ( ! ( ! $profile || ! is_numeric( $profile_id ) ) ) {
								printf( __( '<p>You have <span class="number"><b>0</b></span> available bid(s).</p>', ET_DOMAIN ) );
							} else {
								if ( ae_get_option( 'pay_to_bid', false ) ) {
									printf( __( '<p>You have <span class="number"><b>0</b></span> available bid(s).</p>', ET_DOMAIN ) );
								}
							}
						}
					}
					printf( __( '<p>If you want to get more bids, you can directly move to purchase page by clicking the next button.</p>', ET_DOMAIN ) );
					?>
                    <a class="fre-normal-btn-o"
                       href="<?php echo et_get_page_link( 'upgrade-account' ); ?>"><?php _e( 'Purchase more bids', ET_DOMAIN ); ?></a>
                </div>
            </div>

			<?php
		}
	}
endif;