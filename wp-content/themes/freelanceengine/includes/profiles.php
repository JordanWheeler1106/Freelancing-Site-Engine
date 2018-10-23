<?php
/**
* Registers a new post type profile
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function fre_register_profile() {

	$labels = array(
		'name'                => __( 'Profiles', ET_DOMAIN ),
		'singular_name'       => __( 'Profile', ET_DOMAIN ),
		'add_new'             => _x( 'Add New profile', ET_DOMAIN, ET_DOMAIN ),
		'add_new_item'        => __( 'Add New profile', ET_DOMAIN ),
		'edit_item'           => __( 'Edit profile', ET_DOMAIN ),
		'new_item'            => __( 'New profile', ET_DOMAIN ),
		'view_item'           => __( 'View profile', ET_DOMAIN ),
		'search_items'        => __( 'Search Profiles', ET_DOMAIN ),
		'not_found'           => __( 'No Profiles found', ET_DOMAIN ),
		'not_found_in_trash'  => __( 'No Profiles found in Trash', ET_DOMAIN ),
		'parent_item_colon'   => __( 'Parent profile:', ET_DOMAIN ),
		'menu_name'           => __( 'Profiles', ET_DOMAIN ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,

		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => ae_get_option('fre_profile_archive', 'profiles'),
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true, //array('slug' => ae_get_option('fre_profile_slug', '')),
		'capability_type'     => 'post',
		'supports'            => array(
								'title', 'editor', 'author', 'thumbnail',
								'excerpt','custom-fields', 'trackbacks', 'comments',
								'revisions', 'page-attributes', 'post-formats'
								)
	);

	register_post_type( PROFILE, $args );

    $labels = array(
        'name'                  => _x('Countries', 'Taxonomy plural name', ET_DOMAIN) ,
        'singular_name'         => _x('Country', 'Taxonomy singular name', ET_DOMAIN) ,
        'search_items'          => __('Search countries', ET_DOMAIN) ,
        'popular_items'         => __('Popular countries', ET_DOMAIN) ,
        'all_items'             => __('All countries', ET_DOMAIN) ,
        'parent_item'           => __('Parent country', ET_DOMAIN) ,
        'parent_item_colon'     => __('Parent country', ET_DOMAIN) ,
        'edit_item'             => __('Edit country', ET_DOMAIN) ,
        'update_item'           => __('Update country ', ET_DOMAIN) ,
        'add_new_item'          => __('Add New country ', ET_DOMAIN) ,
        'new_item_name'         => __('New country Name', ET_DOMAIN) ,
        'add_or_remove_items'   => __('Add or remove country', ET_DOMAIN) ,
        'choose_from_most_used' => __('Choose from most used enginetheme', ET_DOMAIN) ,
        'menu_name'             => __('Countries', ET_DOMAIN) ,
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'hierarchical'      => false,
        'show_tagcloud'     => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug'              =>  ae_get_option('country_slug', 'country')
        ),
        'query_var'         => true,
        'capabilities'      => array(
            'manage_terms',
            'edit_terms',
            'delete_terms',
            'assign_terms'
        )
    );
    register_taxonomy('country', array(PROFILE) , $args);

    global $ae_post_factory;
    $ae_post_factory->set(PROFILE, new AE_Posts(PROFILE, array('project_category', 'skill', 'country') , array(
        'et_professional_title',
        'rating_score',
        'hour_rate',
        'et_experience',
        'et_receive_mail',
        'currency'
    )));

}
add_action( 'init', 'fre_register_profile' );
    /**
* Registers a new post type portfolio
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function fre_register_portfolio() {

    $labels = array(
        'name'                => __( 'Portfolios', ET_DOMAIN ),
        'singular_name'       => __( 'Portfolio', ET_DOMAIN ),
        'add_new'             => _x( 'Add New portfolio', ET_DOMAIN, ET_DOMAIN ),
        'add_new_item'        => __( 'Add New portfolio', ET_DOMAIN ),
        'edit_item'           => __( 'Edit portfolio', ET_DOMAIN ),
        'new_item'            => __( 'New portfolio', ET_DOMAIN ),
        'view_item'           => __( 'View portfolio', ET_DOMAIN ),
        'search_items'        => __( 'Search portfolio', ET_DOMAIN ),
        'not_found'           => __( 'No portfolio found', ET_DOMAIN ),
        'not_found_in_trash'  => __( 'No portfolios found in Trash', ET_DOMAIN ),
        'parent_item_colon'   => __( 'Parent portfolio:', ET_DOMAIN ),
        'menu_name'           => __( 'Portfolios', ET_DOMAIN ),
    );

    $args = array(
        'labels'              => $labels,
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 6,

        'show_in_nav_menus'   => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'has_archive'         => ae_get_option('fre_portfolio_archive', 'portfolios'),
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => array('slug' => ae_get_option('fre_portfolio', 'portfolio')),
        'capability_type'     => 'post',
        'supports'            => array(
                                'title', 'editor', 'author', 'thumbnail',
                                'excerpt','custom-fields', 'trackbacks', 'comments',
                                'revisions', 'page-attributes', 'post-formats'
                                )
    );
    global $ae_post_factory;
    $ae_post_factory->set(PORTFOLIO, new AE_Posts( PORTFOLIO, array('skill'), array('portfolio_link') ) );
    register_post_type( PORTFOLIO, $args );
}
add_action('init', 'fre_register_portfolio' );

    /**
     * Create a taxonomy
     *
     * @uses  Inserts new taxonomy object into the list
     * @uses  Adds query vars
     *
     * @param string  Name of taxonomy object
     * @param array|string  Name of the object type for the taxonomy object.
     * @param array|string  Taxonomy arguments
     * @return null|WP_Error WP_Error if errors, otherwise null.
     */
function fre_register_tax_skill(){
    // $isHierarchical = false;
    // $switch_skill = ae_get_option('switch_skill');
    // if($switch_skill && !is_admin()){
    //     $isHierarchical = true;
    // }

    global $pagenow;
    $isHierarchical = true;
    $switch_skill = ae_get_option('switch_skill');
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    if( $pagenow == 'edit-tags.php' || $action == 'ae-project-sync' ) {
        $isHierarchical = false;
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';

        if( $switch_skill && in_array( $method,array('create','update')) ){
            /**
             * isHierarchical = false --> skill don't save.
             * danng
             */
            $isHierarchical = true;
        }
    } elseif (!$switch_skill){
        $isHierarchical = false;
    }

    $labels = array(
        'name'                  => _x('Skills', 'Taxonomy plural name', ET_DOMAIN) ,
        'singular_name'         => _x('Skill', 'Taxonomy singular name', ET_DOMAIN) ,
        'search_items'          => __('Search Skills', ET_DOMAIN) ,
        'popular_items'         => __('Popular Skills', ET_DOMAIN) ,
        'all_items'             => __('All Skills', ET_DOMAIN) ,
        'parent_item'           => __('Parent Skill', ET_DOMAIN) ,
        'parent_item_colon'     => __('Parent Skill', ET_DOMAIN) ,
        'edit_item'             => __('Edit Skill', ET_DOMAIN) ,
        'update_item'           => __('Update Skill ', ET_DOMAIN) ,
        'add_new_item'          => __('Add New Skill ', ET_DOMAIN) ,
        'new_item_name'         => __('New Skill Name', ET_DOMAIN) ,
        'add_or_remove_items'   => __('Add or remove skill', ET_DOMAIN) ,
        'choose_from_most_used' => __('Choose from most used enginetheme', ET_DOMAIN) ,
        'menu_name'             => __('Skills', ET_DOMAIN) ,
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'hierarchical'      => $isHierarchical,
        'show_tagcloud'     => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug'              =>  ae_get_option('skill_slug', 'skill'),
            'hierarchical'      => false
        ),
        'query_var'         => true,
        'capabilities'      => array(
            'manage_terms',
            'edit_terms',
            'delete_terms',
            'assign_terms'
        )
    );
    register_taxonomy('skill', array(PROFILE, PORTFOLIO, PROJECT) , $args);
}
add_action('init', 'fre_register_tax_skill' );
function fre_update_profile_available($result, $user_data){
    if( ae_user_role( $result ) == FREELANCER){
        $user_available = get_user_meta($result, 'user_available', true);
        $profile_id = get_user_meta( $result, 'user_profile_id', true );
        if( $profile_id ){
            update_post_meta($profile_id, 'user_available', $user_available);
        }
    }
}
add_action('ae_update_user', 'fre_update_profile_available', 10, 2);
class Fre_ProfileAction extends AE_PostAction {
	function __construct($post_type = 'fre_profile') {
		$this->post_type = PROFILE;
        // add action fetch profile
		$this->add_ajax('ae-fetch-profiles', 'fetch_post');
        /**
         * sync profile
         # update , insert ...
         * @param Array $request
         * @since v1.0
         */
        $this->add_ajax('ae-profile-sync', 'sync_post');
        $this->add_action('pre_get_posts','pre_get_profile');
        /**
         * hook convert a profile to add custom meta data
         * @param Object $result profile object
         * @since v1.0
         */
        $this->add_filter('ae_convert_fre_profile', 'ae_convert_profile');
        // hook to groupy by, group profile by author
        $this->add_filter( 'posts_groupby', 'posts_groupby', 10, 2 );

        // filter post where to check user professional title
        $this->add_filter('posts_search', 'fre_posts_search', 10, 2);
        // add filter posts join to join post meta and get et professional title
        $this->add_filter('posts_join', 'fre_join_post', 10, 2);
        // Delete profile after admin delete user
        $this->add_action('remove_user_from_blog', 'fre_delete_profile_after_delete_user');
	}
    /**
     * convert  profile
     * @package FreelanceEngine
     */
    function ae_convert_profile($result){
        $result->et_avatar = get_avatar($result->post_author,70 );
        $result->author_link = get_author_posts_url( $result->post_author );
        if($result->et_experience) {
            if($result->et_experience > 1 ) {
                $result->experience = sprintf(__("%d years", ET_DOMAIN), $result->et_experience);
            }else{
                $result->experience = sprintf(__("%d year", ET_DOMAIN), $result->et_experience);
            }
        }else {
            $result->experience = get_post_meta($result->ID, 'experience', true);
        }
        // override profile ling
        $result->permalink = $result->author_link;
        $result->author_name = get_the_author_meta( 'display_name', $result->post_author );
        $result->hourly_rate_price = sprintf(__("%s/h", ET_DOMAIN), fre_price_format($result->hour_rate));

        $rating = Fre_Review::freelancer_rating_score($result->post_author);
        $result->rating_score = $rating['rating_score'];

        ob_start();
        $i = 1;

        if($result->tax_input['skill']){
            $total_skill = count($result->tax_input['skill']);
            $string_length = 0;
            foreach ($result->tax_input['skill'] as $tax){
                $string_length += strlen($tax->name);
        ?>
                <li><span class="skill-name-profile"><?php echo $tax->name;?></span></li>
        <?php
                if($string_length > 20 ) break;
                if($i >= 4) break;
                $i++;
            }
            if($i < $total_skill) {
                echo '<li><span class="skill-name-profile">+'.($total_skill-$i).'</span></li>'  ;
            }
        }
        $skill_list = ob_get_clean();

        // skill dont need id array
        unset($result->skill);
        // generate skill list
        $result->skill_list = $skill_list;
        $result->user_available = get_user_meta($result->post_author, 'user_available', true);
        // update_post_meta($result->ID, 'user_available', $result->user_available);
        return $result;
    }

    /**
     * group profile by user id if user can not edit other profils
     * @param string $groupby
     * @param object $groupby Wp_Query object
     * @since 1.0
     * @author Dakachi
     */
    function posts_groupby($groupby, $query) {
        global $wpdb;

        // if( is_admin() && current_user_can('edit_others_posts') ) {
        //     return $groupby;
        // }
        $query_vars = (isset($query->query_vars['post_type'])) ? $query->query_vars : '';
        if(isset($query_vars['post_type']) && $query_vars['post_type'] == $this->post_type) {
            $groupby = "{$wpdb->posts}.post_author";
        }
        return $groupby;
    }
    /**
     * add post where when user search, check professional title
     * @param String $where SQL where string
     * @since 1.4
     * @author Dakachi
     */
    function fre_posts_search($post_search , $query) {
        global $wpdb;
        if(isset($_REQUEST['query']['s']) && $_REQUEST['query']['s']!='' && $query->query_vars['post_type'] == PROFILE) {

            $post_search = substr($post_search, 0, -2);

            $search = $_REQUEST['query']['s'];
            $q = array();
            $q['s'] = $search;
            // there are no line breaks in <input /> fields
            $search = str_replace( array( "\r", "\n" ), '', esc_sql($search) );
            $q['search_terms_count'] = 1;
            if ( ! empty( $q['sentence'] ) ) {
                $q['search_terms'] = array( $q['s'] );
            } else {
                if ( preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $q['s'], $matches ) ) {
                    $q['search_terms_count'] = count( $matches[0] );
                    $q['search_terms'] = $matches[0];
                    // if the search string has only short terms or stopwords, or is 10+ terms long, match it as sentence
                    if ( empty( $q['search_terms'] ) || count( $q['search_terms'] ) > 9 )
                    $q['search_terms'] = array( $q['s'] );
                } else {
                    $q['search_terms'] = array( $q['s'] );
                }
            }

            foreach ( $q['search_terms'] as $term ) {
                $post_search .= " OR prof_title.meta_value LIKE '%".$term."%'";
            }

            $post_search .= ") ";

            // $where .= " OR prof_title.meta_value LIKE '%".$_REQUEST['query']['s']."%'";
        }
        // wp_send_json( $post_search );
        return $post_search;
    }

    /**
     * join postmeta table to get et_professional_title
     * @param String $join SQL join string
     * @since 1.4
     * @author Dakachi
     */
    function fre_join_post($join, $query){
        global $wpdb;
        if(isset($_REQUEST['query']['s']) && $_REQUEST['query']['s']!='' && $query->query_vars['post_type'] == PROFILE) {
            $join .= " INNER JOIN $wpdb->postmeta as prof_title ON ID = prof_title.post_id AND prof_title.meta_key='et_professional_title' ";
        }
        return $join;
    }

    /**
     * filter query args before query
     * @package FreelanceEngine
     */
    public function filter_query_args($query_args){

        if(isset($_REQUEST['query'])) {
            $query = $_REQUEST['query'];
            $query_args= wp_parse_args($query_args, $query);
            // query profile base on skill
            if(isset($query['skill']) && $query['skill'] != '') {
                //$query_args['skill_slug__and'] = $query['skill'];
                $query_args['tax_query'] = array(
                        'skill' => array(
                                'taxonomy' => 'skill',
                                'terms'    => $query['skill'],
                                'field'    => 'slug'
                          ));
                unset($query_args['skill']);
            }
            // list featured profile
            if(isset($query['meta_key'])) {
                $query_args['meta_key'] =  $query['meta_key'];
                if(isset($query['meta_value'])) {
                    $query_args['meta_value'] =  $query['meta_value'];
                }
            }

            // add hour rate filter to query
            if( isset($query['hour_rate']) && !empty( $query['hour_rate'] ) ){
                $hour_rate = $query['hour_rate'];
                $hour_rate = explode(",",$hour_rate);
                $query_args['meta_query'][] =  array(   'key' =>'hour_rate',
                                                        'value' => array((int)$hour_rate[0],
                                                        (int)$hour_rate[1]),
                                                        'type'    => 'numeric',
                                                        'compare' => 'BETWEEN'
                                                );
            } else {
                $query_args['meta_query'][] =  array(   'key' =>'hour_rate',
                                                        'value' => array(0,(int)ae_get_option('fre_slide_max_budget_freelancer', 2000)),
                                                        'type'    => 'numeric',
                                                        'compare' => 'BETWEEN'
                                                );
            }
            if( !current_user_can('manage_options') ) {
                $query_args['meta_query'][] =  array(   'key' =>'user_available',
                                                        'value' => 'on',
                                                        'compare' => '='
                                                );
            }

            if(isset($query['country']) && $query['country'] != '') {
                $query_args['country'] = $query['country'];
            }

            if(isset($query['project_category']) && $query['project_category'] != '') {
                $query_args['project_category'] = $query['project_category'];
                // $query_args['tax_query']['project_category'] =  array( 'taxonomy' => 'project_category', 'terms' => $query['project_category'], 'field' =>;
                // unset($query_args['project_category']);
            }
        }
        return apply_filters( 'fre_profile_query_args', $query_args, $query );
    }

    /**
     * filter pre get profile
     * @param $query
     * @package FreelanceEngine
     * @return
     */
    function pre_get_profile($query)   {

        if( is_post_type_archive('fre_profile') ) {

            $query_profile  = $query->query;
            $post_type      = isset( $query_profile['post_type'] ) ? $query_profile['post_type'] :'';

            if( $post_type == PROFILE ) {

                if( is_admin() ) {
                    /*
                     * @since version 1.7.9
                     * Fix dashboard profiles menu tabs(list profiles is not enough).
                    */
                    return $query;
                }

                $query->query_vars['meta_query'] = '';
                if( isset( $_REQUEST['query']['hour_rate'] ) && !empty( $_REQUEST['query']['hour_rate'] ) ) {

                    $hour_rate = $_REQUEST['query']['hour_rate'];
                    $hour_rate = explode(",",$hour_rate);
                    $query->query_vars['meta_query'][] =  array( 'key'  =>'hour_rate',
                                                            'value'     => array((int)$hour_rate[0], (int) $hour_rate[1]),
                                                            'type'      => 'numeric',
                                                            'compare'   => 'BETWEEN'
                                                    );
                } else {
                    // Query Hour_rate default
                    $query->query_vars['meta_query'][] =  array( 'key'  =>'hour_rate',
                                                            'value'     => array(0,(int)ae_get_option('fre_slide_max_budget_freelancer', 2000)),
                                                            'type'      => 'numeric',
                                                            'compare'   => 'BETWEEN'
                                                    );
                }

                if( ! current_user_can( 'manage_options' ) ) {
                    /*
                     * fre/emp/visitor only see profile is available for hire.
                     */

                    $query->query_vars['meta_query'][] = array( 'key'   =>'user_available',
                                                            'value'     => 'on',
                                                            'compare'   => '='
                                                    );
                }
            }

        }
        // Search default
        if ( $query->is_search() && is_search() && !is_admin()) {
            $query->set( 'post_type', array('post','page') );
        } // end if

        return $query;
    }
    /**
     * hanlde profile action
     * @package FreelanceEngine
     */
    function sync_post() {
        global $ae_post_factory, $user_ID, $current_user;
        $request   = $_REQUEST;
        $ae_users  = new AE_Users();
        $user_data = $ae_users->convert($current_user);
        $profile   = $ae_post_factory->get($this->post_type);

        if(!AE_Users::is_activate($user_ID)) {
            wp_send_json( array(
                'success' => false,
                'msg'     => __("Your account is pending. You have to activate your account to create profile.", ET_DOMAIN)
                )
            );
        };

        // set status for profile
        if( !isset($request['post_status']) ){
            $request['post_status'] = 'publish';
        }
        // set profile title
        $request['post_title'] = $user_data->display_name;

        if($request['method'] == 'create') {
            $profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
            if($profile_id) {
                $profile_post = get_post($profile_id);
                if($profile_post && $profile_post->post_status != 'draft') {
                    wp_send_json( array(
                        'success' => false,
                        'msg'     => __("You only can have on profile.", ET_DOMAIN)
                        )
                    );
                }
            }
        } 
        // sync profile
        $result = $profile->sync($request);

        if (!is_wp_error($result)) {
            $result->redirect_url = $result->permalink;
            $rating_score = get_post_meta( $result->ID, 'rating_score', true );
            if(!$rating_score) {
                update_post_meta( $result->ID, 'rating_score', 0);
            }
            $user_available = get_user_meta($user_ID, 'user_available', true);
            update_post_meta($result->ID, 'user_available', $user_available);
            // action create profile
            if ($request['method'] == 'create') {
                // store profile id to user meta
                $response = array(
                    'success' => true,
                    'data' => $result,
                    'msg' => __("Your profile has been created successfully.", ET_DOMAIN)
                );
                wp_send_json($response);
                //action update profile
            } else if($request['method'] == 'update'){

                $response = array(
                    'success' => true,
                    'data' => $result,
                    'msg' => __("Your profile has been updated successfully.", ET_DOMAIN)
                );
                wp_send_json($response);
            }

        } else {

            wp_send_json(array(
                'success' => false,
                'data'    => $result,
                'msg'     => $result->get_error_message()
            ));
        }
    }
    /**
     * Delete profile after delete user
     * @param integer $user_id the id of user to delete
     * @return void
     * @since 1.7
     * @package freelanceengine
     * @category PROFILE
     * @author Tambh
     */
    function fre_delete_profile_after_delete_user( $user_id ){
        if( current_user_can('manage_options') ){
            $profile_ids = $this->fre_get_profile_id( array( 'post_author' => $user_id ) );
            foreach ($profile_ids as $key => $value) {
                wp_trash_post($value);
            }
        }
    }
    /**
     * Get profile id
     * @param array $args parameter of profile
     * @return array $id of profile
     * @since 1.7
     * @package freelanceengine
     * @category
     * @author Tambh
     */
    public function fre_get_profile_id( $args = array() ){
        global $user_ID;
        $default = array(
            'post_type'=> PROFILE,
            'show_posts' => -1,
            'post_status' => array('publish', 'pending')
            );
        $args = wp_parse_args( $args, $default );
        query_posts($args);
        $post_ids = array();
        if (have_posts() ){
            while ( have_posts() ) {
                the_post();
                array_push( $post_ids, get_the_ID() );
            }
        }
        wp_reset_query();
        return $post_ids;
    }
}



class Fre_PortfolioAction extends AE_PostAction {
    function __construct($post_type = 'portfolio') {
        $this->post_type = PORTFOLIO;
        $this->add_ajax('ae-fetch-portfolios', 'fetch_post');
        $this->add_ajax('ae-portfolio-sync', 'sync_post');
        $this->add_filter('ae_convert_portfolio', 'ae_convert_portfolio');
    }
    /**
     * filter query args before query
     * @package FreelanceEngine
     */
    public function filter_query_args($query_args){
        if(isset($_REQUEST['query'])) {
            $query = $_REQUEST['query'];
            if(isset($query['skill']) && $query['skill'] != '') {
                $query_args['skill'] = $query['skill'];
            }
        }

        return $query_args;
    }
    function ae_convert_portfolio($result){

        $thumbnail_full_src              = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'full' );
        $thumbnail_src                   = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'portfolio' );
        $result->the_post_thumbnail_full = $thumbnail_full_src[0];
        $result->the_post_thumbnail      = $thumbnail_src[0];

        return $result;
    }
    /**
     * hanlde portfolio action
     * @package FreelanceEngine
     */
    function sync_post() {
        global $ae_post_factory, $user_ID, $current_user,$post;
        // echo 1; exit;
        $request   = $_REQUEST;
        $ae_users  = new AE_Users();
        $user_data = $ae_users->convert($current_user);
        $portfolio = $ae_post_factory->get($this->post_type);

        // set status for profile
        if( !isset($request['post_status']) ){
            $request['post_status'] = 'publish';
        }
        // set default post content
        $request['post_content'] = '';

        // sync place
        $result = $portfolio->sync($request);

        if (!is_wp_error($result)) {
            //update post thumbnail
            if( isset($request['post_thumbnail']) ){
                $thumb_id = $request['post_thumbnail'];
                set_post_thumbnail( $result, $thumb_id );
            }

            // action create profile
            if ($request['method'] == 'create') {
                $convert = $portfolio->convert($result);

                if( ae_get_option('switch_skill', false ) )
                {
                    $term = get_term_by( 'slug', $request['skill'], 'skill', ARRAY_A);
                    wp_set_post_terms($result->ID, $term, 'skill', true);
                }

                $response = array(
                    'success'      => true,
                    'data'         => $convert,
                    'msg'          => __("Portfolio has been created successfully.", ET_DOMAIN)
                );
                wp_send_json($response);
            } else if($request['method'] == 'delete' || $request['method'] == 'remove' ){
                $response = array(
                    'success'      => true,
                    'msg'          => __("Portfolio has been deleted successfully.", ET_DOMAIN)
                );
                wp_send_json($response);
                //action update profile
            } else if($request['method'] == 'update'){

                $response = array(
                    'success'      => true,
                    'data'         => array(
                        'redirect_url' => $result->permalink
                    ) ,
                    'msg'          => __("Portfolio has been updated successfully.", ET_DOMAIN)
                );
                wp_send_json($response);
            }

        } else {
            wp_send_json(array(
                'success' => false,
                'data'    => $result,
                'msg'     => $result->get_error_message()
            ));
        }
    }
}

/**
 * Send Email Confirm
 * @param $user_id
 * @param $user_data
 * @author ThanhTu
 * @since 1.0
 */
function fre_update_new_email($user_id, $user_data){
    global $user_ID, $current_user;

    if(!isset($_REQUEST['do'])) return;
    if ($user_ID == $user_data['ID'] && $user_data['user_email'] == $current_user->user_email) return;

    $hash = md5($user_data['user_email']. time(). mt_rand());
    $new_email = array(
        'hash' => $hash,
        'newemail' => $user_data['user_email']
    );

    update_user_meta($user_data['ID'] ,'adminhash', $new_email );
    $new_details = get_option( 'adminhash' );

    // subject: Email Change Confirmation
    $email_text = __( 'Hi ###USERNAME###,
    Your administration email has just been changed on ###SITENAME###.
    If it is correct, please click on the following link to confirm your change:
    ###LINK###

    Otherwise, you are free to ignore this email.

    Regards,
    All at ###SITENAME###
    ###SITEURL###' );

    $result = wp_update_user(array('ID' => $user_data['ID'],
                'user_email' => $current_user->user_email
            ));

    $userdata = get_userdata($result);

    $content = str_replace('###USERNAME###', $current_user->user_login, $email_text);
    $content = str_replace('###SITENAME###', get_site_option( 'site_name' ), $content);
    $content = str_replace('###LINK###', esc_url( et_get_page_link("profile"). '?adminhash='.$hash ), $content);
    $content = str_replace('###SITEURL###', network_home_url(), $content);

    wp_mail($user_data['user_email'],
        sprintf( __( '[%s]Email Change Confirmation', ET_DOMAIN), wp_specialchars_decode( get_option( 'blogname' ) )),
        $content
    );

}
add_action('ae_update_user', 'fre_update_new_email', 10, 2);


if( !class_exists('fre_notice_user_new')){
    function fre_notice_user_new() {
        global $pagenow;
        if($pagenow == 'user-new.php'){
            if(isset($_GET['update']) && ($_GET['update'] == 'addnoconfirmation' || $_GET['update'] == 'add' || $_GET['update'] == 'newuserconfirmation' )){

                echo '<div class="notice-warning notice ">';
                    echo '<p>';
                         _e( 'Please complete your profile information and enable "Available for hire" function at page Profile!', ET_DOMAIN );
                    echo '</p>';
                echo '</div>';
            }
        }
    }
    add_action( 'admin_notices', 'fre_notice_user_new' );
}