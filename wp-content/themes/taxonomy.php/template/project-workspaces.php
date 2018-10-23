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
$query_args['number'] = 12;//get_option('posts_per_page');
$comments = get_comments( $query_args );

$total_messages = count($all_cmts);
$comment_pages  =   ceil( $total_messages/$query_args['number'] );
$query_args['total'] = $comment_pages;

$messagedata = array();
$message_object = Fre_Message::get_instance();

$bid_id = get_post_meta($post->ID, "accepted", true);
$bid = get_post($bid_id);
?>

<div class="workplace-conversation-title">
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <h2><?php _e('Conversation', ET_DOMAIN);?></h2>
        </div>
        <div class="col-md-4 col-sm-4">
            <a href="<?php echo get_permalink( $post->ID ); ?>">&lt; <?php _e('Back to project detail', ET_DOMAIN);?></a>
        </div>
    </div>
</div>
<div class="project-workplace-details workplace-details">
    <div class="row">
        
        
        <div class="col-md-8 message-container">
            <?php
                if( et_load_mobile() ) {
                    do_action('after_mobile_project_workspace', $post);
                }
            ?>
            <?php if(!count($comments)) : 
                    if($post->post_status == 'close'):?>
                        <div class="list-chat-work-place-none"><?php _e('Send your first message to start the conversation', ET_DOMAIN);?></div>
                    <?php else: ?>
                        <div class="list-chat-work-place-none"><?php _e('No messages were received during the working process', ET_DOMAIN);?></div>
                    <?php endif; ?>
            <?php endif; ?>
            <div class="list-chat-work-place-wrap">
                <ul class="list-chat-work-place new-list-message-item">
                    <?php
                    $comments = array_reverse($comments);
                    foreach ($comments as $key => $message) {
                        $convert = $message_object->convert($message);
                        $messagedata[] = $convert;
                        $author_name = get_the_author_meta( 'display_name', $message->user_id );
                        $isFile = $message->isFile;
                        ?>
                        <li class="message-item <?php echo $message->user_id == $user_ID ? '' : 'partner-message-item' ?> <?php echo $message->isFile;?>" id="comment-<?php echo $message->comment_ID; ?>">
                            <div class="form-group-work-place">
                                <div class="content-chat-wrapper">
                                    <div class="content-chat fixed-chat">
                                        <div class="param-content"><?php echo $convert->comment_content;?></div>
                                    </div>
                                    <?php if($message->user_id == $user_ID && $post->post_status == 'close'){ ?>
                                        <a href="#" class="action delete" data-id="<?php echo $message->comment_ID; ?>">
                                        <i class="fa fa-trash"></i>
                                        </a>
                                    <?php } ?>
                                    <div class="date-chat">
                                        <?php echo human_time_diff( strtotime($convert->comment_date), current_time( 'timestamp' ) ). __(' ago', ET_DOMAIN); ?>
                                    </div>
                                </div>
                            </div>
                        </li>

                    <?php } ?>
                </ul>
            </div>
            <div class="work-place-wrapper">
                <?php if($post->post_status == 'close' && ($user_ID == $post->post_author || $user_ID == $bid->post_author)) { ?>
                <form class="form-group-work-place-wrapper form-message">
                    <div class="form-group-work-place ">
                        <div class="content-chat-wrapper form-content-chat-wrapper">
                            <textarea name="comment_content" class="content-chat" placeholder="<?php _e('Type here to reply',ET_DOMAIN);?>"></textarea>
                            <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
                        </div>
                    </div>
                    <div class="submit-btn-msg">
                        <span class="submit-icon-msg disabled">
                            <input type="submit" name="submit" value="" class="submit-chat-content disabled" disabled>
                        </span>
                    </div>
                </form>
                <?php } ?>
                <script type="application/json" class="ae_query"><?php echo json_encode($query_args);?></script>
            </div>
        </div>
        <?php if(!et_load_mobile()) { ?>
        <div class="col-md-4">
            <div class="workplace-project-details">
                <div class="content-require-project content-require-project-attachment active">
                    <div class="workplace-title-wrap">
                        <div class="workplace-title">
                            <h4><?php _e('Attachment:',ET_DOMAIN);?></h4>
                            <?php if($post->post_status == 'close'){ ?>
                            <div class="workplace-title-arrow file-container" id="file-container" style="font-size: 0;">
                                <div id="apply_docs_container">
                                    <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'file_et_uploader' ) ?>"></span>
                                    <span class="project_id" data-project="<?php echo $post->ID ?>"></span>
                                    <span class="author_id" data-author="<?php echo $user_ID ?>"></span>
                                    <a href="#" class="attack attach-file" id="apply_docs_browse_button">
                                        <i class="fa fa-plus"></i><span><?php _e('Upload file ',ET_DOMAIN);?></span>
                                    </a>
                                </div>
                                <span></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php 
                        // Attachment file in workspace
                        $attachment_comments = get_comments(array(
                            'post_id' => $post->ID ,
                            'meta_query' => array(
                                array(
                                    'key' => 'fre_comment_file',
                                    'value' => '',
                                    'compare' => '!='
                                )
                            )
                        ));
                        $attachments = array();
                        foreach ($attachment_comments as $key => $value) {
                            $file_arr = get_comment_meta($value->comment_ID, 'fre_comment_file', true);
                            if(is_array($file_arr)){
                                $attachment = get_posts(array('post_type' => 'attachment', 'post__in' => $file_arr));
                                $attachments = wp_parse_args($attachments, $attachment);
                            }
                        }
                    ?>
                        <ul class="list-file-attack">
                            <?php
                                $attachments = array_reverse($attachments);
                                foreach ($attachments as $key => $value) {
                                    $comment_file_id = get_post_meta($value->ID, 'comment_file_id', true);
                                    if($post->post_status == 'close' && $value->post_author == $user_ID && !$value->post_parent){
                                        $html_removeAtt = '<a href="#" data-post-id="'.$value->ID.'" data-project-id="'.$post->ID .'" data-file-name="'.$value->post_title.'" class="removeAtt"><i class="fa fa-times" aria-hidden="true" data-post-id="' . $value->ID . '" data-project-id="' . $post->ID . '" data-file-name="' . $value->post_title . '"></i></a>';
                                    }else{
                                        $html_removeAtt = '';
                                    }
                            ?>
                                    <li class="attachment-<?php echo $value->ID;?>">
                                        <span class="file-attack-name">
                                            <a href="<?php echo $value->guid;?>" target="_Blank"><?php echo $value->post_title?></a>
                                        </span>
                                        <span class="file-attack-time"><?php echo get_the_date( '', $value->ID );?></span>
                                        <?php echo $html_removeAtt;?>
                                    </li>
                            <?php } ?>
                        </ul>
                </div>
                <div class="content-require-project active">
                    <?php do_action('after_sidebar_single_project_workspace', $post); ?>
                </div>
                <div class="content-require-project">
                    <div class="workplace-title-wrap">
                        <div class="workplace-title">
                            <h4><?php _e('Project description:',ET_DOMAIN);?></h4>
                            <div class="workplace-title-arrow">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="workplace-project-description">
                        <?php the_content(); ?>
                        <?php
                            // Attachment project
                            $attachment_posts = get_posts(array(
                                'post_parent'       => $post->ID,
                                'post_type'         => 'attachment',
                                'posts_per_page'    => -1,
                                'post_status'       => 'any',
                            ));
                            if($attachment_posts){
                        ?>
                        <ul class="attachment-default-list">
                            <?php foreach ($attachment_posts as $key => $value) {?>
                                <li>
                                    <a href="<?php echo $value->guid;?>" target="_Blank">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i><?php echo $value->post_title?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </div>  
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
