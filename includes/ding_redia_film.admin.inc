<?php

/**
 * @file
 * Implements admin page for ding Redia film module.
 */

/**
 * Implements hook_form().
 */
function ding_redia_film_admin_form($form, &$form_state) {
  $form = [];

  // @TODO: Missing ding_redia_film.install with hook_uninstall to clean up variables.

  $form['ding_redia_film_server'] = [
    '#type' => 'textfield',
    '#title' => t('Film service server'),
    '#description' => t('Url off the film service server. Eg. !server', ['!server' => DING_REDIA_FILM_SERVER]),
    '#default_value' => variable_get('ding_redia_film_server', DING_REDIA_FILM_SERVER),
  ];

  $form['ding_redia_film_enable_logging'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable logging'),
    '#default_value' => variable_get('ding_redia_film_enable_logging', FALSE),
  ];

  return system_settings_form($form);
}

