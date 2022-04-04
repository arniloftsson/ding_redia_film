<?php

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('User film status'),
  'description' => t('The current loan status off a user in the Libry Film service'),
  'single' => TRUE,
  'content_types' => array('list'),
  'required context' => new ctools_context_required(t('User'), 'user'),
  'category' => t('User'),
);

/**
 * Render the block.
 */
function ding_redia_film_librystatus_content_type_render($subtype, $conf, $panel_args, $context) {
  // Define basic block object.
  $block = new stdClass();
  $block->module = 'ding_redia_film';
  $block->delta = 'librystatus';
  $block->title = t('Film Status');


  $block->content = t('Status');


  return $block;
}

/**
 * Adding the admin form, to be able to control the required context.
 */
function ding_redia_film_librystatus_content_type_edit_form($form, &$form_state) {
  return $form;
}

/**
 * Implements hook_content_type_edit_form_submit().
 */
function ding_redia_film_librystatus_content_type_edit_form_submit($form, &$form_state) {
  foreach (array_keys($form_state['plugin']['defaults']) as $key) {
    if (isset($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
  }
}

