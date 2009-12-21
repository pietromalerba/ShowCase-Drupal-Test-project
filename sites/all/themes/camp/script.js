/**
 * Catch flag updates and update our count divs.
 */
Drupal.behaviors.flag_update = function() {
  $(window).bind('flagGlobalAfterLinkUpdate', function(e, data) {
    if ( ! e.stop) { // Use this method or you will get multiple updates (the event will fire more than once per click...)
      e.stop = true;
      var selector = '#node-' + data.contentId + ' .vote-count';
      var number = Number($(selector).html());
      if (data.flagStatus == 'flagged') {
        $(selector).html(number+1);
      }
      else {
        $(selector).html(number-1);
      }
    }
  });
}


/**
 * Because we are using the better select module, we modify its init function
 * because we are using jquery update to get us to jquery 1.3x
 */
Drupal.behaviors.initBetterSelect = function(context) {
  $('.better-select .form-checkboxes input[type="checkbox"]').click(function(){
    this.checked ? $(this).parent().parent().addClass('hilight') : $(this).parent().parent().removeClass('hilight');
  }).filter(":checked").parent().parent().addClass('hilight');
}
