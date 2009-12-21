<?php
// $Id: views-view.tpl.php,v 1.13 2009/06/02 19:30:44 merlinofchaos Exp $
/**
 * @file views-view.tpl.php
 * Main view template
 *
 * Variables available:
 * - $css_name: A css-safe version of the view name.
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 * - $admin_links: A rendered list of administrative links
 * - $admin_links_raw: A list of administrative links suitable for theme('links')
 *
 * @ingroup views_templates
 */
 
  // Handle scrollables.
  drupal_add_js("Drupal.behaviors.featured_attendees = function() {
    var speed = 'slow';
    // Next
    $('#scroller .next').click(function() {
      $('#scroller .views-row-first').removeClass('views-row-first')
        .next().addClass('views-row-first').hide().fadeIn(speed)
        .prev().appendTo('#scroller .items');
    });
    // Previous
    $('#scroller .prev').click(function() {
      $('#scroller .items .views-row:last-child').prependTo('#scroller .items').addClass('views-row-first').hide().fadeIn(speed)
        .next().removeClass('views-row-first');
    });
    // Click on a not-first image.
    $('#scroller .views-row:not(.views-row-first)').live('click', function() {
      $(this).prevAll().appendTo('#scroller .items').removeClass('views-row-first');
      $(this).addClass('views-row-first').hide().fadeIn(speed);
    });
    // Show name on not-first hover.
    $('#scroller .views-field-picture-2').live('mouseover', function() {
      $(this).after('<div class=\"highlight\"></div>');
      $('.highlight').hide().fadeIn(speed);
      $('.hover-name').hide().html($(this).parent().find('.views-field-value-1 .field-content a').html()).fadeIn(speed);
    });
    // Remove name after hover out.
    $('#scroller .views-field-picture-2').live('mouseout', function() {
      $('.hover-name').empty();
      $('.highlight').remove();
    });
  }
  ",'inline');
?>
<div id="scroller" class="view view-<?php print $css_name; ?> view-id-<?php print $name; ?> view-display-id-<?php print $display_id; ?> view-dom-id-<?php print $dom_id; ?>">
  <?php if ($admin_links): ?>
    <div class="views-admin-links views-hide">
      <?php print $admin_links; ?>
    </div>
  <?php endif; ?>
  
  <!-- prev link --> 
  <?php if ($rows): ?>
    <div class="hover-name"></div>
    <div class="scrollable">
      <div class="view-content items">
        <?php print $rows; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- prev link --> 
  <a class="prev">< Prev</a> 
  <a class="next">Next ></a> 


</div> <?php /* class view */ ?>
