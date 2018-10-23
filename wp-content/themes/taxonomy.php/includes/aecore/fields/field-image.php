<?php
class AE_image {

    /**
     * Field Constructor.
     *
     * @param array $field
     * - id
     * - name
     * - placeholder
     * - readonly
     * - class
     * - title
     * @param $value
     * @param $parent
     * @since AEFramework 1.0.0
    */
    public function __construct( $field = array(), $value ='', $parent ) {

        //parent::__construct( $parent->sections, $parent->args );
        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;

    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since AEFramework 1.0.0
    */
    function render() {
        $uploaderID = $this->field['name'];
        $size       = $this->field['size'];

        $attach_id = $this->value['attach_id'];
        $src = wp_get_attachment_image_src($attach_id, $size);
    ?>
        <div class="customization-info" id="<?php echo $this->field['name']; ?> ">
            <div class="input-file upload-logo" id="<?php echo $uploaderID; ?>_container" data-id="<?php echo $uploaderID; ?>" data-w="<?php echo $size[0] ?>" data-h="<?php echo $size[1] ?>" >
                <div class="left clearfix">
                    <div class="image" id="<?php echo $uploaderID;?>_thumbnail" style="<?php echo 'width:'. $size[0] .'px; height:'. $size[1] .'px; text-align:center;';  ?> ">
                        <img style="max-height: <?php echo $size[1] ?>px;" src="<?php echo $src[0]; ?>" />
                    </div>
                </div>

                <span class="et_ajaxnonce" id="<?php echo de_create_nonce( $uploaderID . '_et_uploader' ); ?>"></span>
                <span class="bg-grey-button button btn-button" id="<?php echo $uploaderID;?>_browse_button" style="height:50px;margin-top:10px;">
                    <?php _e('Browse', ET_DOMAIN);?>
                    <span class="icon" data-icon="o"></span>
                </span>
                    <span class="bg-grey-button button btn-button btn-remove-option-image" id="<?php echo $uploaderID.'';?>_remove_button" style="height:50px;margin-top:10px; margin-left:10px; display:none" data-id="<?php echo $attach_id ?>" data-name="<?php echo $uploaderID;?>">
                        <?php _e('Remove', ET_DOMAIN);?>
                        <span class="icon" data-icon="#"></span>
                    </span>
            </div>
        </div>
        <div style="clear:left"></div>
    <?php

    }//render

}
 /**
     * Remove option image
     * @param void
     * @return void
     * @since void
     * @package void
     * @category void
     * @author Tambh
     */
function ae_remove_option_image(){
    global $user_ID;
    $request = $_REQUEST;
    if( isset($request['ID']) && $request['ID'] != ''){
        if( is_super_admin($user_ID) ){
            $result = wp_delete_attachment($request['ID']);
            if( $result ){
                ae_update_option($request['name'], false);
                wp_send_json(array(
                    'success'=> true,
                    'msg'=>__('Removed successfully!', ET_DOMAIN)
                    )
                );
            }
        }
    }
    wp_send_json(array(
                'success'=> false,
                'msg'=>__('Removed failed!', ET_DOMAIN)
                )
            );
}

add_action('wp_ajax_ae-remove-option-image', 'ae_remove_option_image');