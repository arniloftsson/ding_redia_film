<?php
/**
 * @file
 * ding_digital_loan.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_pages().
 */
function ding_digital_loan_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'user_digital_loans';
  $page->task = 'page';
  $page->admin_title = 'User digital loans';
  $page->admin_description = '';
  $page->path = 'user/%user/status-digital-loans';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'external_user',
        'settings' => NULL,
        'context' => array(
          0 => 'argument_entity_id:user_1',
        ),
        'not' => FALSE,
      ),
      1 => array(
        'name' => 'compare_users',
        'settings' => array(
          'equality' => '1',
        ),
        'context' => array(
          0 => 'argument_entity_id:user_1',
          1 => 'logged-in-user',
        ),
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
    'type' => 'none',
    'settings' => NULL,
  );
  $page->menu = array(
    'type' => 'tab',
    'title' => 'Digital loans',
    'name' => 'navigation',
    'weight' => '2',
    'parent' => array(
      'type' => 'tab',
      'title' => 'Min side',
      'name' => 'navigation',
      'weight' => '0',
    ),
  );
  $page->arguments = array(
    'user' => array(
      'id' => 1,
      'identifier' => 'User: ID',
      'name' => 'entity_id:user',
      'settings' => array(),
    ),
  );
  $page->conf = array(
    'admin_paths' => FALSE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_user_digital_loans__panel_context_bb90113f-e639-4acd-9170-0cf6bb30e995';
  $handler->task = 'page';
  $handler->subtask = 'user_digital_loans';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Panel',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'access' => array(
      'plugins' => array(),
      'logic' => 'and',
    ),
    'metatag_panels' => array(
      'enabled' => 1,
      'metatags' => array(
        'title' => array(
          'value' => 'Loans | [current-page:pager][site:name]',
        ),
      ),
    ),
    'name' => '',
  );
  $display = new panels_display();
  $display->layout = '25_50_25';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'middle' => NULL,
      'left' => NULL,
      'right' => NULL,
      'left_sidebar' => NULL,
      'main_content' => NULL,
      'right_sidebar' => NULL,
      'attachment_4_1' => NULL,
      'attachment_4_2' => NULL,
      'attachment_4_3' => NULL,
      'attachment_4_4' => NULL,
      'attachment_3_1' => NULL,
      'attachment_3_2' => NULL,
      'attachment_3_3' => NULL,
      'attachment_2_1' => NULL,
      'attachment_2_2' => NULL,
      'attachment_1_1' => NULL,
      'top_banner' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '846ee609-1e6e-4c1e-ada9-7009f8684530';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-95c59944-f02d-4a0c-95c4-a7a854e6a5f3';
    $pane->panel = 'left_sidebar';
    $pane->type = 'user_menu';
    $pane->subtype = 'user_menu';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => 'argument_entity_id:user_1',
      'override_title' => 0,
      'override_title_text' => '',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '95c59944-f02d-4a0c-95c4-a7a854e6a5f3';
    $display->content['new-95c59944-f02d-4a0c-95c4-a7a854e6a5f3'] = $pane;
    $display->panels['left_sidebar'][0] = 'new-95c59944-f02d-4a0c-95c4-a7a854e6a5f3';
    $pane = new stdClass();
    $pane->pid = 'new-32bc7e0c-87ad-486f-8cb0-f8537e9a1a97';
    $pane->panel = 'main_content';
    $pane->type = 'libryloans';
    $pane->subtype = 'libryloans';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => 'argument_entity_id:user_1',
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array(
      'css_id' => '',
      'css_class' => 'default-account-panel-layout',
    );
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = '';
    $pane->uuid = '32bc7e0c-87ad-486f-8cb0-f8537e9a1a97';
    $display->content['new-32bc7e0c-87ad-486f-8cb0-f8537e9a1a97'] = $pane;
    $display->panels['main_content'][0] = 'new-32bc7e0c-87ad-486f-8cb0-f8537e9a1a97';
    $pane = new stdClass();
    $pane->pid = 'new-921e7f16-24be-41e6-a025-98edd86acb5c';
    $pane->panel = 'main_content';
    $pane->type = 'librystatus';
    $pane->subtype = 'librystatus';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => 'argument_entity_id:user_1',
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array(
      'css_id' => '',
      'css_class' => 'default-account-panel-layout',
    );
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = '';
    $pane->uuid = '921e7f16-24be-41e6-a025-98edd86acb5c';
    $display->content['new-921e7f16-24be-41e6-a025-98edd86acb5c'] = $pane;
    $display->panels['main_content'][1] = 'new-921e7f16-24be-41e6-a025-98edd86acb5c';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $page->default_handlers[$handler->name] = $handler;
  $pages['user_digital_loans'] = $page;

  return $pages;

}
