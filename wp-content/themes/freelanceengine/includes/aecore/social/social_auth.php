<?php
abstract class ET_SocialAuth extends AE_Base
{
    protected $social_option;

    abstract protected function send_created_mail($user_id);

    protected $social_id = false;

    public function __construct($type, $social_option, $labels = array()) {
        $this->social_type = $type;
        $this->social_option = $social_option;
        $this->auth_url = add_query_arg('type', $this->social_type, ae_get_option('social_connect'));
        $this->labels = $labels;
        $this->add_action('wp_enqueue_scripts', 'enqueue_scripts');
        $this->add_action('template_redirect', 'social_redirect');
        $this->add_ajax('et_authentication_' . $type, 'authenticate_user');
        $this->add_ajax('et_confirm_username_' . $type, 'confirm_username');
    }
    public function enqueue_scripts() {
        global $current_user;
        if (isset($current_user->ID) && $current_user->ID != 0){
            $user_obj = new AE_Users();
            $user_data  = $user_obj->convert($current_user->data);
            $current_user->data = $user_data;
        }
        ?>
		<script type="text/javascript" id="current_user">
            var currentUser = <?php if (isset($current_user->ID) && $current_user->ID != 0) echo json_encode($current_user);
            else echo json_encode(array(
                'id' => 0,
                'ID' => 0
            )); ?>;
            var is_mobile = <?php echo json_encode(et_load_mobile()); ?>
            </script>
        <?php   $this->add_script('et-authentication', ae_get_url() . '/social/js/authentication.js', array(
                'jquery',
                'underscore',
                'backbone',
                'appengine'
            ));
        if( is_social_connect_page() ){
            if (!isset($_SESSION)) {
                ob_start();
                @session_start();
            }
            // try to use et session
            $et_session = et_read_session();
            if(isset($et_session['et_auth_type'])) {
                wp_localize_script('et-authentication', 'ae_auth', array(
                    'action_auth' => 'et_authentication_' . $et_session['et_auth_type'],
                    'action_confirm' => 'et_confirm_username_' . $et_session['et_auth_type']
                ));
            }elseif (isset($_SESSION['et_auth_type'])) {
                wp_localize_script('et-authentication', 'ae_auth', array(
                    'action_auth' => 'et_authentication_' . $_SESSION['et_auth_type'],
                    'action_confirm' => 'et_confirm_username_' . $_SESSION['et_auth_type']
                ));
            }
        }
        $this->register_style('social-connect-style', ae_get_url() . '/social/css/default.css');
    }

    public function social_redirect() {
        $flag = is_social_connect_page();
        if ( $flag && is_user_logged_in() ) {
            wp_redirect( home_url() );
            exit();
        }
        if ( $flag ) {
            global $et_data;
            if (isset($_GET['type']) && $_GET['type'] == $this->social_type) {
                $et_data['auth_labels'] = $this->labels;
            }
        }
    }
    protected function get_user($social_id) {
        $args = array(
            'meta_key' => $this->social_option,
            'meta_value' => trim($social_id) ,
            'number' => 1
        );
        $users = get_users($args);
        if (!empty($users) && is_array($users)) return $users[0];
        else return false;
    }
    protected function logged_user_in($social_id) {
        $ae_user = $this->get_user($social_id);
        if ($ae_user != false) {
            wp_set_auth_cookie($ae_user->ID);
            wp_set_current_user($ae_user->ID);
            return true;
        } else {
            return false;
        }
    }

    protected function _create_user($params) {
        // insert user
        $ae_user = AE_Users::get_instance();
        $result = $ae_user->insert($params);
        if (!is_wp_error($result)) {
            // send email here
            $this->send_created_mail($result);
            // login
            $ae_user = wp_signon(array(
                'user_login' => $params['user_login'],
                'user_password' => $params['user_pass']
            ));
            if (is_wp_error($ae_user)) {
                return $ae_user;
            } else {
                // Authenticate successfully
                return true;
            }
        } else {
            return $result;
        }
    }
    protected function connect_user($email, $password) {
        if ($this->social_id != false) {
            // get user first
            $ae_user = get_user_by('email', $email);
            if ($ae_user == false) return new WP_Error('et_password_not_matched', __("Username and password does not matched", ET_DOMAIN));
            // verify password
            if (wp_check_password($password, $ae_user->data->user_pass, $ae_user->ID)) {
                // connect user
                update_user_meta($ae_user->ID, $this->social_option, $this->social_id);
                return true;
            } else {
                return new WP_Error('et_password_not_matched', __("Username and password does not matched", ET_DOMAIN));
            }
        } else {
            return new WP_Error('et_wrong_social_id', __("There is an error occurred", ET_DOMAIN));
        }
    }
    protected function social_connect_success() {
        wp_redirect(home_url());
        exit;
    }
    public function authenticate_user() {
        try {
            // turn on session
            if (!isset($_SESSION)) {
                ob_start();
                @session_start();
            }
            $data = $_POST['content'];
            // find user first
            if (empty($data['user_email']) || empty($data['user_pass'])) throw new Exception(__('Login information is missing', ET_DOMAIN));
            $pattern = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/';
            //if (!preg_match($pattern, $data['user_email']))
            if (filter_var($data['user_email'], FILTER_VALIDATE_EMAIL) === false) throw new Exception(__('Please provide a valid email', ET_DOMAIN));
            $email = $data['user_email'];
            $pass = $data['user_pass'];
            $ae_user = get_user_by('email', $email);
            $return = array();
            // if user doesn't exist, create one
            if ($ae_user == false) {
                // save to session, waiting for username input
                if (isset($_SESSION['et_auth'])) $auth_info = unserialize($_SESSION['et_auth']);
                else $auth_info = array();
                $auth_info = wp_parse_args(array(
                    'user_email' => $email,
                    'user_pass' => $pass
                ) , $auth_info);
                $_SESSION['et_auth'] = serialize($auth_info);
                if (isset($auth_info['user_login'])) {
                    $auth_info['user_login'] = str_replace(' ', '', sanitize_user($auth_info['user_login']));
                    $ae_user = get_user_by('login', $auth_info['user_login']);
                    $ae_user = AE_Users::get_instance();
                    if (!$ae_user) {
                        $result = $ae_user->insert($auth_info);
                        if ($result == false || is_wp_error($result)) throw new Exception(__("Can not authenticate user", ET_DOMAIN));
                        else if (empty($_SESSION['et_social_id'])) {
                            throw new Exception(__("Can't find Social ID", ET_DOMAIN));
                        } else {
                            update_user_meta($result, $this->social_option, $_SESSION['et_social_id']);
                            do_action('et_after_register', $result);
                            wp_set_auth_cookie($result, 1);
                            unset($_SESSION['et_auth']);
                        }
                        $return = array(
                            'status' => 'linked',
                            'user' => $ae_user,
                            'redirect_url' => home_url()
                        );
                    } else {
                        $return = array(
                            'status' => 'wait'
                        );
                    }
                } else {
                    $return = array(
                        'status' => 'wait'
                    );
                }
            }
            // if user does exist, connect them
            else {
                // khi ti`m thay user bang email, kiem tra password
                // neu password dung thi dang nhap luon
                if (wp_check_password($pass, $ae_user->data->user_pass, $ae_user->ID)) {
                    // connect user
                    update_user_meta($ae_user->ID, $this->social_option, $_SESSION['et_social_id']);
                    //
                    wp_set_auth_cookie($ae_user->ID, 1);
                    unset($_SESSION['et_auth']);
                    $return = array(
                        'status' => 'linked',
                        'user' => $ae_user,
                        'redirect_url' => home_url()
                    );
                } else {
                    throw new Exception(__("This email is already existed. If you are the owner, please enter the right password", ET_DOMAIN));
                }
            }
            $resp = array(
                'success' => true,
                'msg' => '',
                'data' => $return
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
    public function confirm_username() {
        try {
            if (!isset($_SESSION)) {
                ob_start();
                @session_start();
            }
            // get data
            $data = $_POST['content'];
            $auth_info = unserialize($_SESSION['et_auth']);
            $username = $data['user_login'];
            if (isset($data['user_role']) && $data['user_role'] != '') {
                $user_roles = ae_get_option('social_user_role', false);
                if( !$user_roles ){
                    $user_roles = ae_get_social_login_user_roles_default();
                }
                $user_role = $data['user_role'];
                if ($user_roles && in_array($data['user_role'], $user_roles) && $data['user_role'] != 'Administrator') {
                    $auth_info['role'] = $user_role;
                }else{
                    $auth_info['role'] = $user_roles[$user_role];
                }
            }
            $et_session = et_read_session();
            if($et_session['et_social_id']) {
                $social_id = $et_session['et_social_id'];
            }else {
                $social_id = $_SESSION['et_social_id'];
            }
            // verify username
            $ae_user = get_user_by('login', $username);
            $return = array();
            if ($ae_user != false) throw new Exception(__('Username is existed, please choose another one', ET_DOMAIN));
            else {
                $auth_info['user_login'] = $username;
                // create user
                $ae_user = AE_Users::get_instance();
                $result = $ae_user->insert($auth_info);
                if (is_wp_error($result)) throw new Exception($result->get_error_message());
                else if (empty($social_id)) {
                    throw new Exception(__("Can't find Social ID", ET_DOMAIN));
                }
                else {
                    // creating user successfully
                    update_user_meta((int)$result->ID, $this->social_option, $social_id);
                    do_action('et_after_register', $result);
                    wp_set_auth_cookie((int)$result->ID, 1);
                    unset($_SESSION['et_auth']);
                    $return = array(
                        'user_id' => $result,
                        'redirect_url' => home_url()
                    );
                }
            }
            $resp = array(
                'success' => true,
                'msg' => '',
                'data' => $return
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
?>