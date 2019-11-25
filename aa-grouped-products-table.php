<?php
/*
    Plugin Name: AA Grouped Products Table
    Plugin URI: https://web-devs.pro/
    Description: Custom grouped products table.
    Author: Alex
    Author URI: https://web-devs.pro/
    Text Domain: admin-collapse-subpages
*/


// SHORTCODE `AA_GROUPED_PRODUCTS`
add_shortcode('aa_grouped_products', function ( $atts ) {

    global $woocommerce, $product, $post;

    if ($product->is_type( 'grouped' )) {

        wp_enqueue_style('aa-grouped-css', plugins_url('css/style.css', __FILE__ ));
        wp_enqueue_script('aa-grouped-js',plugins_url('js/script.js', __FILE__ ), array('jquery'));

        $products = $product->get_children();
        if (!$products) return;

        $loop = new WP_Query( array(
            'post_type' => 'product',
            'post__in' => $products,
            'orderby' => 'meta_value',      // sort by SKU
            'meta_key' => '_sku',           // sort by SKU
        ) );
        
        if ($loop->have_posts()) { ?>

            <table class="grouped-products-container" width="100%">

                <thead class="grouped-product-item-container-head">

                    <tr>

                        <th class="grouped-product-col-sku">SKU</th>
                        <th class="grouped-product-col-name">Nazwa</th>
                        <th class="grouped-product-col-lenght">Długość</th>
                        <th class="grouped-product-col-width">Szerokość</th>
                        <th class="grouped-product-col-height">Wysokość</th>
                        <th class="grouped-product-col-diameter">Średnica</th>
                        <th class="grouped-product-col-price">Cena</th>
                        <th class="grouped-product-col-stock">Stan</th>
                        <th class="grouped-product-col-atc"></th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($loop->have_posts()): $loop->the_post();
                        $sub_product = wc_get_product($loop->post->ID); ?>
                        <tr class="grouped-product-item-container">

                            <td data-label="SKU"> 
                                <div class="column-product_sku">
                                    <?php echo $sub_product->get_sku(); ?>
                                </div>
                            </td>

                            <td data-label="Nazwa"> 
                                <div class="column-product_name">
                                    <?php echo $sub_product->get_title(); ?>
                                </div>
                            </td>


                            <td data-label="Długość"><?php echo $sub_product->get_length()?:'-'; ?></td>

                            <td data-label="Szerokość"><?php echo $sub_product->get_width()?:'-'; ?></td>
                            
                            <td data-label="Wysokość"><?php echo $sub_product->get_height()?:'-'; ?></td>

                            <td data-label="Średnica"><?php echo get_post_meta( $sub_product->get_id(), '_diameter', true )?:'-'; ?></td>

                            <td data-label="Cena"><?php echo $sub_product->get_regular_price()?:'-'; ?></td>

                            <td data-label="Stan"><?php echo $sub_product->get_stock_quantity()?:'-'; ?></td>


                            <td class="add_to_cart">

                                <?php if ($sub_product->get_stock_quantity()) { ?>

                                    <div class="grouped-product-qtt">
                                        <span class="grouped-product-qtt_button qtt_minus">-</span>
                                        <input type="text" readonly class="grouped-product-qtt_count" value="0" />
                                        <span class="grouped-product-qtt_button qtt_plus">+</span>
                                    </div>

                                    <div class="grouped-product-atc">
                                    
                                        <?php echo sprintf( '<a href="%s" data-quantity="0" class="%s" %s>%s</a>',
                                            esc_url( $sub_product->add_to_cart_url() ),
                                            esc_attr( implode( ' ', array_filter( array(
                                                'button', 'product_type_' . $sub_product->get_type(),
                                                $sub_product->is_purchasable() && $sub_product->is_in_stock() ? 'add_to_cart_button' : '',
                                                $sub_product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
                                            ) ) ) ),
                                            wc_implode_html_attributes( array(
                                                'data-product_id'  => $sub_product->get_id(),
                                                'data-product_sku' => $sub_product->get_sku(),
                                                'aria-label'       => $sub_product->add_to_cart_description(),
                                                'rel'              => 'nofollow',
                                            ) ),
                                            '<i aria-hidden="true" class="fal fa-cart-plus"></i>'
                                        );?>
                                    </div>

                                <?php } else { ?>

                                    <div class="not_in_stock">
                                        <a href="#">Zamówić</a>
                                    </div>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>
            
        <?php }
        echo "</ul>";
        wp_reset_postdata();
        
    } 
    //return $result;
});




// ADDITIONAL SHIPPING FIELD `DIAMETER`
// Add custom fields to product shipping tab
add_action( 'woocommerce_product_options_dimensions', function (){
    global $product_object;
    $product_id = method_exists( $product_object, 'get_id' ) ? $product_object->get_id() : $product_object->id;
    echo '</div><div class="options_group">'; // New option group
    woocommerce_wp_text_input( array(
        'id'          => '_diameter',
        'label'       => __( 'Diameter', 'woocommerce' ),
        'desc_tip'    => 'true',
        'description' => __( 'Diameter description help.', 'woocommerce' )
    ) );
});

// Save the custom fields values as meta data
add_action( 'woocommerce_process_product_meta', function ($post_id){
    if( isset( $_POST['_diameter'] ) ) {
        update_post_meta( $post_id, '_diameter', esc_attr( $_POST['_diameter'] ) );
    } 
});


