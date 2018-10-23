<?php
/**
 * this template for payment success, you can overide this template by child theme
*/
global $ad, $payment_return;
extract( $payment_return );
$payment_type			= get_query_var( 'paymentType' );
?>
<div class="redirect-content" style="overflow:hidden" >
	<div class="main-center main-content">
		<h3 class="title"><?php _e("Waiting, friend",ET_DOMAIN);?></h3>
		<div class="content">
			<?php 
				_e("Your payment is waiting for response from the payment gateway. Please reload the page or waiting ...", ET_DOMAIN);
			?>
			<br/>
			<?php _e("Loading",ET_DOMAIN);?> 
			<?php printf(__('Time left: %s', ET_DOMAIN ), '<span class="count_down">10</span>');  ?>
		</div>
	</div>
</div>