<?php
$category_project_selected = '';
if ( isset( $_GET['category_project'] ) && $_GET['category_project'] != '' ) {
	$category_project_selected = $_GET['category_project'];
}

?>

<div class="fre-project-filter-box">
    <script type="data/json" id="search_data">
            <?php
		$search_data = $_POST;
		echo json_encode( $search_data );
		?>

    </script>
    <div class="project-filter-header visible-sm visible-xs">
        <a class="project-filter-title" href=""><?php _e( 'Advance search', ET_DOMAIN ); ?></a>
    </div>
    <div class="fre-project-list-filter">
        <form>
            <div class="row">
                <div class="col-md-4">
                    <div class="fre-input-field">
                        <label for="s" class="fre-field-title"><?php _e( 'Keyword', ET_DOMAIN ); ?></label>
                        <input class="keyword search" id="s" type="text" name="s"
                               placeholder="<?php _e( 'Search projects by keyword', ET_DOMAIN ); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fre-input-field dropdown">
                        <label for="skills" class="fre-field-title"><?php _e( 'Skills', ET_DOMAIN ); ?></label>
                        <input id="skills" class="dropdown-toggle fre-skill-field" readonly type="text"
                               placeholder="<?php _e( 'Search project by skills', ET_DOMAIN ); ?>" data-toggle="dropdown">

						<?php $terms = get_terms( 'skill', array( 'hide_empty' => 0 ) ); ?>
						<?php if ( ! empty( $terms ) ) : ?>
                            <div class="dropdown-menu dropdown-menu-skill">
								<?php if ( count( $terms ) > 7 ) : ?>
                                    <div class="search-skill-dropdown">
                                        <input class="fre-search-skill-dropdown" type="text">
                                    </div>
								<?php endif ?>
                                <ul class="fre-skill-dropdown" data-name="skill">

									<?php
									foreach ( $terms as $key => $value ) {
										echo '<li><a class="fre-skill-item" name="' . $value->slug . '" href="">' . $value->name . '</a></li>';
									}
									?>
                                </ul>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fre-input-field">
                        <label for="project_category"
                               class="fre-field-title"><?php _e( 'Category', ET_DOMAIN ); ?></label>
						<?php ae_tax_dropdown( 'project_category', array(
								'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="' . __( "Select categories", ET_DOMAIN ) . '"',
								'show_option_all' => __( "Select category", ET_DOMAIN ),
								'class'           => 'fre-chosen-single',
								'hide_empty'      => false,
								'hierarchical'    => true,
								'selected'        => $category_project_selected,
								'id'              => 'project_category',
								'value'           => 'slug',
							)
						); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fre-input-field project-number-bids">
                        <label for="number_bids" class="fre-field-title"><?php _e( 'Bid', ET_DOMAIN ); ?></label>
                        <select name="number_bids" id="number-bids" class="fre-chosen-single">
                            <option value=""><?php _e( 'Any Bids', ET_DOMAIN ); ?></option>
                            <option value="0,10">0 - 10</option>
                            <option value="11,20">11 - 20</option>
                            <option value="21,30">21 - 30</option>
                            <option value="31"><?php _e( 'Greater than 30', ET_DOMAIN ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fre-input-field">
                        <label for="country" class="fre-field-title"><?php _e( 'Location', ET_DOMAIN ); ?></label>
						<?php
						ae_tax_dropdown( 'country', array(
								'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="' . __( "Select country", ET_DOMAIN ) . '"',
								'class'           => 'fre-chosen-single',
								'hide_empty'      => false,
								'hierarchical'    => true,
								'value'           => 'slug',
								'id'              => 'country',
								'show_option_all' => __( "Select country", ET_DOMAIN )
							)
						);
						?>
                    </div>
                </div>
                <div class="col-md-4">
					<?php $max_slider = ae_get_option( 'fre_slide_max_budget', 2000 ); ?>
                    <div class="fre-input-field fre-budget-field">
                        <label for="budget" class="fre-field-title"><?php _e( 'Budget', ET_DOMAIN ); ?>
                            (<?php fre_currency_sign() ?>)</label>
                        <input id="budget" class="filter-budget-min" type="number" name="min_budget" value="0" min="0">
                        <span>-</span>
                        <input class="filter-budget-max" type="number" name="max_budget"
                               value="<?php echo $max_slider; ?>" min="0">
                        <input id="et_budget" type="hidden" name="et_budget" value="0,<?php echo $max_slider; ?>"/>
                    </div>
                </div>
            </div>
            <a class="project-filter-clear clear-filter" href=""><?php _e( 'Clear all filters', ET_DOMAIN ); ?></a>
        </form>
    </div>
</div>