<?php
// $Id: list.inc,v 1.3.2.1 2008/10/23 23:48:33 merlinofchaos Exp $


/**
 * @file styles/list.inc
 * Definition of the 'list' panel style.
 */


// ---------------------------------------------------------------------------
// Panels hooks.

/**
 * Implementation of hook_panels_style_info().
 */
function panels_list_panels_styles() {
  return array(
    'list' => array(
      'title' => t('List'),
      'description' => t('Presents the panes in the form of an HTML list.'),
      'render panel' => 'panels_list_style_render_panel',
      'settings form' => 'panels_list_style_settings_form',
      'settings validate' => 'panels_list_style_settings_validate',
    ),
  );
}


// ---------------------------------------------------------------------------
// Panels style plugin callbacks.

/**
 * Render callback.
 *
 * @ingroup themeable
 */
function theme_panels_list_style_render_panel($display, $panel_id, $panes, $settings) {
  $items = array();

  foreach ($panes as $pane_id => $content) {
    $item = panels_render_pane($content, $display->content[$pane_id], $display);
    if (isset($item)) {
      $items[] = $item;
    }
  }

  if (empty($settings['list_type'])) {
    $settings['list_type'] = 'ul';
  }

  return theme('item_list', $items, NULL, $settings['list_type']);
}

/**
 * Settings form callback.
 */
function panels_list_style_settings_form($style_settings) {
  $form['list_type'] = array(
    '#type' => 'select',
    '#title' => t('List type'),
    '#options' => array(
      'ul' => t('Unordered'),
      'ol' => t('Ordered'),
    ),
    '#default_value' => (isset($style_settings['list_type'])) ? $style_settings['list_type'] : 'ul',
  );

  return $form;
}

