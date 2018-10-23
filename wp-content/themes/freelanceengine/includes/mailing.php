<?php

/**
 * class Mailing control mail options
 */
Class Fre_Mailing extends AE_Mailing
{

    public static $instance;

    static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new Fre_Mailing();
        }

        return self::$instance;
    }
    function approved_payment_notification($order_id, $pack){
        $order = get_post($order_id);
        $user = get_userdata($order->post_author);
        $payment_method = get_post_meta($order_id, 'et_order_gateway', true );

        $subject = __('Your payment has been successfully processed!', ET_DOMAIN);
        $message = ae_get_option('approved_payment_mail_template');

        $message = str_replace('[package_name]', $pack->post_title, $message);
        $message = str_replace('[display_name]', $user->display_name, $message);
        $message = str_replace('[user_email]', $user->user_email, $message);
        $message = str_replace('[invoice_id]', $order_id, $message);
        $message = str_replace('[date]', date(get_option('date_format') , time()), $message);
        $message = str_replace('[payment]', $payment_method, $message);
        $message = str_replace('[total]', number_format($pack->et_price, 2), $message);
        $message = str_replace('[currency]', ae_currency_code(false), $message);
        $message = str_replace('[blogname]', get_bloginfo('name'), $message);

        $this->wp_mail($user->user_email, $subject, $message, array(
            'user_id' => $order->post_author,
        ));

    }
    /**
     * bid_mail Mail to author's project know have a freelance has bided on their project.
     * @param  [type] $new_status [description]
     * @param  [type] $old_status [description]
     */
    function new_payment_notification($order_id){
        $mail = ae_get_option('new_post_alert', '') ? ae_get_option('new_post_alert', '') : get_option('admin_email');
        $subject = __("A new payment notification.", ET_DOMAIN);
        $message = ae_get_option('new_payment_mail_template');

        $post = get_post($order_id);
        $author = get_user_by('id', $post->post_author);

        $product = current(get_post_meta($order_id, 'et_order_products', true));

        $message = str_replace('[package_name]', $product['NAME'], $message);
        $message = str_replace('[user_name]', $author->display_name, $message);
        $message = str_replace('[blogname]', get_bloginfo('blogname'), $message);

        $this->wp_mail( $mail, $subject, $message);
    }
    /**
     * Email to author's project
     */
    function bid_mail($bid_id) {

        $project_id = get_post_field('post_parent', $bid_id);
        $post_author = get_post_field('post_author', $project_id);
        $author = get_userdata($post_author);
        if ($author) {
            $message = ae_get_option('bid_mail_template');
            $bid_msg = get_post_field('post_content', $bid_id);
            $message = str_replace('[message]', $bid_msg, $message);
            $subject = sprintf(__("Your project posted on %s has a new bid.", ET_DOMAIN) , get_option('blogname'));

            return $this->wp_mail($author->user_email, $subject, $message, array(
                'post' => $project_id,
                'user_id' => $post_author
            ) , '');
        }
        return false;
    }
     /**
     * bid_cancel mail Mail to author's project know have a freelance has bided on their project.
     * @param  [type] $new_status [description]
     * @param  [type] $old_status [description]
     */
    function bid_cancel_mail($project_id) {

        $post_author = get_post_field('post_author', $project_id);
        $author = get_userdata($post_author);
        if ($author) {
            $message = ae_get_option('bid_cancel_mail_template');
            $subject = sprintf(__("There is a Freelancer canceled a bid on Your project %s.", ET_DOMAIN) , get_option('blogname'));

            return $this->wp_mail($author->user_email, $subject, $message, array(
                'post' => $project_id,
                'user_id' => $post_author
            ) , '');
        }
        return false;
    }

    /**
     * employer complete a job and send mail to freelancer joined project
     * @param integer $project_id The project id
     * @since 1.0
     * @author Dan
     */
    function review_freelancer_email($project_id) {
        $post = get_post($project_id);
        $message = ae_get_option('complete_mail_template');
        
        $link = esc_url( add_query_arg('review', '1', get_permalink($project_id) ) );
        $post_link = '<a href="' . $link. '" >' . $post->post_title . '</a>';
        $message = str_replace('[link_review]', $post_link, $message);

        $subject = __("Project you joined has a review.", ET_DOMAIN);
        $bid_id = get_post_meta($project_id, 'accepted', true);
        $freelancer_id = get_post_field('post_author', $bid_id);
        $author = get_userdata($freelancer_id);
        $this->wp_mail($author->user_email, $subject, $message, array(
            'post' => $project_id,
            'user_id' => $freelancer_id
        ) , '');
        return $author;
    }

    /**
     * employer complete a job and send mail to freelancer joined project
     * @param integer $project_id The project id
     * @since 1.0
     * @author Dan
     */
    function review_employer_email($project_id) {

        $message = ae_get_option('complete_mail_template');
        $message = str_replace('[review]', '', $message);

        $subject = __("Your posted project has a review.", ET_DOMAIN);

        // $bid_id = get_post_meta($project_id, 'accepted', true);
        $employer_id = get_post_field('post_author', $project_id);
        $author = get_userdata($employer_id);
        $this->wp_mail($author->user_email, $subject, $message, array(
            'post' => $project_id,
            'user_id' => $employer_id
        ) , '');
        return $author;
    }

    /**
     * invite a freelancer to work on current user project
     * @param int $user_id The user will be invite
     * @param int $project_id The project will be send
     * @since 1.0
     * @author Dakachi
     */
    function invite_mail($user_id, $project_id) {
        global $current_user, $user_ID;
        if ($user_id && $project_id) {

            // $user = new WP_User($user_id);
            // get user email
            $user_email = get_the_author_meta('user_email', $user_id);

            // mail subject
            $subject = sprintf(__("You have a new invitation to join project from %s.", ET_DOMAIN) , get_option('blogname'));

            // build list of project send to freelancer
            $project_info = '';
            foreach ($project_id as $key => $value) {
                // check invite this project or not
                if(fre_check_invited($user_id, $value)) continue;
                $project_link = get_permalink($value);
                $project_tile = get_the_title($value);
                // create a invite message
                fre_create_invite($user_id, $value);

                $project_info.= '<li><p>' . $project_tile . '</p><p>' . $project_link . '</p></li>';
            }

            if($project_info == '') return false;
            $project_info= '<ul>'.$project_info.'</ul>';

            // get mail template
            $message = '';
            if (ae_get_option('invite_mail_template')) {
                $message = ae_get_option('invite_mail_template');
            }

            // replace project list by placeholder
            $message = str_replace('[project_list]', $project_info, $message);

            // send mail
            return $this->wp_mail($user_email, $subject, $message, array(
                'user_id' => $user_id,
                'post' => $value
            ));
        }
    }

    /**
     * send email to freelancer if his/her bid is accepted by employer
     * use mail template bid_accepted_template
     * @param int $freelancer_id
     * @param int $project_id
     * @since 1.1
     * @author Dakachi
     */
    function bid_accepted($freelancer_id, $project_id) {
        $user_email = get_the_author_meta('user_email', $freelancer_id);

        // mail subject
        $subject = sprintf(__("Your bid on project %s has been accepted.", ET_DOMAIN) , get_the_title($project_id));

        // get mail template
        $message = '';
        if (ae_get_option('bid_accepted_template')) {
            $message = ae_get_option('bid_accepted_template');
        }

        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project_id));

        $workspace_link = '<a href="' . $workspace_link . '">' . $workspace_link . '</a>';
        $message = str_replace('[workspace]', $workspace_link, $message);

        return $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $freelancer_id,
            'post' => $project_id
        ));
    }

    /**
     * send email to employer when have new message
     * @param int $receiver the user will receive email
     * @param int $project the project id message send base on
     * @param string $message the message content
     * @since 1.2
     * @author Dakachi
     */
    function new_message($receiver, $project, $message) {
        $user_email = get_the_author_meta('user_email', $receiver);

        // mail subject
        $subject = sprintf(__("You have a new message on %s workspace.", ET_DOMAIN) , get_the_title($project));
        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project));

        $mail_template = ae_get_option('new_message_mail_template');

        // replace message content place holder
        $mail_template = str_replace('[message]', $message->comment_content, $mail_template);

        // replace workspace place holder
        $workspace_link = '<a href="' . $workspace_link . '">' . __("Workspace", ET_DOMAIN) . '</a>';
        $mail_template = str_replace('[workspace]', $workspace_link, $mail_template);

        // send mail
        return $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $receiver,
            'post' => $project
        ));
    }

    /**
     * send email to 3 user admin, employer, or freelancer when have a new report ignore current user
     * @param Int $project_id The project id was reported
     * @param Object $report The object contain report content
     * @since 1.3
     * @author Dakachi
     */
    function new_report($project_id, $report) {
        global $user_ID;
        $project = get_post($project_id);

        // email subject
        $subject = sprintf(__("Have a new report on project %s.", ET_DOMAIN) , get_the_title($project_id));

        if ($project->post_author == $user_ID) {

            // mail to freelancer when project owner send a report
            $mail_template = ae_get_option('employer_report_mail_template');
            $mail_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $mail_template);

            $bid_id = get_post_meta($project_id, 'accepted', true);
            $bid_author = get_post_field('post_author', $bid_id);
            $user_email = get_the_author_meta('user_email', $bid_author);
            $receiver = $bid_author;
        } else {

            // mail to employer when freelancer working on project send a new report
            $mail_template = ae_get_option('freelancer_report_mail_template');
            $mail_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $mail_template);

            $user_email = get_the_author_meta('user_email', $project->post_author);
            $receiver = $project->post_author;
        }

        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project_id));

        $workspace_link = '<a href="' . $workspace_link . '">' . __("Workspace", ET_DOMAIN) . '</a>';
        // replace workspace place holder
        $mail_template = str_replace('[workspace]', $workspace_link, $mail_template);

        // mail to admin
        $admin_template = ae_get_option('admin_report_mail_template');
        $admin_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $admin_template);

        // send mail to freelancer / employer
        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $receiver,
            'post' => $project_id
        ));

        // send mail to admin
        $this->wp_mail(get_option('admin_email') , $subject, $admin_template, array(
            'user_id' => 1,
            'post' => $project_id
        ));
    }

    /**
     * send email to freelancer, admin when employer request close project
     * @param snippet
     * @since 1.3
     * @author Dakachi
     */
    function close_project($project_id, $message) {
        global $user_ID;
        $project = get_post($project_id);

        // email subject
        $subject = sprintf(__("Project %s was closed.", ET_DOMAIN) , get_the_title($project_id));

        // mail to freelancer when project owner send a report
        $mail_template = ae_get_option('employer_close_mail_template');
        $mail_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $mail_template);

        $bid_id = get_post_meta($project_id, 'accepted', true);
        $bid_author = get_post_field('post_author', $bid_id);
        $user_email = get_the_author_meta('user_email', $bid_author);

        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project_id));

        $workspace_link = '<a href="' . $workspace_link . '">' . __("Workspace", ET_DOMAIN) . '</a>';
        // replace workspace place holder
        $mail_template = str_replace('[workspace]', $workspace_link, $mail_template);

        // send mail to freelancer / employer
        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $bid_author,
            'post' => $project_id
        ));

        // mail to admin
        $admin_template = ae_get_option('admin_report_mail_template');
        $admin_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $admin_template);

        // send mail to admin
        $this->wp_mail(get_option('admin_email') , $subject, $admin_template, array(
            'user_id' => 1,
            'post' => $project_id
        ));
    }

    /**
     * send email to employer, admin when freelancer request close project
     * @param snippet
     * @since 1.3
     * @author Dakachi
     */
    function quit_project($project_id, $message) {
        global $user_ID;
        $project = get_post($project_id);

        // email subject
        $subject = sprintf(__("User quit your project %s.", ET_DOMAIN) , get_the_title($project_id));

        // mail to employer when freelancer working on project send a new report
        $mail_template = ae_get_option('freelancer_quit_mail_template');
        $mail_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $mail_template);

        $user_email = get_the_author_meta('user_email', $project->post_author);

        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project_id));

        $workspace_link = '<a href="' . $workspace_link . '">' . __("Workspace", ET_DOMAIN) . '</a>';

        // replace workspace place holder
        $mail_template = str_replace('[workspace]', $workspace_link, $mail_template);

        // send mail to freelancer / employer
        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $project->post_author,
            'post' => $project_id
        ));

        // mail to admin
        $admin_template = ae_get_option('admin_report_mail_template');
        $admin_template = str_replace('[reporter]', get_the_author_meta('display_name', $user_ID) , $admin_template);

        // send mail to admin
        $this->wp_mail(get_option('admin_email') , $subject, $admin_template, array(
            'user_id' => 1,
            'post' => $project_id
        ));
    }

    /**
     * send mail to employer, freelancer when admin decide dispute process
     * @param
     * @since 1.3
     * @author Dakachi
     */
    function refund($project_id, $bid_accepted) {
        $project_owner = get_post_field('post_author', $project_id);
        $bid_owner = get_post_field('post_author', $bid_accepted);

        $mail_template = ae_get_option('fre_refund_mail_template');
        if (!$mail_template) return;

        // mail to project owner
        $user_email = get_the_author_meta('user_email', $project_owner);
        $subject = sprintf(__("You have got a refund on project %s.", ET_DOMAIN) , get_the_title($project_id));

        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project_id));

        $workspace_link = '<a href="' . $workspace_link . '">' . __("Workspace", ET_DOMAIN) . '</a>';
        // replace workspace place holder
        $mail_template = str_replace('[workspace]', $workspace_link, $mail_template);

        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $project_owner,
            'post' => $project_id
        ));

        // mail to freelancer
        $user_email = get_the_author_meta('user_email', $bid_owner);
        $subject = sprintf(__("Project %s you worked on has refunded.", ET_DOMAIN) , get_the_title($project_id));
        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $bid_owner,
            'post' => $project_id
        ));
    }

    /**
     * send mail to employer, freelancer when admin execute payment
     * @param
     * @since 1.3
     * @author Dakachi
     */
    function execute($project_id, $bid_accepted) {
        $project_owner = get_post_field('post_author', $project_id);
        $bid_owner = get_post_field('post_author', $bid_accepted);

        $mail_template = ae_get_option('fre_execute_mail_template');
        if (!$mail_template) return;

        // mail to project owner
        $user_email = get_the_author_meta('user_email', $project_owner);
        $subject = sprintf(__("Your presend payment on project %s has been transfer.", ET_DOMAIN) , get_the_title($project_id));

        $workspace_link = add_query_arg(array(
            'workspace' => 1
        ) , get_permalink($project_id));

        $workspace_link = '<a href="' . $workspace_link . '">' . __("Workspace", ET_DOMAIN) . '</a>';
        // replace workspace place holder
        $mail_template = str_replace('[workspace]', $workspace_link, $mail_template);

        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $project_owner,
            'post' => $project_id
        ));

        // mail to freelancer
        $user_email = get_the_author_meta('user_email', $bid_owner);
        $subject = sprintf(__("You have been sent payment base on project %s.", ET_DOMAIN) , get_the_title($project_id));
        $this->wp_mail($user_email, $subject, $mail_template, array(
            'user_id' => $bid_owner,
            'post' => $project_id
        ));
    }

    /**
     * mail alert admin when project complete and transfer money to freelancer
     * @param int $project_id The project was completed
     * @param int $bid_accepted The bid accepted on project
     * @since 1.3
     * @author Dakachi
     */
    function alert_transfer_money($project_id, $bid_accepted) {
        if (!ae_get_option('manual_transfer')) {

            // mail to admin
            $subject = sprintf(__("Project %s has been completed and money has transfered.", ET_DOMAIN) , get_the_title($project_id));
            $admin_template = sprintf(__("Project %s has been completed and money has transfered. You can check workspace and project details", ET_DOMAIN) , get_the_title($project_id));
        } else {
            $subject = sprintf(__("Project %s has been completed and waiting your confirm.", ET_DOMAIN) , get_the_title($project_id));
            $admin_template = sprintf(__("Project %s has been completed. Please check it and transfer money to freelancer.", ET_DOMAIN) , get_the_title($project_id));
        }

        $admin_template.= '<a rel="nofollow" href="'.get_permalink($project_id).'">'.get_permalink($project_id).'</a>';

        // send mail to admin
        $this->wp_mail(get_option('admin_email') , $subject, $admin_template, array(
            'user_id' => 1,
            'post' => $project_id
        ));
    }

    /**
     * Notifications of new projects for Freelancers in selected categories
     * @param object $project_id
     * @param array $categories
     * @since 1.0
     * @author ThanhTu
     */
    function new_project_of_category($project){
        global $ae_post_factory, $post;

        $post_object = $ae_post_factory->get( PROFILE );
        // Get term_id parent if that term have parent
        $project_categories = $project->project_category;
        foreach ($project_categories as $key => $value) {
            $term = get_term($value, 'project_category' );
            if($term->parent && !in_array($term->parent, $project_categories)){
                array_push($project_categories, $term->parent);
            }
        }

        $args = array(
            'post_type'     => PROFILE,
            'post_status'   => 'publish',
            'tax_query'     => array(
                array(
                    'taxonomy'  => 'project_category',
                    'field'     => 'term_id',
                    'terms'     => $project_categories,
                    'operator' => 'IN'
                )
            ),
            'meta_query' => array(
                array(
                    'key'      => 'et_receive_mail',
                    'value'    => 1,
                    'compare'  => "="
                )
            )
        );

        $query = new WP_Query( $args );
        $postdata = array();
        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $convert    = $post_object->convert($post);
                $display_name = get_the_author_meta('display_name', $convert->post_author);
                $user_email = get_the_author_meta('user_email', $convert->post_author);
                if(!empty($display_name) && !empty($user_email)){
                    $postdata[] = "{$display_name} <{$user_email}>";
                }
            }
        }

        $subject    = __('New Project For You Today!', ET_DOMAIN);
        $headers[]  = 'Content-Type: text/html; charset=UTF-8';
        $headers[]  = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>'. "\r\n";
        $headers[]  = 'Bcc: '.implode(',', $postdata).'\r\n';

        $template_default = "<p>Hi there,</p><p>There is a new job for you today. Hurry apply for this project [project_link] and get everything started.</p><p>Hope you have a highly effective Day</p>";
        $mail_template = ae_get_option('new_project_mail_template', $template_default);
        $link = "<a rel='nofollow' target='_Blank' href='".$project->permalink."'>".$project->post_title."</a>";
        $mail_template = str_replace('[project_link]', $link, $mail_template);

        // send mail
        $mail = $this->wp_mail(get_option('admin_email') , $subject, $mail_template, array(), $headers);

    }
}
if (!function_exists('send_receipt_mail')) {
    /**
     * filter template ae_receipt_mail in core
     * @param $content
     * @param $user_id
     * @param $order
     * @author ThanhTu
     */
    function send_receipt_mail($content, $order){
        // Get info Order
        $product = current($order['products']);
        $type = $product['TYPE'];
        $packs = AE_Package::get_instance();
        $sku = $order['payment_package'];
        $pack = $packs->get_pack($sku, $type);

        if($type == 'bid_plan'){
            $content = ae_get_option('ae_receipt_bid_mail');
        }else{
            $content = ae_get_option('ae_receipt_project_mail');
        }
        $post_parent = get_post_field('post_parent' ,$order['ID']);
        
        $ad_url = '<a href="' . get_permalink($post_parent) . '">' . get_the_title($post_parent) . '</a>';
        $content = str_ireplace('[link]', $ad_url, $content);

        if($order['payment'] == 'cash'){
            if($type == 'bid_plan'){
                $content = str_ireplace('[notify_cash]', __('Please send the payment to admin to complete your payment.', ET_DOMAIN), $content);
            }else{
                $content = str_ireplace('[notify_cash]', __('Please send the payment to admin to complete your payment.<br>Your project post is under admin review. It will be active right after admin approval.', ET_DOMAIN), $content);
            }
        }else{
            $content = str_ireplace('[notify_cash]', '', $content);
        }

        $content = str_ireplace( '[package_name]', $product['NAME'], $content);
        $content = str_ireplace( array('[number_of_bids]','[number_of_posts]'), $pack->et_number_posts, $content);
        
        return $content;
    }
    add_filter('ae_send_receipt_mail', 'send_receipt_mail', 10, 3 );
}