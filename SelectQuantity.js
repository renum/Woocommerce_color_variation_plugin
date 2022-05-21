document.addEventListener('DOMContentLoaded',()=>{


    varOuts=document.querySelectorAll('.var-out-container > input');
    varQtys=document.querySelectorAll('.qty');
    
    


    varQtys.forEach((varQty)=> {

        varQty.addEventListener("change",()=>{
                    jQuery(varQty).css('cursor','wait');   //show cursor as loading for the color for which quantity being updated
                    updateQtys(varQty);
        });

    });

    


});


function updateQtys(varQty){
  
    var match=false;
   // varOuts.forEach((varOut)=>{
    for(varOut of varOuts){
    
        //varOut.classList.forEach(   
        for(let entry of varOut.classList){  //Find which color quantity is selected and update it in the corresponding box
           // (entry)=>{ 
               if(varQty.classList.contains(entry)){
                   match=true;
                   break;   //break can't be used with forEach. changed to for loop instead of forEach

               }
               else{
                   match=false;
               }
       // })
        }
      
        if(match === true){
           //get stock quantity using ajax and only update quantity only if it is less than stock
           //get_stock_ajax(varQty,varOut);
           get_stock(varQty,varOut);
           break;
                     
        }
        
            
    
    //});
    }
}

//get stock passed in data attribute
function get_stock(varQty,varOut){
    var inpStock=parseInt(varQty.dataset.stock);
    jQuery(varQty).parent().parent().parent().find('.stock-error').remove(); //remove stock error message for this color
    if(inpStock >= varQty.value ){
        varOut.value = varQty.value;
        enable_cart_clear_btns();
    }
    else{
        varOut.value = '';
        alert('Quantity can not be updated. Stock is less than selected quantity.');                           
        let Stock_Message=document.createElement("div");
        jQuery(Stock_Message).addClass('stock-error');
        Stock_Message.textContent ='Only ' +inpStock+' in stock';                     
        varQty.parentElement.parentElement.parentElement.append(Stock_Message); //display stock error message for this color
      
        
       
    }
    jQuery(varQty).css('cursor','');
}


//Get stock from server through ajax request
function get_stock_ajax(varQty,varOut){
   
    var inpVarName=varQty.name;
    var inpPrId=varQty.dataset.id;
    jQuery.ajax( {type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            dataType: 'json',
            data : {action: "my_get_stock", inpVar: JSON.stringify(inpVarName),inpPr: JSON.stringify(inpPrId)}, //Pass product Id and variation number to get stock
            
            success: function( response ) {
                       
              
                if ( !response.error) {
                   
                    console.log(response);
                    jQuery(varQty).parent().parent().parent().find('.stock-error').remove(); //remove stock error message for this color
                    if(response.stock >= varQty.value ){
                        varOut.value = varQty.value;
                        enable_cart_clear_btns();
                      
                    }
                    else{
                        alert('Quantity can not be updated. Stock is less than selected quantity.');                           
                        let Stock_Message=document.createElement("div");
                        jQuery(Stock_Message).addClass('stock-error');
                        Stock_Message.textContent ='Only ' +response.stock+' in stock';                     
                        varQty.parentElement.parentElement.parentElement.append(Stock_Message); //display stock error message for this color
                      
                        
                       
                    }
                  
                    
                }
                jQuery(varQty).css('cursor','');  //remove loading cursor after quantity updated
            } , 
            error: function( response ) {
                    jQuery(varQty).parent().parent().parent().find('.stock-error').remove(); //remove stock error message for this color
                    console.log(response);
                    alert('Error! Quantity not updated.');
                    jQuery(varQty).css('cursor','');       //remove loading cursor      
                   
                
            } , 
    });
   
   
}


function enable_cart_clear_btns(){
    if(jQuery('.reset_variations').css('visibility') =='hidden'){
        jQuery('.reset_variations').css('visibility','visible');
    
    }

    if(jQuery('.single_add_to_cart_button').hasClass('disabled')){
        jQuery('.single_add_to_cart_button').removeClass('disabled');
    }
}