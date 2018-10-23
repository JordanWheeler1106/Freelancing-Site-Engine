<?php
/**
 * Page Edit Project
*/
global $user_ID;
get_header();
$post = '';
$current_skills = '';
if(isset($_REQUEST['id'])) {
    $post = get_post($_REQUEST['id']);
    if($post) {
        global $ae_post_factory;
        $post_object = $ae_post_factory->get($post->post_type);
        $post_convert = $post_object->convert($post);
        echo '<script type="data/json"  id="edit_postdata">'. json_encode($post_convert) .'</script>';
    }
    //get skills
    $current_skills = get_the_terms( $_REQUEST['id'], 'skill' );
}

?>
<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php _e('Edit the project', ET_DOMAIN);?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <div class="container" id="edit_project">
            <div id="fre-post-project-2 step-post" class="fre-post-project-step step-wrapper step-post active">
                <div class="fre-post-project-box">
                    <form class="post" role="form" class="validateNumVal">
                        <div class="step-post-project" id="fre-post-project">
                            <h2><?php _e('Your Project Details', ET_DOMAIN);?></h2>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="project_category"><?php _e('What category does your project work in?', ET_DOMAIN);?></label>
                                <?php 
                                    $cate_arr = array();
                                    if(!empty($post_convert->tax_input['project_category'])){
                                        foreach ($post_convert->tax_input['project_category'] as $key => $value) {
                                            $cate_arr[] = $value->term_id;
                                        };
                                    }
                                    ae_tax_dropdown( 'project_category' ,
                                      array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s categories", ET_DOMAIN), ae_get_option('max_cat', 5)).'"',
                                              'class' => 'fre-chosen-multi',
                                              'hide_empty' => false,
                                              'hierarchical' => true ,
                                              'id' => 'project_category' ,
                                              'show_option_all' => false,
                                              'selected'        => $cate_arr,
                                          )
                                    );
                                ?>
                            </div>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="fre-project-title"><?php _e('Your project title', ET_DOMAIN);?></label>
                                <input class="input-item text-field" id="fre-project-title" type="text" name="post_title">
                            </div>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="fre-project-describe"><?php _e('Describe what you need done', ET_DOMAIN);?></label>
                                <?php wp_editor( '', 'post_content', ae_editor_settings() );  ?>
                            </div>
                            <div class="fre-input-field" id="gallery_place">
                                <label class="fre-field-title" for=""><?php _e('Attachments (optional)', ET_DOMAIN);?></label>
                                <div class="edit-gallery-image" id="gallery_container">
                                    <ul class="fre-attached-list gallery-image carousel-list" id="image-list"></ul>
                                    <div class="plupload_buttons" id="carousel_container">
                                        <label class="img-gallery fre-project-upload-file" id="carousel_browse_button">
                                            <?php _e("Upload Files", ET_DOMAIN); ?>
                                        </label>
                                    </div>
                                    <p class="fre-allow-upload"><?php _e('(Upload maximum 5 files with extensions including png, jpg, pdf, xls, and doc format)', ET_DOMAIN);?></p>
                                    <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
                                </div>
                            </div>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="skill"><?php _e('What skills do you require?', ET_DOMAIN);?></label>
                                <?php
                                    ae_tax_dropdown( 'skill' , array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s skills", ET_DOMAIN), ae_get_option('fre_max_skill', 5)).'"',
                                                        'class' => 'fre-chosen-multi required',
                                                        'hide_empty' => false,
                                                        'hierarchical' => true ,
                                                        'id' => 'skill' ,
                                                        'show_option_all' => false,
                                                )
                                    );
                                ?>
                            </div>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="project-budget"><?php _e('Your project budget', ET_DOMAIN);?></label>
                                <div class="fre-project-budget">
                                    <input id="project-budget" type="number" step="5" required type="number" class="input-item text-field is_number numberVal" name="et_budget" min="1">
                                    <span><?php echo fre_currency_sign(false);?></span>
                                </div>
                            </div>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="project-location"><?php _e('Location (optional)', ET_DOMAIN);?></label>
                                <?php
                                    ae_tax_dropdown( 'country' ,array(
                                            'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="'.__("Choose country", ET_DOMAIN).'"',
                                            'class'           => 'fre-chosen-single',
                                            'hide_empty'      => false,
                                            'hierarchical'    => true ,
                                            'id'              => 'country',
                                            'show_option_all' => __("Choose country", ET_DOMAIN),
                                        )
                                    );
                                ?>
                            </div>
                            <?php
                                // Add hook: add more field
                                echo '<ul class="fre-custom-field">';
                                do_action( 'ae_submit_post_form', PROJECT, $post );
                                echo '</ul>';  
                            ?>
                            <div class="fre-post-project-btn">
                                <button class="fre-btn fre-post-project-next-btn" type="submit"><?php _e("Update", ET_DOMAIN); ?></button>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();