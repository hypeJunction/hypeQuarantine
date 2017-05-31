<?php

/**
 * hypeQuarantine
 *
 * Implements content approval workflow
 *
 * @author    Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2017, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

use hypeJunction\Quarantine\Menus;
use hypeJunction\Quarantine\Policy;
use hypeJunction\Quarantine\Router;
use hypeJunction\Quarantine\Views;

elgg_register_event_handler('init', 'system', function () {

	elgg_register_page_handler('quarantine', [Router::class, 'route']);

	elgg_register_event_handler('create', 'object', [Policy::class, 'quarantine']);

	elgg_register_action('quarantine/change_status', __DIR__ . '/actions/quarantine/change_status.php');

	elgg_register_plugin_hook_handler('get_sql', 'access', [Policy::class, 'setAccessSql']);

	elgg_register_plugin_hook_handler('view_vars', 'object/elements/summary/subtitle', [Views::class, 'extendSubtitle']);

	elgg_register_plugin_hook_handler('register', 'menu:entity', [Menus::class, 'setupEntityMenu']);
	elgg_register_plugin_hook_handler('register', 'menu:topbar', [Menus::class, 'setupTopbarMenu']);

	elgg_extend_view('elgg.css', 'quarantine/styles.css');
	elgg_extend_view('admin.css', 'quarantine/styles.css');

	// Integrate with roles_crud
	elgg_register_plugin_hook_handler('capability_types', 'roles', [Policy::class, 'setCapabilityTypes']);

});
