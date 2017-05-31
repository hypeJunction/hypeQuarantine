<?php

namespace hypeJunction\Quarantine;

use ElggMenuItem;

class Menus {

	/**
	 * Setup menu
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:entity"
	 * @param ElggMenuItem[] $return Menu
	 * @param array          $params Hook params
	 *
	 * @return ElggMenuItem[]
	 */
	public static function setupEntityMenu($hook, $type, $return, $params) {

		$entity = elgg_extract('entity', $params);

		if (Policy::canChangeStatus($entity)) {
			$return[] = ElggMenuItem::factory([
				'name' => 'quarantine_status',
				'href' => "quarantine/change_status/$entity->guid",
				'text' => elgg_echo('quarantine:change_status'),
				'link_class' => 'elgg-lightbox',
			]);
		}

		return $return;
	}

	/**
	 * Setup menu
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:topbar"
	 * @param ElggMenuItem[] $return Menu
	 * @param array          $params Hook params
	 *
	 * @return ElggMenuItem[]
	 */
	public static function setupTopbarMenu($hook, $type, $return, $params) {

		$user = elgg_get_logged_in_user_entity();

		$show_menu = function () use ($user) {
			if (!$user) {
				return false;
			}
			if ($user->isAdmin()) {
				return true;
			} else if (elgg_is_active_plugin('roles')) {
				$role = roles_get_role($user);
				$setting = elgg_get_plugin_setting("role:$role->name", 'roles_crud');
				if ($setting) {
					$conf = unserialize($setting);
					foreach ($conf as $type => $subtypes) {
						foreach ($subtypes as $capabilities) {
							if ($capabilities['update:quarantine_status'] == 'allow') {
								return true;
							}
						}
					}
				}
			}

			return false;
		};

		if ($show_menu) {
			$return[] = ElggMenuItem::factory([
				'name' => 'quarantine',
				'text' => elgg_echo('quarantine'),
				'href' => 'quarantine',
				'section' => 'alt',
				'priority' => 100,
			]);
		}

		return $return;
	}
}