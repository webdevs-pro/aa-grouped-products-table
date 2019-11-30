jQuery(document).ready(function($) {

   $('.grouped-product-qtt_button.qtt_minus').click(function() {
      var val = $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val();
      if (val > 0) {
         val--;
         $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val(val);
         $(this).closest('.add_to_cart').find('.add_to_cart_button').attr('data-quantity', val);
      }
   });

   $('.grouped-product-qtt_button.qtt_plus').click(function() {
      var val = $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val();
      if (val < 1000) {
         val++;
         $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val(val);
         $(this).closest('.add_to_cart').find('.add_to_cart_button').attr('data-quantity', val);
      }
   });

      
   // open popup
   $('.aa_order_product_form').click(function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      var popup = ''+
         '<div class="aa_order_product_popup">'+
            '<div class="aa_order_product_popup_loader"></div>'+
            '<div class="aa_close_popup"><i aria-hidden="true" class="fas fa-window-close"></i></div>'+
            '<iframe class="aa_popup_iframe" src="'+url+'"/>'+
         '</div>'+
      '';
      $('body').addClass('aa_order_popup_opened').append(popup);
   
   });

   // close popup
   $('.aa_close_popup').live('click', function() {
      $('body').removeClass('aa_order_popup_opened');
      $('.aa_order_product_popup').remove();
   });
   









});