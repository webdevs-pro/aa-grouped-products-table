jQuery(document).ready(function($) {

   $('.grouped-product-qtt_button.qtt_minus').click(function(){
      var val = $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val();
      if (val > 0) {
         val--;
         $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val(val);
         $(this).closest('.add_to_cart').find('.add_to_cart_button').attr('data-quantity', val);
      }
   });

   $('.grouped-product-qtt_button.qtt_plus').click(function(){
      var val = $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val();
      if (val < 1000) {
         val++;
         $(this).closest('.grouped-product-qtt').find('.grouped-product-qtt_count').val(val);
         $(this).closest('.add_to_cart').find('.add_to_cart_button').attr('data-quantity', val);
      }
   });

      
      
     
     








});