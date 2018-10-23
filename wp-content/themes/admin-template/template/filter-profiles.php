<div class="fre-profile-filter-box">
      <script type="data/json" id="search_data">
            <?php 
                $search_data = $_POST;
                echo json_encode($search_data);
            ?>
      </script>
      <div class="profile-filter-header visible-sm visible-xs">
          <a class="profile-filter-title" href=""><?php _e('Advance search', ET_DOMAIN);?></a>
      </div>
      <div class="fre-profile-list-filter">
          <form>
              <div class="row">
                  <div class="col-md-4">
                      <div class="fre-input-field">
                          <label for="keywords" class="fre-field-title"><?php _e('Keyword', ET_DOMAIN);?></label>
                          <input class="keyword search" id="s" type="text" name="s" placeholder="<?php _e('Search freelancers by keyword', ET_DOMAIN);?>">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="fre-input-field dropdown">
                          <label for="skills" class="fre-field-title"><?php _e('Skills', ET_DOMAIN);?></label>
                          <input id="skills" class="dropdown-toggle fre-skill-field" type="text" placeholder="<?php _e ('Search freelancers by skills', ET_DOMAIN ); ?>" data-toggle="dropdown" readonly>
                          <?php $terms = get_terms('skill', array('hide_empty' => 0)); ?>
                          <?php if(!empty($terms)) : ?>
                            <div class="dropdown-menu dropdown-menu-skill">
                              <?php if(count($terms) > 7) : ?>
                                  <div class="search-skill-dropdown">
                                    <input class="fre-search-skill-dropdown" type="text">
                                  </div>
                                <?php endif ?>
                              <ul class="fre-skill-dropdown" data-name="skill">
                                
                                <?php
                                    foreach ($terms as $key => $value) {
                                        echo '<li><a class="fre-skill-item" name="'.$value->slug.'" href="">'.$value->name.'</a></li>';
                                    }
                                ?>
                              </ul>
                            </div>
                          <?php endif; ?>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="fre-input-field">
                          <label for="total_earning" class="fre-field-title"><?php _e('Earning', ET_DOMAIN);?> (<?php fre_currency_sign() ?>)</label>
                          <select name="earning" id="total_earning" class="fre-chosen-single">
                              <option value=""><?php _e('Any amount', ET_DOMAIN);?></option>
                              <option value="100">0 - 100</option>
                              <option value="100-1000">100 - 1000</option>
                              <option value="1000-10000">1000 - 10000</option>
                              <option value="10000"><?php _e('Greater than') ?> 10000</option>
                          </select>
                      </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-4">
                      <div class="fre-input-field project-number-worked">
                          <label for="project-number-worked" class="fre-field-title"><?php _e('Projects Worked', ET_DOMAIN);?></label>
                          <select name="total_projects_worked" id="project-number-worked" class="fre-chosen-single">
                              <option value=""><?php _e('Any projects worked', ET_DOMAIN);?></option>
                              <option value="10">0 - 10</option>
                              <option value="20">11 - 20</option>
                              <option value="30">21 - 30</option>
                              <option value="40"><?php _e('Greater than') ?> 30</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="fre-input-field">
                          <label for="location" class="fre-field-title"><?php _e('Location', ET_DOMAIN);?></label>
                          <?php 
                              ae_tax_dropdown( 'country' ,array(
                                      'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="'.__("Select country", ET_DOMAIN).'"',
                                      'class'           => 'fre-chosen-single',
                                      'hide_empty'      => false,
                                      'hierarchical'    => true ,
                                      'value'           => 'slug',
                                      'id'              => 'country',
                                      'show_option_all' => __("Select country", ET_DOMAIN)
                                  )
                              );
                          ?>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <?php $max_slider = ae_get_option('fre_slide_max_budget_freelancer', 2000); ?>
                      <div class="fre-input-field fre-budget-field">
                          <label for="budget" class="fre-field-title"><?php _e('Hourly Rate', ET_DOMAIN);?> (<?php fre_currency_sign() ?>)</label>
                          <input id="budget" class="filter-budget-min" type="number" name="min_budget" value="0" min="0">
                          <span>-</span>
                          <input class="filter-budget-max" type="number" name="max_budget" value="<?php echo $max_slider; ?>" min="0">
                          <input id="hour_rate" type="hidden" name="hour_rate" value="0,<?php echo $max_slider; ?>"/>
                          <input type="hidden" name="user_available" id="user_available" value= "yes" />
                      </div>
                  </div>
              </div>
              <a class="profile-filter-clear clear-filter" href=""><?php _e('Clear all filters', ET_DOMAIN);?></a>
          </form>
      </div>
  </div>