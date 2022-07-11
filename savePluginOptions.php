<?php
     add_action("wp_ajax_save_plugin_options","save_plugin_options");
     add_action("wp_ajax_nopriv_save_plugin_options","save_plugin_options_non_admin");



     function save_plugin_options(){

         if(is_admin()){
            require_once("../wp-load.php");
            
            $productIds=get_option("varProductIds");
            if(isset($_POST['productid'])){
                  foreach ($_POST['productid'] as $prid){
                  
                     $productid=$prid;
                  
                     if (!empty($productIds)){
                        $productIds=$productIds.','.$productid;
                     }
                     else{
                        $productIds=$productIds.$productid;
                     }
                     update_option("varProductIds",$productIds );
                  
                  }
            }
            else{
                  echo 'Nothing is selected';
            }
            wp_redirect(admin_url('?page=color-variation-plugin'));
            exit();
         }

         else{
            echo 'Security- Not logged in as admin';
         }
        
     }

     function save_plugin_options_non_admin(){
        echo 'Security- Not logged in as admin';
     }

     add_action("wp_ajax_save_plugin_options_modal","save_plugin_options_modal");
     add_action("wp_ajax_nopriv_save_plugin_options_modal","save_plugin_options_modal_non_admin");



     function save_plugin_options_modal(){
         if(is_admin()){
            require_once("../wp-load.php");
            
            $productIds=get_option("varProductIds");
            if(isset($_POST['productid'])){
                  foreach ($_POST['productid'] as $prid){
                  
                     $productid=$prid;
                  
                     if (!empty($productIds)){
                        $productIds=$productIds.','.$productid;
                     }
                     else{
                        $productIds=$productIds.$productid;
                     }
                     update_option("varProductIdsModal",$productIds );
                  
                  }
            }
            else{
                  echo 'Nothing is selected';
            }
            wp_redirect(admin_url('?page=color-variation-plugin'));
            exit();
         }
         else{
            echo 'Security- Not logged in as admin';
         }

        
     }

     function save_plugin_options_modal_non_admin(){
      echo 'Security- Not logged in as admin';
   }


    


?>