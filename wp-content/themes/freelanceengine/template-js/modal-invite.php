<?php
global $wp_query, $ae_post_factory, $post, $current_user, $wpdb, $user_ID;
$post_object = $ae_post_factory->get(PROJECT);
$author_id = get_query_var('author');
if(!$author_id) return;

$sql = "select ID from  $wpdb->posts as P
						join $wpdb->comments as C
							on P.ID=C.comment_post_ID
						join $wpdb->commentmeta as M
							on M.comment_id=C.comment_ID
					where post_status = 'publish'
							AND M.meta_key ='invite'
							AND P.post_author = $user_ID
							AND C.comment_approved = 1
							AND M.meta_value = $author_id
					";
$results = $wpdb->get_col($sql);
query_posts( array('post_status' => 'publish', 'showposts' => -1, 'post_type' => 'project', 'author' => $current_user->ID , 'post__not_in' => $results));

?>
<div class="modal fade <?php if(!have_posts()){ echo 'moddal-no-project'; }?>" id="modal_invite">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<?php if(have_posts()){ ?>
					<h4 class="modal-title">
						<?php 
							printf(__('Invite <span><strong>%s</strong></span> to join your project (choose one or more projects below):', ET_DOMAIN),
								get_the_author_meta('display_name', $author_id));
						?>
					</h4>
				<?php 
				}else{
					_e('There are currently no projects available to invite this user.', ET_DOMAIN);
				}
				?>
			</div>
			<div class="modal-body">
				<?php if(have_posts()) { ?>
				<form role="form" id="submit_invite" class="auth-form submit_invite">
					<div class="all-selected">
						<a class="select-all" href="#"><?php _e('Select all', ET_DOMAIN)?></a>
						<a class="remove-all" href="#" style="display:none;"><?php _e('Remove all', ET_DOMAIN)?></a>
					</div>
					<div class="form-group invites-list">
						<div class="list mCustomScrollbar">
							<?php
								while (have_posts()) { the_post();
									$budget = get_post_meta( $post->ID, 'et_budget', true );
							?>
								<div class="outer">
									<p class="name-project">
										<input type="checkbox" name="project_invites[]" value="<?php echo $post->ID; ?>">
										<span><?php the_title(); ?></span>
									</p>
									<p class="project-price"><?php echo fre_price_format($budget); ?></p>
								</div>
									<?php
								}
							?>
						</div>
					</div>
                    <div class="clearfix"></div>
					<div class="footer-invest">
						<button type="submit" class="btn-submit btn-sumary btn-sub-create" <?php if($wp_query->found_posts == 0){echo 'disabled="disabled"';} ?> >
							<?php _e('Invite', ET_DOMAIN) ?>
						</button>
					</div>
				</form>
				<?php }else {
					echo '<p class="lead text-info">';
					// _e("Currently, you do not have any project available to invite this user.", ET_DOMAIN);
					echo ' <a href="'.et_get_page_link('submit-project').'" >'.__("Post a project now", ET_DOMAIN).'</a>';
					echo '</p>';
				} ?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php wp_reset_query(); ?>