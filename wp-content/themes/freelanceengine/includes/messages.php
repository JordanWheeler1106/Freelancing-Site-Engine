<?php
class Fre_Message extends AE_Comments
{
    public static $instance;

    /**
     * return class $instance
     */
    public static function get_instance() {
        if (self::$instance == null) {

            self::$instance = new Fre_Message();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->comment_type = 'message';
        $this->meta = array();

        $this->post_arr = array();
        $this->author_arr = array();

        $this->duplicate = true;
        $this->limit_time = '';
    }

    function convert($comment, $thumb = 'thumbnail', $merge_post = false, $merge_author = false) {
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');

        /**
         * add comment meta
         */
        if (!empty($this->meta)) {
            foreach ($this->meta as $key => $value) {
                $comment->$value = get_comment_meta($comment->comment_ID, $value, true);
            }
        }

        $comment->comment_content = wpautop(esc_attr($comment->comment_content));

        // comment link
        $comment->comment_link = get_permalink( $comment->comment_post_ID );

        $comment->ID = $comment->comment_ID;
        $comment->id = $comment->comment_ID;
        $comment->avatar = get_avatar($comment->user_id, '33');

        unset($comment->comment_author_email);

//        $comment->message_time = sprintf(__('on %s', ET_DOMAIN) , get_comment_date($date_format, $comment)) . '&nbsp;' . sprintf(__('at %s', ET_DOMAIN) , get_comment_date($time_format, $comment));
        $comment->message_time = sprintf( _x( '%s ago', '%s = human-readable time difference', ET_DOMAIN ), human_time_diff( strtotime($comment->comment_date), current_time( 'timestamp' ) ));
        $file_arr = get_comment_meta( $comment->comment_ID, 'fre_comment_file', true );
        $comment->file_list = '';
        if(!empty($file_arr)) {
            $attachment = get_posts(array('post_type' => 'attachment', 'post__in' => $file_arr));
            $current_user_id = get_current_user_id();
            ob_start();
            echo '<ul class="list-file-attack">';
            foreach ($attachment as $key => $file) {
                if($file->post_author == $current_user_id)
                    $html_removeAtt = '<a href="#" data-id="'.$file->ID.'"><i class="fa fa-times removeAtt" data-id="'.$file->ID.'"></i></a>';
                else
                    $html_removeAtt = '';
                echo '<li><a target="_blank" href="'.$file->guid.'" class="attack-file"><i class="fa fa-paperclip"></i> '.$file->post_title.'</a> '.$html_removeAtt.'</li>';
            }
            echo '</ul>';
            $message_file = ob_get_clean();
            $comment->file_list = $message_file;
        }

        $comment = apply_filters( 'ae_convert_message', $comment );
        $comment->str_message = sprintf(__('<span class="author-name">%s</span> marked <span class="">"%s"</span> as <span class="status">%s</span><span class="changelog-time" >%s</span>',ET_DOMAIN), $comment->author_name, $comment->milestone_title, $comment->action, $comment->message_time);
        return $comment;
    }
}

class Fre_MessageAction extends AE_Base
{
    public static $instance;

    /**
     * return class $instance
     */
    public static function get_instance() {
        if (self::$instance == null) {

            self::$instance = new Fre_MessageAction();
        }
        return self::$instance;
    }

    function __construct() {

        // send message
        $this->add_ajax('ae-sync-message', 'sendMessage');

        // get older message
        $this->add_ajax('ae-fetch-messages', 'fetchMessage');

        $this->add_action( 'template_redirect', 'preventAccessWorkspace' );

        $this->add_action( 'after_sidebar_single_project', 'addWorkSpaceLink' );
        $this->add_filter( 'heartbeat_settings', 'fre_change_heartbeat_rate');
        $this->add_filter( 'heartbeat_received', 'fre_send_data_to_heartbeat', 10, 2 );

        // init message object
        $this->comment = Fre_Message::get_instance();
        //delete attack file
        $this->add_ajax('free_remove_attack_file', 'removeAttack');
    }

    /**
     * ajax callback sync message
     * $request ajax from client with comment_post_ID, comment_content
     */
    function sendMessage() {
        global $user_ID;

        // check projec id is associate with current user
        $args = array();
        /**
         * validate data
         */
        if (empty($_REQUEST['comment_post_ID'])) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("Error! Can not specify which project you are working on.", ET_DOMAIN)
            ));
        }

        if (empty($_REQUEST['comment_content'])) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("You cannot send an empty message.", ET_DOMAIN)
            ));
        }

        if (!$user_ID) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("You have to login.", ET_DOMAIN)
            ));
        }

        $comment_post_ID = $_REQUEST['comment_post_ID'];

        // check project owner
        $project = get_post($comment_post_ID);

        // check freelancer was accepted on project
        $bid_id = get_post_meta($comment_post_ID, "accepted", true);
        $bid = get_post($bid_id);

        // current user is not project owner, or working on
        if ($user_ID != $project->post_author && $user_ID != $bid->post_author) {
            wp_send_json(array(
                'success' => false,
                'msg' => __("You are not working on this project.", ET_DOMAIN)
            ));
        }

        /**
         * set message data
         */
        $_REQUEST['comment_approved'] = 1;
        $_REQUEST['type'] = 'message';

        $comment = $this->comment->insert($_REQUEST);

        if (!is_wp_error($comment)) {

            // get comment data
            $comment = get_comment($comment);
            if(isset($_REQUEST['fileID'])) {
                $file_arr = array();
                foreach ((array)$_REQUEST['fileID'] as $key => $file) {
                    $file_arr[] = $file['attach_id'];
                }
                update_comment_meta( $comment->comment_ID, 'fre_comment_file', $file_arr );
            }
            /**
             * fire an action fre_send_message after send message
             * @param object $comment
             * @param object $project
             * @param object $bid
             * @author Dakachi
             */
            do_action('fre_send_message', $comment, $project, $bid);
            $this->fre_update_project_meta($project, 1);
            // send json data to  client
            wp_send_json(array(
                'success' => true,
                'data' => $this->comment->convert($comment)
            ));
        } else {
            wp_send_json(array(
                'success' => false,
                'msg' => $comment->get_error_message()
            ));
        }
    }

    /**
     * ajax callback get message collection
     * @author Dakachi
     */
    function fetchMessage() {
        global $user_ID;
        $review_object = $this->comment;

        // get review object

        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 2;
        $query = $_REQUEST['query'];

        // If milestone was deactived, will get all comment are not changelog
        if( !defined( 'MILESTONE_DIR_URL' ) ) {
            $query['meta_query'] = array(
                array(
                    'key' => 'changelog',
                    'value' => '',
                    'compare' => 'NOT EXISTS'
                )
            );
        }

        $map = array(
            'status' => 'approve',
            'type' => 'message',
            'number' => '4',
            'total' => '10',
            'order' => 'DESC',
            'orderby' => 'date'
        );

        $query['page'] = $page;
        $all_cmts   = get_comments(  $query );

        /**
         * get page 1 reviews
        */
        if( !isset($query['total']) ){
            $query['number'] = get_option('posts_per_page');

            $total_messages = count($all_cmts);
            $comment_pages  =   ceil( $total_messages/$query['number']);
            $query['total'] = $comment_pages;
        }
        //add_filter( 'comments_clauses' , array($this, 'groupby') );
        $data = $review_object->fetch($query);
        if (!empty($data)) {
            $data['success'] = true;
            if( isset($query['use_heartbeat']) && $query['use_heartbeat'] == 1){
                $project = fre_get_project_infor($data['data']['0']->comment_post_ID);
                if( $project ){
                    $this->fre_reset_project_meta($project);
                }
            }
            wp_send_json($data);
        } else {
            wp_send_json(array(
                'success' => false,
                'data' => $data
            ));
        }
    }
    /**
     * prevent user access workspace
     * @since 1.2
     * @author Dakachi
     */
    function preventAccessWorkspace(){
        if( isset($_REQUEST['workspace']) && $_REQUEST['workspace'] &&  !current_user_can( 'manage_options' ) ) {
            if(is_singular( PROJECT )){
                global $post, $user_ID;
                // check project owner
                $project = $post;

                // check freelancer was accepted on project
                $bid_id = get_post_meta($project->ID, "accepted", true);
                $bid = get_post($bid_id);
                // current user is not project owner, or working on
                if (!$bid_id || ($user_ID != $project->post_author && $user_ID != $bid->post_author) ) {
                    wp_redirect( get_permalink( $post->ID ) );
                    exit;
                }
            }
        }
    }

    function addWorkSpaceLink($project){
        $permission = fre_access_workspace($project);
        $project_link = get_permalink( $project->ID );
        if($permission){
            echo '<a class="workspace-link"  style="font-weight:600;" href='.add_query_arg(array('workspace' => 1), $project_link).'>'.__("Open Workspace", ET_DOMAIN).' <i class="fa fa-arrow-right"></i></a>';
        }
    }
    /**
     * change heartbeat rate
     * @param array $settings
     * @return array $settings
     * @since 1.6.5
     * @package void
     * @category void
     * @author Tambh
     */
    public function fre_change_heartbeat_rate($settings){
        $settings['interval'] = 20;
        return $settings;
    }
    /**
     * send data to heart beat
     * @param array $response
     * @param array $data
     * @return void
     * @since 1.6.5
     * @package freelanceengine
     * @category void
     * @author Tambh
     */
    public  function fre_send_data_to_heartbeat($response, $data){
        if( isset($data['new_message']) ){
            global $ae_post_factory;
            $post = get_post($data['new_message']);
            if( $post ){
                $project_obj = $ae_post_factory->get(PROJECT);
                $project = $project_obj->convert($post);
                $val = $this->fre_get_project_meta($project);
                $response['new_message'] = $val;
            }
        }
        return $response;
    }
    /**
     * update project meta when there is a new message send
     * @param integer $val
     * @param object $project
     * @return void
     * @since 1.6.5
     * @package freelanceengine
     * @category void
     * @author Tambh
     */
    public  function fre_update_project_meta($project, $val = 1){
        global $user_ID;
        if( $user_ID == $project->post_author ){
            update_post_meta($project->ID, 'fre_freelancer_new_msg', $val);
        }
        else{
            update_post_meta($project->ID, 'fre_employer_new_msg', $val);
        }
    }
     /**
     * reset project meta when there is a new message send
     * @param object $project
     * @return void
     * @since 1.6.5
     * @package freelanceengine
     * @category void
     * @author Tambh
     */
    public  function fre_reset_project_meta($project){
        global $user_ID;
        if( $user_ID == $project->post_author ){
            update_post_meta($project->ID, 'fre_employer_new_msg', 0);
        }
        else{
            update_post_meta($project->ID, 'fre_freelancer_new_msg', 0);
        }
    }
    /**
     * get project meta when there is a new message send
     * @param object $project
     * @return interger 1 if have fetch message and 0 if don't need fetch
     * @since 1.6.5
     * @package freelanceengine
     * @category void
     * @author Tambh
     */
    public  function fre_get_project_meta($project){
        global $user_ID;
        $val = 0;
        if( $user_ID == $project->post_author ){
            $val = get_post_meta($project->ID, 'fre_employer_new_msg', true);
        }
        else{
            $val = get_post_meta($project->ID, 'fre_freelancer_new_msg', true);
        }
       return (int)$val;
    }

    /**
     * Remove attack file
     * @param post_id
     * @return bool
     * @since 1.7.5
     * @author Tuandq
     */
    public function removeAttack(){
        $post_id = $_POST['post_id'];
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->id;
        $post = get_post($post_id);
        $post_author = $post->post_author;
        if($current_user_id==$post_author)
            $result = wp_delete_post($post_id);
        if($result)
            echo $result->ID;
        else
            echo "0";
        wp_die();
    }

}

new Fre_MessageAction();
/**
 * function check user can access project workspace or not
 * @param object $project The project user want to access workspace
 * @since 1.2
 * @author Dakachi
 */
function fre_access_workspace($project){
    global $user_ID;
    // check freelancer was accepted on project
    $bid_id = get_post_meta($project->ID, "accepted", true);
    $bid = get_post($bid_id);

    // current user is not project owner, or working on
    if($project->post_status == 'close'){
        if(current_user_can('manage_options')){
            return true;
        }else if ($bid_id && ($user_ID == $project->post_author || $user_ID == $bid->post_author)) {
            return true;
        }
    }
    return false;
}