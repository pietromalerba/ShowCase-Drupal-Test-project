<?php
// $Id: views-view-unformatted.tpl.php,v 1.6 2008/10/01 20:52:11 merlinofchaos Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php 
  // dsm($rows);
  // dsm($view);
  // Get all the flags for the user.
  $flagged = flag_get_user_flags('node');
  // dsm($flagged);

  // Build a grid that we can use for our table.
  foreach ($rows as $id => $row) {
    $results[$view->result[$id]->node_data_field_session_day_field_session_day_value][$view->result[$id]->node_data_field_session_room_field_session_time_value][$view->result[$id]->node_data_field_session_day_field_session_room_value] = array(
      'content' => $row,
      'nid' => $view->result[$id]->nid,
      'id' => 'node-'. $view->result[$id]->nid,
    );
  }
  dsm($results);
  // Sorting so that time goes ASC.
  ksort($results['saturday']);  
  ksort($results['sunday']);

  // Gather the room cck field values for the table headers
  $field_rooms = content_fields('field_session_room');
  $allowed = content_allowed_values($field_rooms);
  $saturday_header = array(
    $allowed[1], $allowed[2], $allowed[3], $allowed[4], $allowed[5], $allowed[8]
  );
  
  $sunday_header = array(
    $allowed[2], $allowed[3], $allowed[4], $allowed[5], $allowed[8]
  );
  
  
  // Add some js magic to catch a vote request and then update the td class
  drupal_add_js("$(document).ready(function() {
    $(window).bind('flagGlobalAfterLinkUpdate', function(e, data) {
      var selector = '#node-' + data.contentId;
      if (data.flagStatus == 'flagged') {
        $(selector).addClass('flagged').removeClass('not-flagged');
      }
      else {
        $(selector).addClass('not-flagged').removeClass('flagged');
      }
    });
  });", 'inline');

// dsm($results);
?>

<?php if (user_access('manage schedule')): ?>
  <div class="manage-link"><?php echo l('Manage Schedule', 'manage/schedule', array('query' => 'destination=schedule')); ?></div>
<?php endif; ?>

<h3>Saturday</h3>
<?php
  $tr = array();
  foreach ($results['saturday'] as $time => $item) {
    if (is_numeric($time)) {
      $tr[] = array(
        array('data' => $item[1]['content'], 'id' => $item[1]['id'], 'class' => $flagged['vote'][$item[1]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[2]['content'], 'id' => $item[2]['id'], 'class' => $flagged['vote'][$item[2]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[3]['content'], 'id' => $item[3]['id'], 'class' => $flagged['vote'][$item[3]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[4]['content'], 'id' => $item[4]['id'], 'class' => $flagged['vote'][$item[4]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[5]['content'], 'id' => $item[5]['id'], 'class' => $flagged['vote'][$item[5]['nid']] ? 'flagged' : 'not-flagged'),
        // array('data' => $item[6]['content'], 'id' => $item[6]['id'], 'class' => $flagged['vote'][$item[6]['nid']] ? 'flagged' : 'not-flagged'),
        // array('data' => $item[7]['content'], 'id' => $item[7]['id'], 'class' => $flagged['vote'][$item[7]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[8]['content'], 'id' => $item[8]['id'], 'class' => $flagged['vote'][$item[8]['nid']] ? 'flagged' : 'not-flagged'),
      );      
    }
  }
  print theme('table', $saturday_header, $tr);
?>

<h3>Sunday</h3>
<?php
  $tr = array();
  foreach ($results['sunday'] as $time => $item) {
    if (is_numeric($time)) {
      $tr[] = array(
        array('data' => $item[2]['content'], 'id' => $item[2]['id'], 'class' => $flagged['vote'][$item[2]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[3]['content'], 'id' => $item[3]['id'], 'class' => $flagged['vote'][$item[3]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[4]['content'], 'id' => $item[4]['id'], 'class' => $flagged['vote'][$item[4]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[5]['content'], 'id' => $item[5]['id'], 'class' => $flagged['vote'][$item[5]['nid']] ? 'flagged' : 'not-flagged'),        
        // array('data' => $item[6]['content'], 'id' => $item[6]['id'], 'class' => $flagged['vote'][$item[6]['nid']] ? 'flagged' : 'not-flagged'),
        // array('data' => $item[7]['content'], 'id' => $item[7]['id'], 'class' => $flagged['vote'][$item[7]['nid']] ? 'flagged' : 'not-flagged'),
        array('data' => $item[8]['content'], 'id' => $item[8]['id'], 'class' => $flagged['vote'][$item[8]['nid']] ? 'flagged' : 'not-flagged'),
      );      
    }
  }
  print theme('table', $sunday_header, $tr);
?>