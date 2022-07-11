

jQuery(document).ready( function() {
   
    jQuery(".single_add_to_cart_button").click( function(e) {
        
      
       e.preventDefault(); 
       jQuery(".single_add_to_cart_button").addClass( 'loading' );
       var inpArr={};
       jQuery(".tot-qty").each
       (function(index,element){
        //   console.log(jQuery(element).val());
           key= String(element.name);
           console.log(key);
           keyval= (jQuery(element).val()>0?jQuery(element).val():0);
           //inpArr.push({[key]:keyval});
           if(keyval > 0){
            inpArr[key]=keyval;
           }
          
          
       });
       console.log(Object.keys(inpArr).length);             


      // console.log(inpArr);
       if (Object.keys(inpArr).length > 0) {
            jQuery.ajaxSetup ({
                cache: false
                });
            // jQuery( document.body ).trigger( 'adding_to_cart', [ jQuery(".single_add_to_cart_button"), data ] );

            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : myAjax.ajaxurl,
                dataType: 'json',
                data : {action: "my_custom_add_to_cart", inpArr: JSON.stringify(inpArr)},
                /*success: function (response) { 
                    //console.log(response.pr_count);
                    jQuery('.cart-contents .count').html(response.pr_count);
                    jQuery('.cart-contents .amount-cart').html(response.pr_price);
                    // Remove existing notices
                    jQuery( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();	
                    // Add new notices
                    jQuery('.woocommerce-notices-wrapper:first').prepend(response.notices);
                    jQuery( document.body ).trigger( 'wc_fragment_refresh' );  ///Updates the cart widget on header and in sidebar
                    console.log('success');
                    console.log(response);
                }, 
                error:function(response){
                // console.log(response.pr_count);
                console.log('error');
                console.log(response);
                
                    
                }

                */

                success: function( response ) {
                    if ( ! response ) {
                        return;
                    }
                    
                    // Redirect after error (optional), errors will be displayed on the product page
                    if ( response.error && response.product_url ) {
                        window.location = response.product_url;
                        return;
                    }

                    if (response.error) {
                    // refresh_notices();
                    // Remove existing notices
                        jQuery( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();	
                        // Add new notices
                        jQuery('.woocommerce-notices-wrapper:first').prepend(response.notices);
                        jQuery( document.body ).trigger( 'wc_notices_refreshed' );

                        jQuery(".single_add_to_cart_button").removeClass( 'loading' );	
                        return; // to prevent firing the added_to_cart trigger
                    }

                    // Redirect to cart option (adjusted so it will not redirect after error)
                    if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' && !response.error) {
                        window.location = wc_add_to_cart_params.cart_url;
                        return;
                    }
                    
                    // display notices on success?					

                    // Trigger event so themes can refresh other areas (adjusted so it will not trigger after error).
                    if ( !response.error) {
                        jQuery('.cart-contents .count').html(response.pr_count);
                        jQuery('.cart-contents .amount-cart').html(response.pr_price);
                        jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, jQuery(".single_add_to_cart_button") ] );
                        // Remove existing notices
                        jQuery( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();	
                        // Add new notices
                    
                        jQuery('.woocommerce-notices-wrapper:first').prepend('<div class="woocommerce-info">'+response.notices+'</div>');
                        jQuery( document.body ).trigger( 'wc_notices_refreshed' );
                        jQuery( document.body ).trigger( 'wc_fragment_refresh' );
                        jQuery(".single_add_to_cart_button").removeClass( 'loading' );
                        resetQuantity();	
                        console.log(response);
                    }
                    
                },
            });
           

        }
        else{
             // Remove existing notices
             jQuery( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();	
            jQuery('.woocommerce-notices-wrapper:first').prepend('<div class="woocommerce-error">No Variations selected</div>');
            disableAddtoCart();
            jQuery(".single_add_to_cart_button").removeClass( 'loading' );	

        }
    });
 });