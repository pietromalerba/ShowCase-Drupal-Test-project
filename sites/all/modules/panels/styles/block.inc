<?php
// $Id: block.inc,v 1.2 2008/07/16 00:09:05 merlinofchaos Exp $

/**
 * @file styles/block.inc
 * Definition of the 'default' panel style.
 */

// ---------------------------------------------------------------------------
// Panels hooks.

/**
 * Implementation of hook_panels_style_info().
 */
function panels_block_panels_styles() {
  return array(
    'block' => array(
      'title' => t('System block'),
      'description' => t('Display the pane as a system block; this is more restrictive than the default.'),
      'render pane' => 'panels_block_style_render_pane',
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
function theme_panels_block_style_render_pane($content, $pane, $display) {
  if (!empty($content->title)) {
    $content->subject = $content->title;
  }
  return theme('block', $content);
}

