<?php

namespace hypeJunction\Quarantine;

/**
 * @access private
 */
class QuarantineTable {

	private $table;

	/**
	 * Constructor
	 */
	public function __construct() {
		$dpbrefix = elgg_get_config('dbprefix');
		$this->table = "{$dpbrefix}quarantine";
	}

	/**
	 * Get entity status row by guid
	 *
	 * @param int $guid Entity guid
	 *
	 * @return \stdClass|false
	 */
	public function get($guid) {
		$query = "
			SELECT * FROM {$this->table}
			WHERE guid = :guid
		";

		$params = [
			':guid' => $guid,
		];

		return get_data_row($query, null, $params) ?: false;
	}

	/**
	 * Update entity status in the quarantine
	 *
	 * @param int $guid   GUID of the entity
	 * @param int $status Quarantine status
	 *
	 * @return int|false
	 */
	public function update($guid, $status = 0) {

		$query = "
			INSERT INTO {$this->table}
			SET guid = :guid,
				status = :status
			ON DUPLICATE KEY
			UPDATE status = :status
		";

		$params = [
			':guid' => (int) $guid,
			':status' => (int) $status,
		];

		return insert_data($query, $params) !== false;

	}
}
