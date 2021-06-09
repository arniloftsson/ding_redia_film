<?php

/**
 * @file
 * Implements admin page for ding redia film module.
 */

/**
 * Form builder; Configure Redia FIlm settings for this site.
 *
 * @ingroup forms
 *
 * @see system_settings_form()
 */
function ding_redia_film_admin_form($form, &$form_state) {
  $form['ding_redia_film_server'] = array(
    '#type' => 'textfield',
    '#title' => t('Film service server'),
    '#description' => t('Url off the film service server. Eg. https://bapps-be-staging.redia.dk/v2/'),
    '#default_value' => variable_get('ding_redia_film_server', 'https://bapps-be-staging.redia.dk/v2/'),
  );

  $form['ding_redia_film_enable_logging'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable logging'),
    '#default_value' => variable_get('ding_redia_film_enable_logging', FALSE),
  );

  return system_settings_form($form);
}

