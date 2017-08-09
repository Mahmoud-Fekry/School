/*global $, jQuery, alert, confirm*/

$(function () {

    'use strict';
    
    // Define all variable


    /* Start dashboard page */

    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next().fadeToggle();
        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
        else{
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }

    });


    
    // Trigger Select Box plugin
    
    $("select").selectBoxIt({
        
        autoWidth: false
    });
    
    
    //Hide placeholder on form fouce
    
    $('[placeholder]').focus(function () {
        
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    
    
    //Add astrisk to requerd field
    
    $('input').each(function () {
        
        if ($(this).attr('required') === 'required') {
            
            $(this).after('<span class="asterisk">*</span>');
        }
    });
    
    
    //Confirm deleting any user
    
    $('.confirm').click(function () {
        
        return confirm('Are you sure about deleting !!');
    });
    
   
});