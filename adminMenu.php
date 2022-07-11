<?php

    require_once(plugin_dir_path(__FILE__)."/savePluginOptions.php");

    //enqueue style
    add_action('admin_menu','enqueue_admin_style');
    function enqueue_admin_style(){
        wp_enqueue_style('adminMenuStyle',plugin_dir_url( __FILE__ ).'css/adminMenuStyle.css',array(),'1.0.0');
    }
    
    //Add admin menu

    add_action('admin_menu','color_variation_plugin_setup_menu');
    
    function color_variation_plugin_setup_menu(){
        add_menu_page('Color variation plugin page', 'Color variation plugin','manage_options','color-variation-plugin','color_variation_init');
    }

    function color_variation_init(){

        $args = [
            'status'  => 'publish',
            'orderby' => 'name',
            'order'   => 'ASC',
            'limit'   => -1,
        ];

        $varProducts=array();
        foreach(wc_get_products($args) as $product){
            if ($product->is_type('variable')){            
                //array_push($varProducts,$product->name.'-'.$product->id);
                array_push($varProducts,array('prName'=>$product->name,'prId'=>$product->id));
              
            }
        }

        
        $html='<h1 class="colorVarAdminHeader"> Color variation plugin options</h1><br>'; 

        /**Dropdown to select products for display variation on product page */

        $html=$html.'<form class="admin-option-form" action="'. admin_url('admin-ajax.php')   .'?action=save_plugin_options" method="POST">';
        $html= $html.'<div class="labelSelect"><label for="product-id"> <h2>Select Variable products- Display variations on product page </h2></label><select name="productid[]" multiple size=6>';
        foreach($varProducts as $item){
            $html=$html.'<option value="'.esc_attr($item['prId']).'">'.$item['prName'].'</option>';
        }

        $html=$html.'</select></div>';

        $html=$html.'<div class="saveButtons"><input type="submit" class="saveOptions" value="Save Options" /><a class="resetOptions" href="'.admin_url('admin-ajax.php').'?action=reset_plugin_options">Reset Options</a></div>';
        $html=$html.'</form>';
        echo $html;
        echo '<br><br>';

        /**Dropdown to select products for display variation on product page */

        $html='<form class="admin-option-form" action="'. admin_url('admin-ajax.php')   .'?action=save_plugin_options_modal" method="POST">';
        $html= $html.'<div class="labelSelect"><label for="product-id"> <h2>Select Variable products- Display variations in modal pop-up </h2></label><select name="productid[]" multiple size=6>';
        foreach($varProducts as $item){
            $html=$html.'<option value="'.esc_attr($item['prId']).'">'.$item['prName'].'</option>';
        }

        $html=$html.'</select></div>';

        $html=$html.'<div class="saveButtons"><input type="submit" class="saveOptionsModal" value="Save Options" /><a class="resetOptionsModal" href="'.admin_url('admin-ajax.php').'?action=reset_plugin_options_modal">Reset Options</a></div>';
        $html=$html.'</form>';
        echo $html;
        
    }

?>