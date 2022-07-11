<?php
     add_action("wp_ajax_reset_plugin_options","reset_plugin_options");
     add_action("wp_ajax_nopriv_reset_plugin_options","reset_plugin_options");



     function reset_plugin_options(){
      require_once("../wp-load.php");
        //var_dump(get_option('varProductIds'));
        delete_option('varProductIds'); 
        //update_option("varProductIds",'xxx' );
        wp_redirect(admin_url('?page=color-variation-plugin'));
        exit();
        
     }


     add_action("wp_ajax_reset_plugin_options_modal","reset_plugin_options_modal");
     add_action("wp_ajax_nopriv_reset_plugin_options_modal","reset_plugin_options_modal");



     function reset_plugin_options_modal(){
      require_once("../wp-load.php");
        //var_dump(get_option('varProductIds'));
        delete_option('varProductIds'); 
        //update_option("varProductIds",'xxx' );
        wp_redirect(admin_url('?page=color-variation-plugin'));
        exit();
        
     }

    

?>