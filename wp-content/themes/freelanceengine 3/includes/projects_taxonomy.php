<?php 

class Fre_Project_Category extends AE_Base{
    function __construct() {
        $this->add_action( 'project_category_add_form_fields', 'fre_add_form_fields');
        $this->add_action( 'created_project_category', 'fre_save_tax_meta', 10, 2 );

        $this->add_action( 'project_category_edit_form_fields', 'fre_edit_tax_group_field', 10, 2 );
        $this->add_action( 'edited_project_category', 'fre_update_tax_meta', 10, 2 );
        
        $this->add_action( 'admin_enqueue_scripts', 'fre_tax_enqueue_scripts'  );


    }
    function fre_add_form_fields($taxonomy){
        global $featured_tax;
        $term_id = 0;
        // Remove image URL
        $remove_url = add_query_arg( array(
            'action'   => 'remove-wp-term-images',
            'term_id'  => $term_id,
            '_wpnonce' => false,
        ) );
        // Get the meta value
        $value = get_term_meta($term_id, 'project_category_image', true);
        $hidden = empty( $value ) ? ' style="display: none;"' : ''; ?>
        <div class="form-field term-group">
            <label><?php _e('Taxonomy image', ET_DOMAIN) ?></label>
            <div>
                <img id="ae-tax-images-photo" src="<?php echo esc_url( wp_get_attachment_image_url( $value, 'full' ) ); ?>"<?php echo $hidden; ?> />
                <input type="hidden" name="<?php echo $taxonomy; ?>_image" id="<?php echo $taxonomy; ?>_image" value="<?php echo esc_attr( $value ); ?>" />
            </div>
            <a class="button-secondary ae-tax-images-media">
                <?php esc_html_e( 'Choose Image', 'enginethemes' ); ?>
            </a>
            <a href="<?php echo esc_url( $remove_url ); ?>" class="button ae-tax-images-remove"<?php echo $hidden; ?>>
                <?php esc_html_e( 'Remove', 'wp-user-avatars' ); ?>
            </a>
            <div class="clearfix"></div><br/>
            <div class="featured-tax">
                <input type="checkbox" name="featured-tax" class="left margin-20 margin-top-3" value="true" />
                <label for="featured-tax" class="left" style="display: inline;"><?php _e('Featured taxonomy', ET_DOMAIN); ?></label>
            </div>
        </div>
        <div class="clearfix"></div>
    <?php 
    }

    public function fre_save_tax_meta( $term_id, $tt_id ){
        if( isset( $_POST['featured-tax'] ) && '' !== $_POST['featured-tax'] ){
            $group = sanitize_title( $_POST['featured-tax'] );
            add_term_meta( $term_id, 'featured-tax', $group, true );
        }
        if( isset( $_POST['project_category_image'] ) && '' !== $_POST['project_category_image'] ){
            $group = sanitize_title( $_POST['project_category_image'] );
            update_term_meta( $term_id, 'project_category_image', $group );
        }
    }

    function fre_edit_tax_group_field( $term, $taxonomy ){
        global $featured_tax;
        // get current group
        $check = '';
        $featured_tax = get_term_meta( $term->term_id, 'featured-tax', true );
        if( $featured_tax ){
            $check = 'checked';
        }
        $remove_url = add_query_arg( array(
            'action'   => 'remove-ae-tax-images',
            'term_id'  => $term->term_id,
            '_wpnonce' => false,
        ) );
        $value = get_term_meta($term->term_id, 'project_category_image', true);
        $hidden = empty( $value ) ? ' style="display: none;"' : ''; ?>
        <tr class="form-field term-group-wrap">
            <th scope="row"><label for="featured-tax"><?php _e( 'Featured', ET_DOMAIN ); ?></label></th>
            <td>
                <input type="checkbox" name="featured-tax" value="true" <?php echo $check; ?>/>
                <label for="featured-tax"><?php _e('Featured taxonomy', 'enginethemes'); ?></label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="tax-image"><?php _e( 'Thumbnail', 'enginethemes' ); ?></label></th>
            <td>
                <div>
                    <img id="ae-tax-images-photo" src="<?php echo esc_url( wp_get_attachment_image_url( $value, 'thumbnail' ) ); ?>"<?php echo $hidden; ?> />
                    <input type="hidden" name="<?php echo $taxonomy; ?>_image" id="<?php echo $taxonomy; ?>_image" value="<?php echo esc_attr( $value ); ?>" />
                </div>
                <a class="button-secondary ae-tax-images-media">
                    <?php esc_html_e( 'Choose Image', ET_DOMAIN ); ?>
                </a>
                <a href="<?php echo esc_url( $remove_url ); ?>" class="button ae-tax-images-remove"<?php echo $hidden; ?>>
                    <?php esc_html_e( 'Remove', 'wp-user-avatars' ); ?>
                </a>
            </td>
        </tr>
    <?php
    }
    public function fre_update_tax_meta( $term_id, $tt_id ){
        if( isset( $_POST['featured-tax'] ) && '' !== $_POST['featured-tax'] ){
            $group = sanitize_title( $_POST['featured-tax'] );
            update_term_meta( $term_id, 'featured-tax', $group );
        }else{
            update_term_meta($term_id, 'featured-tax', false);
        }
        if( isset( $_POST['project_category_image'] ) && '' !== $_POST['project_category_image'] ){
            $group = sanitize_title( $_POST['project_category_image'] );
            update_term_meta( $term_id, 'project_category_image', $group );
        }else{
            update_term_meta($term_id, 'project_category_image', false);
        }
    }
    public function fre_tax_enqueue_scripts(){
        wp_enqueue_media();
        wp_enqueue_script( 'fre-tax-images', get_template_directory_uri() . '/assets/js/fre_taxonomy_custom.js',   array(
            'jquery',
            'underscore',
            'backbone',
            'appengine'
        ), 1.0, true );
        $term_id = ! empty( $_GET['tag_ID'] )
            ? (int) $_GET['tag_ID']
            : 0;
        // Localize
        wp_localize_script( 'fre-tax-images', 'i10n_WPTermImages', array(
            'insertMediaTitle' => esc_html__( 'Choose an Image', 'wp-user-avatars' ),
            'insertIntoPost'   => esc_html__( 'Set as image',    'wp-user-avatars' ),
            'deleteNonce'      => wp_create_nonce( 'remove_ae_tax_images_nonce' ),
            'mediaNonce'       => wp_create_nonce( 'assign_ae_tax_images_nonce' ),
            'term_id'          => $term_id,
        ) );
    }
}
new Fre_Project_Category();