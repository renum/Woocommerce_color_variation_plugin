<?php



    add_action("wp_ajax_my_custom_add_to_cart","my_custom_add_to_cart");
    add_action("wp_ajax_nopriv_my_custom_add_to_cart","my_custom_add_to_cart");


    function my_custom_add_to_cart(){
        require_once("../wp-load.php");
        global $product;
        global $woocommerce;
       // echo 'this is custom add to cart';
        $Arr=json_decode(stripslashes($_REQUEST["inpArr"]),true);
        $Arr=str_replace("\n","",$Arr);
       // print_r($Arr);

        foreach($Arr as $index=>$val){
           // echo $index ;
           // echo $val;
            
            
            wc()->cart->add_to_cart((int)$index,(int)$val);
           
            
       }

       $result['pr_count']= WC()->cart->get_cart_contents_count();
       $result['pr_price']= WC()->cart->get_cart_total();
       $result['notices'] = wc_print_notices(true) ;
       if ($result['notices']=== ''){
          
            $result['notices']=$product['title'].' added to cart successfully ';
       }
        //add_action('wc_ajax_get_notices', 'get_notices');  

        function get_notices(){
            $result['notices'] = wc_print_notices(true) ;
            //wp_send_json( $result );
           // wc_clear_notices();
        };

       $result=json_encode($result);
       echo $result;
       
 
       


        die();
    }

    



?>

