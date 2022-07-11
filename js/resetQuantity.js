
 jQuery(document).ready(function(){
    jQuery(".reset_variations").click(function(e){

        resetQuantity();

    });

});

function resetQuantity(){
   
        jQuery(".var-out-container , .var-input-container").find('input').val('');
    
   
}

