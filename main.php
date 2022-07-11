<?php
/*
Plugin Name: Woocommerce color variation Plugin
Plugin URI:
Description: Customizes product page
Version: 1.0
Author: Renu Mehta
Tested up to: 5.7
Author URI:
License: GPL2
*/


//Add the php for add to cart ajax actions for custom add to cart -variable products
require_once(plugin_dir_path(__FILE__)."/adminMenu.php");
require_once(plugin_dir_path(__FILE__)."/customAddToCart.php"); 
require_once(plugin_dir_path(__FILE__)."/resetPluginOptions.php");
//require_once(plugin_dir_path(__FILE__)."/getStock.php"); 



//Enqueue scripts

add_action('woocommerce_before_single_product','Custom_Product_Page_Scripts');

function Custom_Product_Page_Scripts(){
  global $product,$id;
  $product=wc_get_product();
  checkcolorVarProduct();
  global $colorVarProduct;
 
 
  //Only enqueue these scripts and styles for variable products
  
  if( $product->is_type( 'variable' )  && $colorVarProduct){
  
    wp_enqueue_style('CustomProductPage',plugin_dir_url( __FILE__ ).'css/customProductPage.css',array(),'1.0.0');

    
    wp_register_script('SelectQuantity',plugin_dir_url( __FILE__ ).'js/selectQuantity.js',array('jquery'));
    wp_localize_script( 'SelectQuantity', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('SelectQuantity',plugin_dir_url( __FILE__ ).'js/selectQuantity.js',array('jquery'),'1.0.0');
      
    wp_register_script( "CustomAddToCart", plugin_dir_url(__FILE__).'js/customAddToCart.js', array('jquery') );
    wp_localize_script( 'CustomAddToCart', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('CustomAddToCart',plugin_dir_url( __FILE__ ).'js/customAddToCart.js',array('jquery'),'1.0.0');

    wp_register_script( "disableAddtoCart", plugin_dir_url(__FILE__).'js/disableAddToCart.js', array('jquery') );
    wp_localize_script( 'disableAddtoCart', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('disableAddtoCart',plugin_dir_url( __FILE__ ).'js/disableAddToCart.js',array('jquery'),'1.0.0');

    wp_register_script( "resetQuantity", plugin_dir_url(__FILE__).'js/resetQuantity.js', array('jquery') );
    wp_localize_script( 'resetQuantity', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('resetQuantity',plugin_dir_url( __FILE__ ).'js/resetQuantity.js',array('jQuery'),'1.0.0');

  }
 
 
}

//Hide quantity field for eligible products
//Hide select for variations
/**clear variations button styling**/


add_action('woocommerce_before_single_product','quantity_wp_head');
function quantity_wp_head(){
  global $product;
  checkcolorVarProduct();
  global $colorVarProduct;
  

    if( $product->is_type( 'variable' )  && $colorVarProduct== true){

      ?>
      <style type="text/css">
        .woocommerce-variation-add-to-cart .quantity { display: none !important;} 
        .woocommerce form.cart .variations select {display:none !important;}
        .woocommerce form.cart a.reset_variations{
        background-color:#dd3333 !important;
        display:inline-block !important;
        color:white !important;
        font-weight: 700 !important;
        vertical-align: center;
        border-radius:3px;
        padding:0.25em 1em;}
      </style>
      <?php

    }
}

//remove dropdown for variations and add color qty display fields
  
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args) {

  global $product;
  checkcolorVarProduct();
  global $colorVarProduct;
  

    if( $product->is_type( 'variable' )  && $colorVarProduct== true){
   
    $vars = $product->get_available_variations();
    $html=$html.'<div class="var-out-container">';
  
    foreach($vars as $var){
      $id=$var["variation_id"];
      $color= $var["attributes"]["attribute_color"];
      $html=$html.'<input value="" name="'.esc_attr($id). '" style="background-color:'.esc_attr($color).'" class="tot-qty '.esc_attr($color).'" type="number" readonly ></input>';
     
     
    }
  
    $html=$html.'</div>';
  
   
  }
  return $html;
}




/***Add separate input qty field for each variation */
add_action('woocommerce_after_single_product_summary','add_var_qty');
function add_var_qty(){

  global $product;
  checkcolorVarProduct();
  global $colorVarProduct;
  
    if( $product->is_type( 'variable' )  && $colorVarProduct){
    $vars = $product->get_available_variations();
    $prid=get_the_id();
    echo '<div class="var-input-container">';
    foreach($vars as $var){
     
      $id=$var["variation_id"];
      $color= $var["attributes"]["attribute_color"];
      $stock=$var['max_qty'];
      
     echo '<div class="var-input-wrapper"><div class="var-input" style="background-color:'.esc_attr($color).';"><p>'.esc_attr($color).'</p><div class="var-qty"><input name="'.esc_attr($id). '" data-id="'.esc_attr($prid).'" data-stock="'. esc_attr($stock).'" class="qty '.esc_attr($color).'" type="number" min="0" max="'.esc_attr($stock).'"></input></div></div></div>';
    // Input select fields for quantity will show a maximum number=stock available for that variation. But any number greater than that limit can be entered manually. This will
    // be taken care in SelectQuantity.js script which will fetch stock before updating quantity.
    }
    echo '</div>';

  }


}


//add to cart message

add_filter('wc_add_to_cart_message_html','add_message');
function add_message(){
  $message='Your products have been added to cart';
  return $message;
}

//*** Remove availability of stock message */

add_filter( 'woocommerce_get_stock_html', 'my_remove_stock_messages', 10, 2 );
function my_remove_stock_messages( $html, $product ) {
  
  global $product;
  checkcolorVarProduct();
  global $colorVarProduct;
      if( $product->is_type( 'variable' )  && $colorVarProduct){
        //$view_variable='this is a variable product';
        //console_log($view_variable);   //To display php output on console
        $html = '';	 	 
    }	 	 
    return $html;	 	 
}



/**Display debug info in console */
function console_log($output,$with_script_tag=true){

  $js_code='console.log('.json_encode($output,JSON_HEX_TAG).');';
  if($with_script_tag){
    $js_code = '<script>'.$js_code.'</script>';

  }
  echo esc_js($js_code);
}



//Refresh segments

//add_action('wp_enqueue_scripts', 'refreshfragments');  //Enqueue the script to refresh the cart widget 
function refreshfragments(){
  wp_enqueue_script('refreshFragments',plugin_dir_url( __FILE__ ).'refreshFragments.js',array('jquery'),'1.0.0');
  
}


function checkcolorVarProduct(){
  global $product;
  global $colorVarProduct;
  $productIds=explode(',',get_option('varProductIds'));
 
  $colorVarProduct=false;
  foreach ($productIds as $productId){
    if($productId == $product->get_id()){
      $colorVarProduct=true;
      break;
    }
  }

}



?>
 
