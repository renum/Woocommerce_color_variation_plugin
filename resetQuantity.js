jQuery(document).ready(function(){
    jQuery(".reset_variations").click(function(e){
        e.preventDefault();
        console.log('this is reset quantity');
        jQuery(".var-out-container , .var-input-container").find('input').val('');
    
    });
}); 