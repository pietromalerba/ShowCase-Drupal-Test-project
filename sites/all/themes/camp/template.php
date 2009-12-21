<?php
// $Id: template.php,v 1.17.2.1 2009/02/13 06:47:44 johnalbin Exp $

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to camp_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: camp_breadcrumb()
 *
 *   where camp is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/*
 * Add any conditional stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that ALWAYS need to be included, you should add them to
 * your .info file instead. Only use this section if you are including
 * stylesheets based on certain conditions.
 */
/* -- Delete this line if you want to use and modify this code
// Example: optionally add a fixed width CSS file.
if (theme_get_setting('camp_fixed')) {
  drupal_add_css(path_to_theme() . '/layout-fixed.css', 'theme', 'all');
}
// */

/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function
function camp_preprocess(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function camp_preprocess_page(&$vars, $hook) {
  $vars['user_nav'] = camp_user_nav();
  $vars['user_registration_badge'] = camp_user_registration_badge();
  $vars['user_is_registered_class'] = camp_user_registered_status();
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function camp_preprocess_node(&$vars, $hook) {
  if ($vars['node']->type == 'sponsor') {
    foreach ($vars['node']->taxonomy as $tid => $term) {
      $vars['sponsor_level'] = t('%term Sponsor', array('%term' => $term->name));
    }
  }
  
  if ( ! empty($vars['node']->taxonomy) && is_array($vars['node']->taxonomy)) {
    foreach ($vars['node']->taxonomy as $tid => $term) {
      $terms[] = $term->name;
    }
    $output .= '<ul class="links inline">';
    $output .= '<li>'. implode('</li><li>', $terms) .'</li>';
    $output .= '</ul>';
    $vars['terms'] = $output;
  }
}

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function camp_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function camp_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Implementation of HOOK_theme().
 */
function camp_theme(&$existing, $type, $theme, $path) {
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  $hooks['news_node_form'] = array(
    'arguments' => array('form' => NULL),
    'template' => 'form-node-news',
  );
  $hooks['session_node_form'] = array(
    'arguments' => array('form' => NULL),
    'template' => 'form-node-session',
  );  
  $hooks['sponsor_node_form'] = array(
    'arguments' => array('form' => NULL),
    'template' => 'form-node-sponsor',
  );
  // @TODO: Needs detailed comments. Patches welcome!
  return $hooks;
}

function camp_links($links, $attributes = array('class' => 'links')) {
  $output = '';

  if (count($links) > 0) {
    $output = '<ul'. drupal_attributes($attributes) .'>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = $key;

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))) {
        $class .= ' active';
      }
      
      if (function_exists('pathauto_cleanstring')) {
        $class .= ' ' . pathauto_cleanstring(url($link['href']));
      }
      
      $output .= '<li'. drupal_attributes(array('class' => $class)) .'>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span'. $span_attributes .'>'. $link['title'] .'</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

/**
 * User Bar
 */
function camp_user_nav() {
  global $user;
  if (!$user->uid) {
    $output .= t('!login or !register to participate.', array('!login' => l('login', 'user'), '!register' => l('register', 'user/register')));
  }
  else {
    $output .= t('Welcome back !username, !logout', array('!username' => l($user->name, 'user/'. $user->uid), '!logout' => l('logout', 'logout')));
  }
  return $output;
}


function camp_user_registered_status() {
  global $user;
  if (in_array('attendee', $user->roles)) {
    return 'step3';
  }
  // User is registered (has an account) but has not purchased a ticket. STEP 2.
  else if ($user->uid) {
    return 'step2';
  }
  // User needs to register for the site first.  STEP 1.
  else {
    return 'step1';
  }  
}

function camp_user_registration_badge() {
  global $user;
  // User has purchased a ticket. STEP 3
  if (in_array('attendee', $user->roles)) {
    return t('You are registered');
  }
  // User is registered (has an account) but has not purchased a ticket. STEP 2.
  else if ($user->uid) {
    return t('<big>Not</big> registered') . '<br />' . t('<a href="!url">buy ticket</a>', array('!url' => url('buy-ticket', array('query' => 'destination=cart/checkout'))));
  }
  // User needs to register for the site first.  STEP 1.
  else {
    return t('<big>Not</big> registered') . '<br />' . t('<a href="!url">register now</a>', array('!url' => url('user/register')));
  }
}

/**
 * Theme function for 'default' userreference field formatter.
 */
function camp_userreference_formatter_default($element) {
  $output = '';
  $default = variable_get('user_picture_default', '');
  if (isset($element['#item']['uid']) && $account = user_load(array('uid' => $element['#item']['uid']))) {
    $filepath = $account->picture ? $account->picture : $default;
    $output = l(theme('imagecache', 'mini', $filepath, $account->profile_fullname, $account->profile_fullname), 'user/'. $account->uid, array('html' => TRUE));
    $output .= '<span class="username">'. theme('username', $account) .'</span>';
  }
  return $output;
}
