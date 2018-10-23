<?php 
	global $user_ID, $ae_post_factory;
?>
<div class="fre-service-content">
	<div class="row">
		<div class="col-md-1 hidden-sm"></div>
		<div class="col-md-10">
			<div class="row fre-service-package-list">
				<?php
					if(!is_user_logged_in() || ae_user_role($user_ID) != FREELANCER){
						// Show Package for project - role Admin, Employer & Visitor
					    $ae_pack = $ae_post_factory->get('pack');
					    $packs = $ae_pack->fetch('pack');
						foreach ($packs as $key => $package) {
	                        if($package->et_duration == 1){
	                        	$duration = sprintf(__('%s day', ET_DOMAIN), $package->et_duration);
	                        }else{
	                        	$duration = sprintf(__('%s days', ET_DOMAIN), $package->et_duration);
	                        }
				?>
						<div class="col-md-4 col-sm-6">
							<div class="fre-service-pricing">
								<div class="service-price">
									<?php 
										if($package->et_price > 0) {
											echo "<h2>";
											ae_price($package->et_price);
											echo "</h2> ";
											echo "<p>";
                        					printf(__(" for %s", ET_DOMAIN), $duration);
                        					echo "</p>";
                        				}else{
                        					echo "<h2>";
											_e("FREE", ET_DOMAIN);
											echo "</h2> ";
											echo "<p>";
                        					printf(__("for %s", ET_DOMAIN), $duration);
                        					echo "</p>";
                        				}
									?>
								</div>
								<div class="service-info">
									<h3><?php echo $package->post_title; ?></h3>
									<p><?php echo $package->post_content; ?></p>
								</div>
								
							 	<?php 
							 	if(!is_user_logged_in() ) { 
						 				if(fre_check_register()){ ?>
                                	<a class="fre-service-btn" href="<?php echo et_get_page_link( 'register', array('role' => 'employer')); ?>">
                                    	<?php _e('Sign Up', ET_DOMAIN); ?>
                                    </a>
                                <?php }
                                }else{ ?>
                                	<a class="fre-service-btn" href="<?php echo et_get_page_link( array('page_type' => 'submit-project') ); ?>">
                                    	<?php _e("Purchase", ET_DOMAIN); ?>
                                    </a>
                                <?php } ?>
							</div>
						</div>
				<?php
						}
					}else{
						// Show Package for bid - role Freelancer
						$ae_bid = $ae_post_factory->get('bid_plan');
    					$bid_plans = $ae_bid->fetch('bid_plan');
    					foreach ($bid_plans as $key => $plan) {
    			?>			
    						<div class="col-md-4 col-sm-6">
								<div class="fre-service-pricing">
									<div class="service-price">
										<?php 
											if($plan->et_price > 0) {
												echo "<h2>";
												ae_price($plan->et_price);
												echo "</h2> ";
	                        				}else{
	                        					echo "<h2>";
												_e("FREE", ET_DOMAIN);
												echo "</h2> ";
	                        				}
										?>
									</div>
									<div class="service-info">
										<h3><?php echo $plan->post_title; ?></h3>
										<p><?php echo $plan->post_content; ?></p>
									</div>
									<a class="fre-service-btn" href="<?php echo et_get_page_link('upgrade-account');?>"><?php _e('Purchase', ET_DOMAIN)?></a>
								</div>
							</div>
    			<?php
    					}

					}
				?>
			</div>
		</div>
		<div class="col-md-1 hidden-sm"></div>
	</div>
</div>