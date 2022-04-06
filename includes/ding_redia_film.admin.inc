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
    '#description' => t('Url off the film service server. Eg. !server', ['!server' => DING_REDIA_FILM_SERVER]),
    '#default_value' => variable_get('ding_redia_film_server', DING_REDIA_FILM_SERVER),
  ];

  $form['ding_redia_film_enable_logging'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable logging'),
    '#default_value' => variable_get('ding_redia_film_enable_logging', FALSE),
  ];

  $form['revert_config'] = array(
    '#type' => 'fieldset',
    '#title' => t('Revert configuration'),
    '#description' => t('Reverts the configuration after a update off DDBCMS has overridden them. Needs to be done every time DDBCMS is updated'),
  );

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

