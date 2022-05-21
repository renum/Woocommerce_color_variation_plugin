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
require_once(plugin_dir_path(__FILE__)."/customaddtocart.php"); 
require_once(plugin_dir_path(__FILE__)."/getstock.php"); 


//Enqueue scripts

add_action('woocommerce_before_single_product','Custom_Product_Page_Scripts');

function Custom_Product_Page_Scripts(){
  global $product,$id;
  $product=wc_get_product();
 
  //Only enqueue these scripts and styles for variable products

  if( $product->is_type( 'variable' ) ){
  
    wp_enqueue_style('CustomProductPage',plugin_dir_url( __FILE__ ).'CustomProductPage.css',array(),'1.0.0');
    
    wp_register_script('SelectQuantity',plugin_dir_url( __FILE__ ).'SelectQuantity.js',array('jquery'));
    wp_localize_script( 'SelectQuantity', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('SelectQuantity',plugin_dir_url( __FILE__ ).'SelectQuantity.js',array('jquery'),'1.0.0');
      
    wp_register_script( "CustomAddToCart", plugin_dir_url(__FILE__).'CustomAddToCart.js', array('jquery') );
    wp_localize_script( 'CustomAddToCart', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('CustomAddToCart',plugin_dir_url( __FILE__ ).'CustomAddToCart.js',array('jquery'),'1.0.0');

    wp_register_script( "resetQuantity", plugin_dir_url(__FILE__).'resetQuantity.js', array('jquery') );
    wp_localize_script( 'resetQuantity', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('resetQuantity',plugin_dir_url( __FILE__ ).'resetQuantity.js',array('jQuery'),'1.0.0');

  }
 
 
}

add_action('wp_enqueue_scripts', 'refreshfragments');  //Enqueue the script to refresh the cart widget 
function refreshfragments(){
  wp_enqueue_script('refreshFragments',plugin_dir_url( __FILE__ ).'refreshFragments.js',array('jquery'),'1.0.0');
  
}

//*** Remove availability of stock message */

add_filter( 'woocommerce_get_stock_html', 'my_remove_stock_messages', 10, 2 );
function my_remove_stock_messages( $html, $product ) {	 	 
    if ( $product->is_in_stock() ) {
        $html = '';	 	 
    }	 	 
    return $html;	 	 
}


//remove dropdown for variations and add color qty display fields
  
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args) {
  global $product;
 
  if( $product->is_type( 'variable' ) ){
   
    $vars = $product->get_available_variations();
    $html=$html.'<div class="var-out-container">';
    //$var_arr=array();
    foreach($vars as $var){
      $id=$var["variation_id"];
      $color= $var["attributes"]["attribute_color"];
      $html=$html.'<input name="'.$id. '" style="background-color:'.$color.'" class="tot-qty '.$color.'" type="number" readonly ></input>';
     
    }
  
    $html=$html.'</div>';
    //Another way to add php action to a button without using javascript or jquery
    //$link=admin_url('admin-ajax.php?action=my_custom_add_to_cart&inpArr='.json_encode($var_arr)); //create a new button for add to cart and add ajax action to it.
    //$html= $html.'<a class="cart-link" href='.$link.'>Add to Cart</a>';
    return $html;
  }
}




/***Add separate input qty field for each variation */
add_action('woocommerce_after_single_product_summary','add_var_qty');
function add_var_qty(){

  global $product;
  if( $product->is_type( 'variable' ) ){
    $vars = $product->get_available_variations();
    $prid=get_the_id();
    echo '<div class="var-input-container">';
    foreach($vars as $var){
     
      $id=$var["variation_id"];
      $color= $var["attributes"]["attribute_color"];
      $stock=$var['max_qty'];
      
     echo '<div class="var-input-wrapper"><div class="var-input" style="background-color:'.$color.';"><p>'.$color.'</p><div class="var-qty"><input name="'.$id. '" data-id="'.$prid.'" data-stock="'. $stock.'" class="qty '.$color.'" type="number" min="0" max="'.$stock.'"></input></div></div></div>';
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

 
