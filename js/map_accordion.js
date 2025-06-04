(function ($, Drupal) {
  Drupal.behaviors.myCustomBehavior = {
    attach: function (context, settings) {
        
        $(context).once('myCustomBehavior').each(function () {
            console.log('Custom JS is loaded once');
            // Your JavaScript code goes here
            
        });
        
    }
    
  }
})(jQuery, Drupal);
