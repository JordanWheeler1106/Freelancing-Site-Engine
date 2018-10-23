<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_VC_Tta_Accordion|WPBakeryShortCode_VC_Tta_Tabs|WPBakeryShortCode_VC_Tta_Tour|WPBakeryShortCode_VC_Tta_Pageable
 */
$el_class = $css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );
extract( $atts );

$button = '';
if( isset($atts['tab_style']) && $atts['tab_style'] == "profile" ){
    ob_start();
    fre_profile_button();
    $button = ob_get_clean();
}
if ( isset($atts['tab_style']) && $atts['tab_style'] == "project" ){
    ob_start();
    $button = fre_project_button();
    $button = ob_get_clean();
}

$this->setGlobalTtaInfo();
$this->enqueueTtaStyles();
$this->enqueueTtaScript();

// It is required to be before tabs-list-top/left/bottom/right for tabs/tours
$prepareContent = $this->getTemplateVariable( 'content' );

$class_to_filter = $this->getTtaGeneralClasses();
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$output = '<div ' . $this->getWrapperAttributes() . '>';
$output .= '<div class="number-project-wrapper"><div class="container"><div class="vc-tta-title">';
$output .= $this->getTemplateVariable( 'title' );
$output .= '</div></div></div>';
$output .= '<div class="' . esc_attr( $css_class ) . '">';
$output .= '<div class="number-project-wrapper"><div class="container"><div style="position: relative;">';
$output .= $this->getTemplateVariable( 'tabs-list-top' );
$output .= $button.'</div></div></div>';
$output .= $this->getTemplateVariable( 'tabs-list-left' );
$output .= '<div class="container"><div class="vc-tta-container">';
$output .= '<div class="vc_tta-panels-container">';
$output .= $this->getTemplateVariable( 'pagination-top' );
$output .= '<div class="vc_tta-panels">';
$output .= $prepareContent;
$output .= '</div></div>';
$output .= $this->getTemplateVariable( 'pagination-bottom' );
$output .= '</div>';
$output .= '</div>';
$output .= $this->getTemplateVariable( 'tabs-list-bottom' );
$output .= $this->getTemplateVariable( 'tabs-list-right' );
$output .= '</div>';
$output .= '</div>';

echo $output;
