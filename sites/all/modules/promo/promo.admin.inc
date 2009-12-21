<?php

/**
 * @file
 * This file contains all the administrative menu/form callbacks.
 */

/**
 * Settings form
 */
function promo_dashboard() {
  // How many items to show
  $list_size = 25;
  
  drupal_add_css(drupal_get_path('module', 'promo') .'/promo.css', 'module');
  drupal_add_js(drupal_get_path('module', 'promo') .'/promo.js', 'module');
  
  $header = array(
    array('data' => 'Title', 'field' => 'title', 'sort' => 'asc'),
    array('data' => 'Link'),
    'Status',
    '',
  );
  
  $sql = "SELECT * FROM {promo}";
  $sql .= tablesort_sql($header);
  $query = pager_query($sql, $list_size, 0);
  
  while ($row = db_fetch_object($query)) {
    $tr[] = array(
      l($row->name, 'admin/content/promo/'. $row->pid .'/edit', array('class' => 'promo-link')),
      $row->link,
      $row->status ? 'Active' : 'Inactive',
      l(t('Edit'), 'admin/content/promo/'. $row->pid .'/edit'),
      l(t('Delete'), 'admin/content/promo/'. $row->pid .'/delete'));
    // $output .= '<div id="promo-'. $row->pid .'" class="promo-image">';
    // $output .= '</div>';
  }
  $output .= '<fieldset><legend>Promo Help</legend>';
  $output .= _promo_help_text();
  $output .= '</fieldset>';
  $output .= theme('table', $header, $tr);
  $output .= theme('pager');
  return $output;
}

/**
 * Node local task form to manage promo image and alt text
 *
 * @param string $type The type parameter can have 3 values:
 * 'add' => Creating a new promo
 * 'node' => Creating a promo for a node
 * 'admin' => Creating a promo for external link
 *
 * @param object $node The node object if $type == 'node'
 */
function promo_form(&$form_state, $type = 'add', $promo_id = NULL, $node = NULL) {
  // Check arguments and handle for errors
  if ($type == 'admin'){
    if ( ! is_numeric($promo_id)) {
      drupal_set_message(t('Could not find promo with id = @id', array('@id' => $promo_id)));
      drupal_goto('admin/content/promo');
    }
  }

  // Load default values
  if ($node) {
    $query = db_query("SELECT * FROM {promo} WHERE link = 'node/%d'", $node-nid);
  }
  else if ($promo_id){
    $query = db_query("SELECT * FROM {promo} WHERE pid = %d", $promo_id);
  }
  if ($query) {
    $promo = db_fetch_object($query);
  }
  // Enable file uploads
  $form['#attributes']['enctype'] = 'multipart/form-data';

  // Store values for reinsertion
  $form['type'] = array('#type' => 'value', '#value' => $type);
  // Set this first because we can override it below if we retrieved an actual result with an nid from the db
  if ($node) {
    $form['nid'] = array('#type' => 'value', '#value' => $node->nid);
  }
  // If we retreived a result from the db, set default values
  if ($promo) {
    $form['pid'] = array('#type' => 'value', '#value' => $promo->pid);
    $form['filepath'] = array('#type' => 'value', '#value' => $promo->filepath);
    if ($promo->nid) {
      $form['nid'] = array('#type' => 'value', '#value' => $promo->nid);
    }
  }
  
  $form['promo'] = array(
    '#type' => 'fieldset',
    '#title' => t('Promo Settings'),
  );
  $form['promo']['status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable promo'),
    '#default_value' => $promo->status,
    '#description' => t('Promos can be enabled/disabled for your convenience.')
  );
  $form['promo']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#default_value' => $promo->name,
    '#required' => TRUE,
    '#description' => 'The title is used as a descriptor in the block admin forms.  This helps you distinguish which block is for which promo.'
  );
  $form['promo']['image'] = array(
    '#type' => 'markup',
    '#value' => theme('image', $promo->filepath, $promo->alt, $promo->title),
    '#prefix' => '<div class="promo-form-image">',
    '#suffix' => '</div>',
  );

  // If its for a node, we build the link internally
  if ($promo->nid) {
    $form['promo']['link'] = array('#type' => 'value', '#value' => 'node/'. $promo->nid);
  }
  else if ($nid) {
    $form['promo']['link'] = array('#type' => 'value', '#value' => 'node/'. $node->nid);
  }
  else {
    $form['promo']['link'] = array(
      '#type' => 'textfield',
      '#title' => t('Destination URL'),
      '#description' => t('The link where this promo will take the user when clicked. Internal links: node/49. External links: http://google.com'),
      '#default_value' => $promo->link,
      '#weight' => 10
    );
  }
  $form['promo']['alt'] = array(
    '#type' => 'textfield',
    '#title' => t('Alt Text'),
    '#default_value' => $promo->alt,
    '#description' => t('Alt text to be shown for the image.  This is helpful for screen readers and acessability.')
  );
  $form['promo']['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title Text'),
    '#default_value' => $promo->title,
    '#description' => 'Title text is the hover text shown when someone hovers over the image',
  );
  
  $form['upload'] = array(
    '#type' => 'fieldset',
    '#title' => t('Image Upload'),
  );
  $form['upload']['file'] = array(
    '#type' => 'file',
    '#title' => t('Promo Image'),
    '#description' => t('Choose a file to be shown as the banner for this promo.')
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save Promo Settings'),
  );
  return $form;
}

/**
 * Submit handler
 */
function promo_form_submit($form, &$form_state) {
  $form_values = $form_state['values'];
  $destination = sprintf('%s/%s', file_directory_path(), 'promos');

  // handle file upload
  if ($image = file_save_upload('file', array('file_validate_is_image' => array()) , $destination, FILE_EXISTS_RENAME)) {
    drupal_set_message('File uploaded successfully.');
  }
  else if ($form_values['filepath']){
    $image = (object) array();
    $image->filepath = $form_values['filepath'];
  }
  else {
    drupal_set_message('Image upload failed. There is no image associated with this promo. Please try again.');
  }

  $type     = $form_values['type'];
  $pid      = $form_values['pid'];
  $name     = $form_values['name'];
  $nid      = $form_values['nid'];
  $status   = $form_values['status'];
  $link     = $form_values['link'];
  $title    = $form_values['title'];
  $alt      = $form_values['alt'];
  
  if ($type == 'node' || $type == 'admin') {
    if ($pid > 0) {
      db_query("UPDATE {promo} SET name = '%s', nid = %d, link = '%s', status = %d, filepath = '%s', alt = '%s', title = '%s' WHERE pid = %d", $name, $nid, $link, $status, $image->filepath, $alt, $title, $pid);
    }
    else {
      db_query("INSERT INTO {promo} (name, nid, link, status, filepath, alt, title) VALUES (%d, '%s', %d, '%s', '%s', '%s')", $pid, $name, $nid, $link, $status, $image->filepath, $alt, $title);
    }
  }
  else if ($type = 'add') {
    db_query("INSERT INTO {promo} (name, link, status, filepath, alt, title) VALUES ('%s', '%s', %d, '%s', '%s', '%s')", $name, $link, $status, $image->filepath, $alt, $title);
  }
  drupal_set_message('Promo updated successfully.');
  // if editing from a node we send the user back to the node edit form
  if ($type == 'node') {
    return 'node/'. $nid .'/promo';
  }
  else {
    return 'admin/content/promo';
  }
}


/**
 * Delete form
 */
function promo_delete(&$form_state, $pid = NULL) {
  if ( ! is_numeric($pid)) {
    drupal_set_message(t('Error finding promo with id = @pid', array('@pid' => check_plain($pid))));
    return 'admin/content/promo';
  }
  
  $query = db_query("SELECT * FROM {promo} WHERE pid = %d", $pid);
  $promo = db_fetch_object($query);

  if ($promo) {
    // Build Confirm Form
    $form['pid'] = array('#type' => 'value', '#value' => $pid);
    $form['promo'] = array(
      '#type' => 'fieldset',
      '#title' => t('Promo Details'),
    );
    $form['promo']['title'] = array(
      '#type' => 'markup',
      '#value' => '<h3>'. $promo->title .'</h3>',
    );
    $form['promo']['image'] = array(
      '#type' => 'markup',
      '#value' => theme('image', file_create_path($promo->filepath), $promo->alt, $promo->title),
    );
    $form = confirm_form(
      $form,
      t('Are you sure you would like to delete this promo?'),
      'admin/content/promo',
      null,
      t('Delete'),
      t('Cancel')    
    );
    return $form;
  }
  else {
    drupal_set_message(t('Error finding promo with id = @pid', array('@pid' => check_plain($pid))));    
    drupal_goto('admin/content/promo');
  }
}

/**
 * Delete submit handler
 */
function promo_delete_submit($form, &$form_state) {
  $query = db_query("DELETE FROM {promo} WHERE pid = %d", $form_state['values']['pid']);
  drupal_set_message('Promo deleted successfully.');
  return 'admin/content/promo';
}