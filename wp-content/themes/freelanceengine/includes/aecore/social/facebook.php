<?php
class ET_FaceAuth extends ET_SocialAuth
{
    private $fb_secret_key;
    protected $fb_app_id;
    protected $fb_token_url;
    protected $fb_exchange_token;
    public function __construct() {
        parent::__construct('facebook', 'et_facebook_id', array(
            'title' => __('SIGN IN WITH FACEBOOK', ET_DOMAIN) ,
            'content' => __("This seems to be your first time signing in using your Facebook account.If you already have an account, please log in using the form below to link it to your Facebook account. Otherwise, please enter an email address and a password on the form, and a username on the next page to create an account.You will only do this step ONCE. Next time, you'll get logged in right away.", ET_DOMAIN) ,
            'content_confirm' => __("Please provide a username to continue", ET_DOMAIN)
        ));
        //$this->add_action('init', 'auth_facebook');
        $this->fb_app_id = ae_get_option('et_facebook_key', false);
        $this->fb_secret_key = ae_get_option('et_facebook_secret_key', false);
        $this->fb_token_url = 'https://graph.facebook.com/me';
        $this->fb_exchange_token = 'https://graph.facebook.com/oauth/access_token';
        $this->add_action('wp_enqueue_scripts', 'add_scripts', 20);
        $this->add_ajax('et_facebook_auth', 'auth_facebook');
    }
    public function add_scripts() {
        //$this->add_script('facebook_auth', '//connect.facebook.net/en_US/all.js', array(), false, true);
        $this->add_script('facebook_auth', ae_get_url() . '/social/js/facebookauth.js', array(
            'jquery'
        ) , false, true);
        wp_localize_script('facebook_auth', 'facebook_auth', array(
            'appID' => ae_get_option('et_facebook_key') ,
            'auth_url' => home_url('?action=authentication')
        ));
    }
    // implement abstract method
    protected function send_created_mail($user_id) {
        do_action('et_after_register', $user_id);
    }
    public function auth_facebook() {
        try {
            // turn on session
            if (!isset($_SESSION)) {
                ob_start();
                @session_start();
            }
            $fb_appID = ae_get_option('et_facebook_key', false);
            $fb_secret_key = ae_get_option('et_facebook_secret_key', false);
            if ( !$this->fb_app_id || !$this->fb_secret_key ) {
                $resp = array(
                    'success' => false,
                    'msg' => __('Social login is invalid. Please contact administrator for help.', ET_DOMAIN)
                );
                wp_send_json($resp);
                return;
            }
            if( !isset( $_POST['fb_token'] ) || $_POST['fb_token'] == '' ){
                   $resp = array(
                    'success' => false,
                    'msg' => __('Social login is invalid. Please contact administrator for help.', ET_DOMAIN)
                );
                wp_send_json($resp);
                return;
            }
            /**
             * check user id with a access token
             */
            $token_url = $this->fb_token_url;
            $token_url .= '?fields=id&access_token='.$_POST['fb_token'];
            $check_userid = wp_remote_get( $token_url );
            $check_userid = json_decode( $check_userid['body'] );
            if( !isset( $check_userid->id ) ||  $check_userid->id == '' ){
                $resp = array(
                    'success' => false,
                    'msg' => __('Social login is invalid. Please contact administrator for help.', ET_DOMAIN)
                );
                wp_send_json($resp);
                return;
            }
            $check_userid = $check_userid->id;
            /**
             * check user vefified app
             *
             */
            $fb_exchange_token = $this->fb_exchange_token;
            $fb_exchange_token .= '?grant_type=fb_exchange_token&';
            $fb_exchange_token .= 'client_id='.$this->fb_app_id.'&';
            $fb_exchange_token .= 'client_secret='.$this->fb_secret_key.'&';
            $fb_exchange_token .= 'fb_exchange_token='.$_POST['fb_token'];
            // $fb_app_token = wp_remote_get('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id='.$this->fb_app_id.'&client_secret='.$this->fb_secret_key.'&fb_exchange_token=' . $_POST['fb_token']);
            $fb_app_token = wp_remote_get( $fb_exchange_token );
            if( !isset( $_POST['content'] ) || empty( $_POST['content'] ) ){
                $resp = array(
                    'success' => false,
                    'msg' => __('Social login is invalid. Please contact administrator for help.', ET_DOMAIN)
                );
                wp_send_json($resp);
                return;
            }
            $data = $_POST['content'];
            if( !isset( $data['id'] ) || $data['id'] == '' ){
                $resp = array(
                    'success' => false,
                    'msg' => __('Social login is invalid. Please contact administrator for help.', ET_DOMAIN)
                );
                wp_send_json($resp);
                return;
            }
            if ( isset($fb_app_token['body']) && $fb_app_token['body'] != '' ) {
                $fb_app_token = explode('&', $fb_app_token['body']);
                $fb_token = explode('=', $fb_app_token['0']);
                if ($check_userid != $data['id'] || !isset($fb_token[1]) || $fb_token[1] == '') {
                    $fb_token = $fb_token['1'];
                    $resp = array(
                        'success' => false,
                        'msg' => __('Please login by using your Facebook account again!')
                    );
                    wp_send_json($resp);
                    return;
                }
            } else {
                $resp = array(
                    'success' => false,
                    'msg' => __('Please login by using your Facebook account again!')
                );
                wp_send_json($resp);
                return;
            }
            // find usser
            $return = array(
                'redirect_url' => home_url()
            );
            $user = $this->get_user($data['id']);
            // if user is already authenticated before
            if ( $user ) {
                $result = $this->logged_user_in($data['id']);
                $ae_user = AE_Users::get_instance();
                $userdata = $ae_user->convert($user);
                $nonce = array(
                    'reply_thread' => wp_create_nonce('insert_reply') ,
                    'upload_img' => wp_create_nonce('et_upload_images') ,
                );

                $return = array(
                    'user' => $userdata,
                    'nonce' => $nonce
                );
            }
            // if user never authenticated before
            else {
                // avatar
                $ava_response = wp_remote_get('http://graph.facebook.com/' . $data['id'] . '/picture?type=large&redirect=false');
                if (!is_wp_error($ava_response)){
                    $ava_response = json_decode($ava_response['body']);
                    $et_avatar_url = $ava_response->data->url;
                }
                else $ava_response = false;

                $sizes = get_intermediate_image_sizes();
                $avatars = array();
                if ($ava_response) {
                    foreach ($sizes as $size) {
                        $avatars[$size] = array(
                            $ava_response->data->url
                        );
                    }
                } else {
                    $avatars = false;
                }
                $data['name'] = str_replace(' ', '', sanitize_user($data['name']));
                $username = $data['name'];
                $params = array(
                    'user_login' => $username,
                    'user_email' => isset($data['email']) ? $data['email'] : false,
                    'description' => isset($data['bio']) ? $data['bio'] : false,
                    'user_location' => isset($data['location']) ? $data['location']['name'] : false,
                    'et_avatar' => $avatars,
                    'et_avatar_url' => $et_avatar_url,
                );
                //remove avatar if cant fetch avatar
                foreach ($params as $key => $param) {
                    if ($param == false) unset($params[$key]);
                }

                $_SESSION['et_auth'] = serialize($params);
                $_SESSION['et_social_id'] = $data['id'];
                $_SESSION['et_auth_type'] = 'facebook';

                // try to use et session
                et_write_session('et_auth', serialize($params));
                et_write_session('et_social_id', $data['id']);
                et_write_session('et_auth_type', 'facebook');

                $return['params'] = $params;
                $return['redirect_url'] = $this->auth_url;
            }
            $resp = array(
                'success'   => true,
                'msg'       => __('You have logged in successfully', ET_DOMAIN),
                'redirect'  => home_url(),
                'data'      => $return
            );
        }
        catch(Exception $e) {
            $resp = array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
        wp_send_json($resp);
    }
}