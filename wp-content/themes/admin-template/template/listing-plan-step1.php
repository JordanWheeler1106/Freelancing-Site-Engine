<!-- Step 1 -->
<?php
global $user_ID, $ae_post_factory;
$ae_pack = $ae_post_factory->get( 'pack' );
$packs   = $ae_pack->fetch( 'pack' );

$package_data = AE_Package::get_package_data( $user_ID );

$orders = AE_Payment::get_current_order( $user_ID );

$number_free_plan_used = AE_Package::get_used_free_plan( $user_ID );
?>
<div id="fre-post-project-1 step-plan" class="fre-post-project-step step-wrapper step-plan active">
    <div class="fre-post-project-box">
        <div class="step-post-package">
            <h2><?php _e( 'Choose your most appropriate package', ET_DOMAIN ) ?></h2>
            <ul class="fre-post-package">
				<?php foreach ( $packs as $key => $package ) {
					$number_of_post = $package->et_number_posts;
					$sku            = $package->sku;
					$text           = '';
					$order          = false;
					$disabled_plan  = '';
					$input          = '';


					if ( $number_of_post > 0 ) {
						if ( isset( $orders[ $sku ] ) ) {
							$order = get_post( $orders[ $sku ] );
						}

						if ( isset( $package_data[ $sku ] ) && isset( $order->post_status ) && $order->post_status != 'draft' ) {
							$package_data_sku = $package_data[ $sku ];
							if ( isset( $package_data_sku['qty'] ) && $package_data_sku['qty'] > 0 ) {
								$number_of_post = $package_data_sku['qty'];
							}
						}

						if ( $package->et_duration > 0 ) {
							if ( $package->et_duration > 1 ) {
								$duration_text = sprintf( __( '%s days', ET_DOMAIN ), $package->et_duration );
							} else {
								$duration_text = sprintf( __( '%s day', ET_DOMAIN ), $package->et_duration );
							}
						} else {
							$duration_text = sprintf( __( '%s days', ET_DOMAIN ), $package->et_duration );
						}

						if ( $package->et_price > 0 ) {
							$input = '<input id="package-' . $package->ID . '" name="post-package" type="radio" ' . $disabled_plan . '>';
							$price = fre_price_format( $package->et_price );
						} else {
							$disabled_plan  = 'disabled';
							$price          = __( "Free", ET_DOMAIN );
							$number_of_post = (int) $number_of_post - (int) $number_free_plan_used;
							if ( $number_of_post < 0 ) {
								$number_of_post = 0;
							}
						}

						if ( $number_of_post > 0 ) {
							if ( $number_of_post > 1 ) {
								$text = sprintf( __( "%s for %s projects, displaying in %s.", ET_DOMAIN ), $price, $number_of_post, $duration_text );
							} else {
								$text = sprintf( __( "%s for %s project, displaying in %s.", ET_DOMAIN ), $price, $number_of_post, $duration_text );
							}
						} else {
							$text = sprintf( __( "%s for %s projects, displaying in %s.", ET_DOMAIN ), $price, $number_of_post, $duration_text );
						}

						echo '<li class="' . $disabled_plan . '" data-sku="' . trim( $package->sku ) . '" data-id="' . $package->ID . '" data-price="' . $package->et_price . '" data-package-type="' . $package->post_type . '" data-title="' . $package->post_title . '" data-description="' . $text . '">
						<label class="fre-radio" for="package-' . $package->ID . '">' . $input . '<span>' . $package->post_title . '</span></label>
						<span class="disc">' . $text . ' ' . wp_strip_all_tags( $package->post_content ) . '</span>
						</li>';
					}
				} ?>
            </ul>
			<?php echo '<script type="data/json" id="package_plans">' . json_encode( $packs ) . '</script>'; ?>
            <div class="fre-select-package-btn">
                <input class="fre-btn fre-post-project-next-btn select-plan" type="button"
                       value="<?php _e( 'Next Step', ET_DOMAIN ); ?>">
            </div>
        </div>
    </div>
</div>

<!-- Step 1 / End -->