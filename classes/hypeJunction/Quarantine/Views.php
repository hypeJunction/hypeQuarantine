<?php

namespace hypeJunction\Quarantine;


class Views {

	/**
	 * Extends entity subtitle with quarantine status badge
	 *
	 * @param string $hook   "views_vars"
	 * @param string $type   "object/elements/summary/subtitle"
	 * @param array  $vars   View vars
	 * @param array  $params Hook params
	 *
	 * @return array
	 */
	public static function extendSubtitle($hook, $type, $vars, $params) {

		$subtitle = elgg_extract('subtitle', $vars);
		if (!$subtitle) {
			return;
		}

		$parts = [$subtitle];
		$parts[] = elgg_view('object/elements/quarantine_status', $vars);

		$return['subtitle'] = implode('<br />', $parts);

		return $return;
	}
}