<?php

global $user_ID;
get_header();
?>
    <div class="blog-header-container">
        <div class="container">
            <!-- blog header -->
            <div class="row">
                <div class="col-md-12 blog-classic-top">
                    <h2 class="recharge">Recharge Credit</h2>
                </div>
            </div>
            <!--// blog header  -->
        </div>
    </div>
    <div id="recharge-pages">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 recharge-container">
                    <div class="step-wrapper step-input-account" id="step-input-account">
                        <a href="#" class="step-heading active">
                            <span class="number-step">1</span>
                            <span class="text-heading-step"><?php _e( 'Enter amount' , ET_DOMAIN ); ?></span>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="step-content-wrapper content">
                            <ul class="information-charge">
                                <li>
                                    <div class="items">Current total credit</div>
                                    <div class="price color-item-1">$1000</div>
                                </li>
                                <li>
                                    <div class="items">Available credit</div>
                                    <div class="price color-item-2">$ 850</div>
                                </li>
                                <li>
                                    <div class="items">Frozen credit</div>
                                    <div class="price color-item-3">$ 150</div>
                                </li>
                            </ul>
                            <div class="amount">
                                <div class="form-group">
                                    <label for="">Recharge amount</label>
                                    <input type="text" class="input-charge">
                                    <button class="btn-summary btn-charge">Recharge</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-wrapper step-select-payment" id="step-select-payment">
                        <a href="#" class="step-heading">
                            <span class="number-step">2</span>
                            <span class="text-heading-step"><?php _e( 'Select payment method' , ET_DOMAIN ); ?></span>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="step-content-wrapper content">
                            <ul class="payment-charge">
                                <li>
                                    <div class="brand-name">
                                        <p class="name-gateway">pay pal</p>
                                        <p class="text">Here goes the description for the paypal</p>
                                    </div>
                                    <div class="button">
                                        <button class="btn-summary btn-charge">Select</button>
                                    </div>
                                </li>
                                <li>
                                    <div class="brand-name">
                                        <p class="name-gateway">2checkout</p>
                                        <p class="text">Here goes the description for the paypal</p>
                                    </div>
                                    <div class="button">
                                        <button class="btn-summary btn-charge">Select</button>
                                    </div>
                                </li>
                                <li>
                                    <div class="brand-name">
                                        <p class="name-gateway">google checkout</p>
                                        <p class="text">Here goes the description for the paypal</p>
                                    </div>
                                    <div class="button">
                                        <button class="btn-summary btn-charge">Select</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12  blog-sidebar" id="right_content">
                    <?php get_sidebar('blog'); ?>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
