<div id="wpbody" role="main">
    <div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
        <h1><?php _e( "Postem Ipsum", POSTEM_IPSUM_TEXT_DOMAIN ); ?></h1>
        <div class="wrap">
            <form id="postem-ipsum-product-generation" method="post" action="options.php">
				<?php settings_fields( 'postem-ipsum-general-settings' ); ?>
				<?php do_settings_sections( 'postem-ipsum-general-settings' ); ?>
                <h1 class="table_header">
                    <span class="dashicons dashicons-admin-generic"></span>
					<?php _e( "Woocommerce General Settings", POSTEM_IPSUM_TEXT_DOMAIN ); ?>
                    <span class="header_icon dashicons dashicons-arrow-up-alt2"></span></h1>
                <hr>
                <div class="slider  table_container">
                    <table class="form-table widefat">
                        <tr>
                            <th scope="row"><?php _e( 'Select Product Category', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td class="select_product_category">
                                <label><?php _e( "Random", POSTEM_IPSUM_TEXT_DOMAIN ); ?>: </label>
                                <input type="checkbox" name="cat_random" id="cat_random">
								<?php
								wp_dropdown_categories(
									array(
										'hide_empty'   => 0,
										'hierarchical' => 1,
										'taxonomy'     => 'product_cat',
										'class'        => "select_category"
									) );
								?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'How many products', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td colspan="2">
                                <input type="number" step="1" min="1" name="postem_ipsum_woo_products_number"
                                       id="postem_ipsum_woo_products_number" value="1">
                            </td>
                        </tr>
                    </table>
                </div>
                <h1 class="table_header">
                    <span class="dashicons dashicons-cart"></span> <?php _e( "Product Settings", POSTEM_IPSUM_TEXT_DOMAIN ); ?>
                    <span class="header_icon dashicons dashicons-arrow-up-alt2"></span>
                </h1>
                <hr>
                <div class="slider table_container">
                    <table class="form-table widefat">
                        <tr>
                            <th scope="row"><?php _e( 'Paragraphs', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td>
                                <input type="number" step="1" min="1" max="5" name="postem_ipsum_woo_product_paragraphs"
                                       id="postem_ipsum_woo_product_paragraphs" value="1">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Average length', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td>
                                <select name="postem_ipsum_woo_product_paragraph_length"
                                        id="postem_ipsum_woo_product_paragraph_length">
                                    <option value="0"><?php _e( "Select an option", POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                    <option value="short"><?php _e( "Short", POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                    <option value="medium"><?php _e( "Medium", POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                    <option value="long"><?php _e( "Long", POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                    <option value="verylong"><?php _e( "Very Long", POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Set min and max price', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td>
                                <div id="price_slider"></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <h1 class="table_header">
                    <span class="dashicons dashicons-format-image"></span>
					<?php _e( "Product Image Settings", POSTEM_IPSUM_TEXT_DOMAIN ); ?>
                    <span class="header_icon dashicons dashicons-arrow-up-alt2"></span>
                </h1>
                <hr>
                <div class="slider table_container">
                    <table class="form-table  widefat postem_ipsum_image_table">
                        <tr>
                            <th scope="row"><?php _e( 'Product Image?', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td>
                                <select name="postem_ipsum_woo_product_image" id="postem_ipsum_woo_product_image">
                                    <option value="0"><?php _e( 'Select an option', POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                    <option value="yes"><?php _e( 'Yes', POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                    <option value="no"><?php _e( 'No', POSTEM_IPSUM_TEXT_DOMAIN ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr class="postem_ipsum_image_color" style="display: none;">
                            <th scope="row"><?php _e( 'Image Color', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td style="height: 0px;">
                                <label style="float: left; margin-top: 5px;"><?php _e( 'Random: ', POSTEM_IPSUM_TEXT_DOMAIN ); ?></label>
                                <input type="checkbox" name="bg_random" id="bg_random"
                                       style="float: left; margin: 5px 10px 0 5px;">
                                <input class="color-field" type="text" name="postem_ipsum_product_image_bg"
                                       id="postem_ipsum_product_image_bg" value="" style="float: left; width: 80%;">
                            </td>
                        </tr>
                        <tr class="postem_ipsum_image_w" style="display: none;">
                            <th scope="row"><?php _e( 'Image Width', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td>
                                <input type="number" step="10" min="10" name="postem_ipsum_woo_product_image_w"
                                       id="postem_ipsum_woo_product_image_w" value="300">
                            </td>
                        </tr>
                        <tr class="postem_ipsum_image_h" style="display: none;">
                            <th scope="row"><?php _e( 'Image Height', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
                            <td>
                                <input type="number" step="10" min="10" name="postem_ipsum_woo_product_image_h"
                                       id="postem_ipsum_woo_product_image_h" value="300">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <button class="button button-primary postem-ipsum-generate-products"><?php _e( 'Generate products', POSTEM_IPSUM_TEXT_DOMAIN ); ?></button>
        <button class="button button-primary postem-ipsum-delete-products"><?php _e( 'Delete all products generated with Postem Ipsum', POSTEM_IPSUM_TEXT_DOMAIN ); ?></button>
        <div class="result"></div>
    </div>
</div>