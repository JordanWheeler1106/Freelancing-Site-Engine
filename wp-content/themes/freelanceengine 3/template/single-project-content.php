<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get( PROJECT );
$convert     = $project = $post_object->current_post;
$project     = $post_object->convert( $post );
$author_id   = $project->post_author;
$rating      = Fre_Review::employer_rating_score( $author_id );

$user_data = get_userdata( $author_id );

$profile_id = get_user_meta( $author_id, 'user_profile_id', true );
$profile    = array();
if ( $profile_id ) {
	$profile_post = get_post( $profile_id );
	if ( $profile_post && ! is_wp_error( $profile_post ) ) {
		$profile = $post_object->convert( $profile_post );
	}
}

$hire_freelancer = fre_count_hire_freelancer( $author_id );

$attachment = get_children( array(
	'numberposts' => - 1,
	'order'       => 'ASC',
	'post_parent' => $post->ID,
	'post_type'   => 'attachment'
), OBJECT );

?>

<div class="project-detail-box no-padding">
    <div class="project-detail-desc">
        <h4><?php _e( 'Project Desciption', ET_DOMAIN ); ?></h4>
        <p><?php the_content(); ?></p>
		<?php
		if ( ! empty( $attachment ) ) {
			echo '<ul class="project-detail-attach">';
			foreach ( $attachment as $key => $att ) {
				$file_type = wp_check_filetype( $att->post_title, array(
						'jpg'  => 'image/jpeg',
						'jpeg' => 'image/jpeg',
						'gif'  => 'image/gif',
						'png'  => 'image/png',
						'bmp'  => 'image/bmp'
					)
				);
				echo '<li><a href="' . $att->guid . '"><i class="fa fa-paperclip" aria-hidden="true"></i>' . $att->post_title . '</a></li>';
			}
			echo '</ul>';
		}
		?>
    </div>
    <div class="project-detail-extend">
        <div class="project-detail-skill">
			<?php list_tax_of_project( get_the_ID(), __( 'Skills Required', ET_DOMAIN ), 'skill' ); ?>
        </div>
        <div class="project-detail-category">
			<?php list_tax_of_project( get_the_ID(), __( 'Category', ET_DOMAIN ) ); ?>
        </div>
		<?php

		//milestone
		$args = array(
			'post_type'      => 'ae_milestone',
			'posts_per_page' => - 1,
			'post_status'    => 'any',
			'post_parent'    => $project->ID,
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_key'       => 'position_order'
		);

		$query = new WP_Query( $args );

		if ( function_exists( 'ae_query_milestone' ) && $query->have_posts() ) { ?>

            <div class="project-detail-milestone">
                <h4><?php echo __( "Milestones", ET_DOMAIN ); ?></h4>
				<?php do_action( 'after_sidebar_single_project', $project ); ?>
            </div>

		<?php } ?>

		<?php
		//Customfields
		if ( function_exists( 'et_render_custom_field' ) ) {
			et_render_custom_field( $project );
		}

		?>
        <div class="project-detail-about">
			<?php _e( '<h4>Employer Information</h4>', ET_DOMAIN ); ?>
            <div class="project-employer-rating">
                <span class="rate-it" data-score="<?php echo $rating['rating_score']; ?>"></span>
                <span class=""><?php printf( __( '%s project(s) posted', ET_DOMAIN ), fre_count_user_posts_by_type( $author_id, 'project', '"publish","complete","close","disputing","disputed", "archive" ', true ) ); ?></span>
                <span><?php printf( __( 'hire %s freelancers', ET_DOMAIN ), $hire_freelancer ); ?></span>
				<?php
				if ( ! empty( $profile->tax_input['country'] ) ) {
					echo '<span>' . $profile->tax_input['country']['0']->name . '</span>';
				} ?>
            </div>
            <div class="project-employer-since">
                <span>
                    <?php _e( 'Member since: ', ET_DOMAIN );
                    if ( isset( $user_data->user_registered ) ) {
	                    echo date_i18n( get_option( 'date_format' ), strtotime( $user_data->user_registered ) );
                    } ?>
                </span>
            </div>
        </div>
    </div>
</div>