<?php

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('User film loan list'),
  'description' => t('The current libry film service loans for a user'),
  'single' => TRUE,
  'content_types' => array('list'),
  'required context' => new ctools_context_required(t('User'), 'user'),
  'category' => t('User'),
);

/**
 * Render the block.
 */
function ding_redia_film_libryloans_content_type_render($subtype, $conf, $panel_args, $context) {
  // Define basic block object.
  $block = new stdClass();
  $block->module = 'ding_redia_film';
  $block->delta = 'libryloans';
  $block->title = t('Film loan list');

  $block->content = ding_redia_film_get_loans(); 

  return $block;
}

/**
 * Adding the admin form, to be able to control the required context.
 */
function ding_redia_film_libryloans_content_type_edit_form($form, &$form_state) {
  return $form;
}

/**
 * Implements hook_content_type_edit_form_submit().
 */
function ding_redia_film_libryloans_content_type_edit_form_submit($form, &$form_state) {
  foreach (array_keys($form_state['plugin']['defaults']) as $key) {
    if (isset($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
  }
}

