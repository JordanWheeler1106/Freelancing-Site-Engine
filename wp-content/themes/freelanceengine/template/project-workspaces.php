<?php
/**
 * The template for displaying project message list, mesage form in single project
 */
global $post, $user_ID;
$date_format = get_option('date_format');
$time_format = get_option('time_format');

// Load milestone change log if ae-milestone plugin is active
if( defined( 'MILESTONE_DIR_URL' ) ) {
    $query_args = array(
        'type' => 'message',
        'post_id' => $post->ID ,
        'paginate' => 'load',
        'order' => 'DESC',
        'orderby' => 'date',
    );
} else {
    $query_args = array(
        'type' => 'message',
        'post_id' => $post->ID ,
        'paginate' => 'load',
        'order' => 'DESC',
        'orderby' => 'date',
        'meta_query' => array(
            array(
                'key' => 'changelog',
                'value' => '',
                'compare' => 'NOT EXISTS'
            ),
        )
    );
}
$query_args['text'] = __("Load older message", ET_DOMAIN);
echo '<script type="data/json"  id="workspace_query_args">'. json_encode($query_args) .'</script>';
/**
 * count all reivews
*/
$total_args = $query_args;
$all_cmts   = get_comments( $total_args );

/**
 * get page 1 reviews
*/
$query_args['number'] = get_option('posts_per_page');
$comments = get_comments( $query_args );

$total_messages = count($all_cmts);
$comment_pages  =   ceil( $total_messages/$query_args['number'] );
$query_args['total'] = $comment_pages;

$messagedata = array();
$message_object = Fre_Message::get_instance();
?>
<div class="project-workplace-details workplace-details">
    <div class="row">
        <div class="col-md-8 message-container">
            <?php
                if( et_load_mobile() ) {
                    do_action('after_mobile_project_workspace', $post);
                }
            ?>

        	<div class="work-place-wrapper pd-r-15">
                <?php if($post->post_status != 'complete') { ?>
            	<form class="form-group-work-place-wrapper form-message">
                	<div class="form-group-work-place file-container"  id="file-container">
                        <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'file_et_uploader' ) ?>"></span>

                        <div class="content-chat-wrapper form-content-chat-wrapper">
                            <textarea name="comment_content" class="content-chat" placeholder="<?php _e('Type here to reply',ET_DOMAIN);?>"></textarea>
                            <div class="submit-btn-msg">
                                <span class="submit-icon-msg"><input type="submit" name="submit" value="" class="submit-chat-content"></span>
                            </div>
                            <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
                        </div>
                        <ul class="file-attack apply_docs_file_list" id="apply_docs_file_list">
                        </ul>
                        <div id="apply_docs_container">
                            <a href="#" class="btn btn-primary attack attach-file" id="apply_docs_browse_button"><?php _e('Add File ',ET_DOMAIN);?><i class="fa fa-plus-circle"></i></a>
                        </div>
                    </div>
                </form>
                <?php } ?>
                <ul class="list-chat-work-place  new-list-message-item">
                    <?php
                    foreach ($comments as $key => $message) {
                        $convert = $message_object->convert($message);
                        $messagedata[] = $convert;
                        $author_name = get_the_author_meta( 'display_name', $message->user_id );

                        if( !empty( $convert->changed_milestone_id ) && !empty( $convert->action ) && !empty( $convert->changelog ) ) {
                            // Render html for changelog
                            ?>
                            <li class="message-item changelog-item" id="comment-<?php echo $message->comment_ID; ?>">
                               <?php
                                    $changelog_time = human_time_diff( strtotime($convert->comment_date), current_time( 'timestamp' ) ). ' ago';
                                    printf( __( '<span class="author-name">%s</span> marked <span class="">"%s"</span> as <span class="status">%s</span><span class="changelog-time" >%s</span>', ET_DOMAIN ), $convert->author_name, $convert->milestone_title, $convert->action, $changelog_time ); ?>
                            </li>
                            <?php
                        } else {
                            // Render html for message
                            ?>
                            <li class="message-item <?php echo $message->user_id == $user_ID ? '' : 'partner-message-item' ?>" id="comment-<?php echo $message->comment_ID; ?>">
                                <div class="form-group-work-place">
                                    <?php
                                        if($message->user_id != $user_ID){ ?>
                                            <div class="avatar-chat-wrapper">
                                                <a href="#" class="avatar-employer">
                                                    <?php echo $message->avatar; ?>
                                                </a>
                                            </div>
                                    <?php    }  ?>
                                    <div class="content-chat-wrapper">
                                        <div class="content-chat fixed-chat">
                                            <div class="param-content"><?php echo $convert->comment_content; ?></div>
                                        <?php echo $convert->file_list; ?>
                                        </div>
                                        <div class="date-chat">
                                        <?php
                                            //echo $message->message_time;
                                            echo human_time_diff( strtotime($convert->comment_date), current_time( 'timestamp' ) ). __(' ago', ET_DOMAIN);
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <div class="paginations-wrapper" >
                    <?php
                    if($comment_pages > 1) {
                        ae_comments_pagination( $comment_pages, $paged ,$query_args );
                    }
                     ?>
                </div>
                <?php echo '<script type="json/data" class="postdata" > ' . json_encode($messagedata) . '</script>'; ?>
            </div>
        </div>
        <?php if(!et_load_mobile()) { ?>
        <div class="col-md-4 workplace-project-details">
        	<div class="content-require-project">
                <?php
                if(fre_access_workspace($post)) {
                    echo '<a style="font-weight:600;" href="'.get_permalink( $post->ID ).'">
                            <i class="fa fa-arrow-left"></i> '.__("Back To Project Page", ET_DOMAIN).
                        '</a>';
                }
                ?>
                <?php do_action('after_sidebar_single_project_workspace', $post); ?>

                <h4><?php _e('Project description:',ET_DOMAIN);?></h4>
                <?php the_content(); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
