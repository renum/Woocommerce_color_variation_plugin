<?php



    add_action("wp_ajax_my_get_stock","my_get_stock");
    add_action("wp_ajax_nopriv_my_get_stock","my_get_stock");


    function my_get_stock(){
        require_once("../wp-load.php");
        global $product;
        global $woocommerce;

        //get product id from ajax request
        $inpPr=json_decode(stripslashes($_REQUEST["inpPr"]),true);   
        $inpPr=str_replace("\n","",$inpPr);

        //get product from product id
        $_pf=new WC_Product_Factory();
        $product= $_pf->get_product((int) $inpPr);

        //get variation id from ajax request
        $inpVar=json_decode(stripslashes($_REQUEST["inpVar"]),true);
        $inpVar=str_replace("\n","",$inpVar);
        $varName=(int) $inpVar; 
        
      
        //get all product variations
        $vars = $product->get_available_variations();
        
       

        foreach($vars as $var){
            $id=(int)$var["variation_id"];
            if($id == $varName){
                $result['stock']=$var['max_qty'];  //pull the stock for the requested variation id

            }
        }
       
      
       $result=json_encode($result);
       echo $result;
       
 
        die();
    }

    



?>