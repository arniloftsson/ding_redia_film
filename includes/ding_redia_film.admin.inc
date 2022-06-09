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

  $form['ding_redia_film_server'] = [
    '#type' => 'textfield',
    '#title' => t('Film service server'),
    '#description' => t('Url off the film service server. Eg. !server', ['!server' => 'https://bapps-be-staging.redia.dk/v2/']),
    '#default_value' => variable_get('ding_redia_film_server', 'https://bapps-be-staging.redia.dk/v2/'),
  ];

  $form['ding_redia_film_api_key'] = [
    '#type' => 'textfield',
    '#title' => t('Film service server api key'),
    '#description' => t('Api key off the film service server'),
    '#default_value' => variable_get('ding_redia_film_api_key', ''),
  ];

  $form['ding_redia_film_version'] = [
    '#type' => 'textfield',
    '#title' => t('Film service server version'),
    '#description' => t('Version key off the film service server. E.g ios_3.1.3'),
    '#default_value' => variable_get('ding_redia_film_version', 'ios_3.1.3'),
  ];

  $form['ding_redia_film_language'] = [
    '#type' => 'textfield',
    '#title' => t('Film service server language'),
    '#description' => t('Language off the film service server. Either "en" or "da"'),
    '#default_value' => variable_get('ding_redia_film_language', 'en'),
  ];

  $form['ding_redia_film_enable_logging'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable logging'),
    '#default_value' => variable_get('ding_redia_film_enable_logging', FALSE),
  ];

  $form['ding_redia_film_development'] = [
    '#type' => 'fieldset',
    '#title' => t('Development settings'),
    '#description' => t('A token to use instead of the token you get when logging in to the openplatform.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ];

  $form['ding_redia_film_development']['ding_redia_film_use_devel_token'] = [
    '#type' => 'checkbox',
    '#title' => t('Use development token'),
    '#default_value' => variable_get('ding_redia_film_use_devel_token', FALSE),
  ];

  $default = variable_get('ding_redia_film_development_token', FALSE);
  $form['ding_redia_film_development']['ding_redia_film_development_token'] = [
    '#type' => 'textfield',
    '#title' => t('Agency token'),
    '#default_value' => $default ? $default : '',
    '#description' => t('User token from the openplatform'),
  ];

  $form['ding_redia_film_development']['ding_redia_film_use_devel_cover'] = [
    '#type' => 'checkbox',
    '#title' => t('Use development cover'),
    '#default_value' => variable_get('ding_redia_film_use_devel_cover', FALSE),
  ];

  $form['revert_config'] = [
    '#type' => 'fieldset',
    '#title' => t('Revert configuration'),
    '#description' => t('Reverts the configuration after a update off DDBCMS has overridden them. Needs to be done every time DDBCMS is updated'),
  ];

  $form['revert_config']['submit_button'] = [
    '#value' => t('Revert configuration'),
    '#type' => 'submit',
    '#submit' => ['ding_redia_film_revert_configuration'],
  ];

  return system_settings_form($form);
}

/**
 * Revert configuration to default.
 */
function ding_redia_film_revert_configuration($form, &$form_state) {
  try {
    ding_redia_film_add_site_template_config();
    ding_redia_film_add_film_loan_panes();
    drupal_set_message(t('Libry film configuration reverted'));
  } catch (Exception $e) {
    watchdog_exception('ding_redia_film', $e);
    drupal_set_message(t('Failed to revert Libry film configuration'));
  }
}

