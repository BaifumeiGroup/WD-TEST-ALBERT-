<?php

function load_banner_assets() {

    $js1 = plugins_url('assets/js/jquery.bxslider.js', dirname(__FILE__));
    $js2 = plugins_url('assets/js/bxslider.custom.js', dirname(__FILE__));
    $css = plugins_url('assets/css/jquery.bxslider.css', dirname(__FILE__));
	wp_enqueue_style( 'style-name', $css );
	wp_enqueue_script( 'bx-slider', $js1, array('jquery'), '1.0.0', true );
	wp_enqueue_script( 'bx-slider-custom', $js2, array('jquery','bx-slider'), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'load_banner_assets' );

//load  
//add_action('woocommerce_before_main_content','show_banner_main',1, 0);
//add_action('woocommerce_archive_description','show_banner_main',10, 0);
add_action('woocommerce_before_shop_loop','show_banner_main',10,0);

function show_banner_main() {
   global $posttype;
   //get the banner for the specific section
   $args = array(
    'post_type' => $posttype,
    'posts_per_page' => 1,
   );

   $banners = new WP_Query($args);
   $banner_id = $banners->posts[0]->ID;
   $prodimgids =  explode(":",get_post_meta( $banner_id, 'bannerimages_prodimg',true));
   $prodimgids = array_filter($prodimgids);

   //check which section of woocommerce is being loaded
    if (is_product_category()) {
        ?>
        <div class="center banner-images">
            <ul class="bxslider">
            <?php 
                if (is_array($prodimgids)) {
                    foreach($prodimgids as $imgs) {
                        $img = wp_get_attachment_image_src($imgs,'large');
                        echo '<li><img src="'.$img[0].'" alt=""/></li>';
                    }
                }
            ?>
            </ul>
        </div>
        <?php
    } 
}