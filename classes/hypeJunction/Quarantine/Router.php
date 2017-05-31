<?php

namespace hypeJunction\Quarantine;

class Router {

	/**
	 * Route /quarantine
	 *
	 * @param array $segments Segments
	 *
	 * @return bool
	 */
	public static function route($segments) {

		set_input('quarantine', true);

		$page = array_shift($segments);

		switch ($page) {
			default :
				echo elgg_view_resource('quarantine/index');

				return true;

			case 'change_status' :
				$guid = array_shift($segments);
				echo elgg_view_resource('quarantine/change_status', [
					'guid' => $guid,
				]);

				return true;
		}

		return false;
	}

}