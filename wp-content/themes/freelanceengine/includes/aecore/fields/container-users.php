<?php
/**
 * Class render user manage in engine themes backend
 * - list user
 * - search user
 * - load more user
 * @since 1.0
 * @author Dakachi
*/
class AE_UsersContainer {

	/**
	 * construct a user container
	*/
	function __construct( $args = array(), $roles = '' ) {
		$this->args		=	$args;
		$this->roles	=	$roles;
	}

	/**
	 *
	*/
    function render() {
    	global $wp_roles, $user;

    	$number = get_option('posts_per_page');
    	$args = array ('number' => $number, 'count_total' => true, 'orderby' => 'user_registered', 'order' => 'DESC' );
    	$users_query	= 	new WP_User_Query($args);

    	$total	=	$users_query->total_users;
    	$users	=	$users_query->results;

    	$pages	=	ceil($total/$number);

    	$user_data	=	array();

    	$role_names	=	$wp_roles->role_names;

    	$ae_users	=	AE_Users::get_instance();

    ?>
		<div class="et-main-content user-container" id="<?php echo $this->args['id']; ?>">

			<div class="search-box et-member-search">
				<div id="search_users" >
					<span class="et-search-role">
						<select name="role" id="" class="et-input" >
							<option value="" ><?php _e("All", ET_DOMAIN); ?></option>
							<?php foreach ($role_names as $role_name => $role_label) {
									echo '<option value="'. $role_name .'" >'. $role_label .'</option>';
							} ?>
						</select>
					</span>
					<span class="et-search-input">
						<input type="text" class="et-input user-search" name="keyword" placeholder="<?php _e("Search users...", ET_DOMAIN); ?>">
						<span class="icon" data-icon="s"></span>
					</span>
				</div>
			</div>
			<!-- // user search box -->

			<div class="et-main-main no-margin clearfix overview list">
				<div class="title font-quicksand"><?php _e('All Users', ET_DOMAIN) ?></div>
				<ul class="list-inner list-payment users-list">
					<?php
					foreach ($users as $user) {
						$user_data[]	=	$ae_users->convert($user);
						ae_get_template_part('user' , 'item');
					} ?>
				</ul>
				<script type="application/json" id="ae_users_list">
					<?php
					    echo json_encode( array('users' =>  $user_data , 'pages' => $pages , 'query' => $args ) );
					?>
				</script>
				<?php if( $pages > 1 ) { ?>
					<button class="et-button btn-button load-more" >
						<?php _e('More Users', ET_DOMAIN) ?>
					</button>
				<?php } ?>
			</div>
			<!-- //user list -->

			<!-- ban modal -->
			<div class="modal fade" id="ban_modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="form_ban_user" action="">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title"><?php _e('Ban user',ET_DOMAIN) ?> <span class="display-name"></span></h4>
							</div>
							<div class="modal-body">
									<input type="hidden" name="id" value="">
									<div class="dialog-form-item">
										<label for=""><?php _e('Ban for', ET_DOMAIN) ?></label> <br>
										<select name="expired" id="">
											<?php
											$expired = et_get_ban_expired_period();
											foreach ($expired as $day) {
												echo '<option value="' . $day['value'] . '">' . $day['label'] . '</option>';
											} ?>
										</select>
									</div>
									<div class="dialog-form-item">
										<label for=""><?php _e('Reason', ET_DOMAIN) ?></label> <br>
										<textarea name="reason" id="" cols="30" rows="10"></textarea>
									</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', ET_DOMAIN) ?></button>
								<button type="submit" class="btn btn-primary"><?php _e('Submit', ET_DOMAIN) ?></button>
							</div>
						</form>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- /ban modal -->
		</div>
    <?php
    	$this->render_js_template ();
    }

    function render_js_template() {
    	global $wp_roles;
    	$role_names	=	$wp_roles->role_names;
    ?>
		<script type="text/template" id="user-item-template">
			<div class="et-mem-container">
				<div class="et-mem-avatar">
					{{= avatar }}
				</div>
				<!-- action change user role -->
				<div class="et-act">
					<# if( !banned ) { #>
						<select name="role" class="role-change regular-input">
							<?php foreach ($role_names as $role_name => $role_label) {
									echo '<option <# if( role == "'.$role_name.'") { #> selected="selected" <# } #> value="'. $role_name .'" >'. $role_label .'</option>';
							} ?>
						</select>

						<?php
							// Action to add more template to user item in admin setting
							do_action( 'ae_admin_user_js_template_action' );
						?>
					<# } else { #>
						<span class="ban-badge"><?php printf( __('Banned until %s', ET_DOMAIN), '{{= ban_expired }}' )  ?></span>
					<# } #>

					<!-- Ban Button -->
					<# if ( role != 'administrator' && banned ) { #>
						<a class="et-act-unban" href="#" title="<?php _e( 'Unban this user', ET_DOMAIN ) ?>"><span class="icon" data-icon=")"></span></a>
					<# } else if ( role != 'administrator' ) { #>
						<a class="et-act-ban" href="#" data-toggle="modal" data-target="#ban_modal" title="<?php _e( 'Ban this user', ET_DOMAIN ) ?>"><span class="icon" data-icon="("></span></a>
					<# } #>
					<!-- End / Ban Button -->

					<?php if(current_user_can( 'administrator' )){ ?>
					<# if(register_status == "unconfirm"){ #>
					<a class="action et-act-confirm" data-act="confirm" href="javascript:void(0)" title="Confirm this user">
						<span class="icon" data-icon="3"></span>
					</a>
					<# } #>
					<?php } ?>
				</div>
				<div class="et-mem-detail">
					<?php do_action( 'ae_admin_before_user_details_js_template'); ?>
					<div class="et-mem-top">
						<span class="name">{{= display_name }}</span>
						<span class="thread icon" data-icon="w">{{= post_count }}</span>
						<span class="comment icon" data-icon="q">{{= comment_count }}</span>
					</div>
					<div class="et-mem-bottom">
						<span class="date">{{= join_date }}</span>
						<# if(location) { #>
						<span class="loc icon" data-icon="@">{{= location }}</span>
						<# } else { #>
							<span class="loc icon" data-icon="G"></span>
						<# } #>
					</div>
					<?php do_action( 'ae_admin_after_user_details_js_template' ); ?>
				</div>
			</div>
		</script>
    <?php
    }
}