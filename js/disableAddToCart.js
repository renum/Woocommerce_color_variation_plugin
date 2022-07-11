//Load the page with disabled add to cart button. Enable only if any variation selected.

//document.addEventListener('wc_product_gallery_after_init',()=>{

function disableAddtoCart(){
    console.log('disable add to cart script');
    varOuts=document.querySelectorAll('.var-out-container > input');
    cartButton=document.querySelector('.single_add_to_cart_button');
    variationAddToCart=document.querySelector('.woocommerce-variation-add-to-cart');
    resetVariationButton=document.querySelector('.reset_variations');

    console.log(resetVariationButton.style.visibility);
    resetVariationButton.style.visibility="hidden";
    cartButton.classList.remove("wc-variation-is-unavailable");
   cartButton.classList.add("disabled", "wc-variation-selection-needed");
    variationAddToCart.classList.remove("woocommerce-variation-add-to-cart-enabled");
    variationAddToCart.classList.add("woocommerce-variation-add-to-cart-disabled");

  
    console.log(resetVariationButton.style.visibility);
    console.log(cartButton);
    console.log(variationAddToCart);
    for(varOut of varOuts){
        //console.log(varOut);
        if(varOut.value.trim().length !==0 ){
            console.log('enabling button'+ varOut.value);
           /* cartButton.classList.remove("disabled","wc-variation-selection-needed" );
            variationAddToCart.classList.remove("woocommerce-variation-add-to-cart-disabled");
            variationAddToCart.classList.add("woocommerce-variation-add-to-cart-enabled");
            */
            resetVariationButton.style.visibility="visible";
            break;

        }
    }
    console.log(resetVariationButton);
    console.log(cartButton);
    console.log(variationAddToCart);
}
export function disableAddtoCart();
   
//}


//);
