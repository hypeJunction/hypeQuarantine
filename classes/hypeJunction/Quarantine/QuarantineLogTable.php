<?php

namespace hypeJunction\Quarantine;

/**
 * @access private
 */
class QuarantineLogTable {

	private $table;

	/**
	 * Constructor
	 */
	public function __construct() {
		$dpbrefix = elgg_get_config('dbprefix');
		$this->table = "{$dpbrefix}quarantine_log";
	}

	/**
	 * Get entity status rows by guid
	 *
	 * @param int $guid Entity guid
	 *
	 * @return \stdClass[]
	 */
	public function get($guid) {
		$query = "
			SELECT * FROM {$this->table}
			WHERE guid = :guid
		";

		$params = [
			':guid' => $guid,
		];

		return get_data($query, null, $params) ?: [];
	}

	/**
	 * Add status change entry
	 *
	 * @param int $guid        Entity guid
	 * @param int $user_guid   User performing an action
	 * @param int $prev_status Previous status
	 * @param int $status      New status
	 *
	 * @return int|false
	 */
	public function add($guid, $status = 0, $user_guid = null, $prev_status = null) {

		$query = "
			INSERT INTO $this->table
			SET guid = :guid,
			    user_guid = :user_guid,
			    prev_status = :prev_status,
			    status = :status,
			    time = :time
		";

		$params = [
			':guid' => (int) $guid,
			':user_guid' => isset($user_guid) ? (int) $user_guid : null,
			':prev_status' => isset($prev_status) ? (int) $prev_status : null,
			':status' => (int) $status,
			':time' => time(),
		];

		return insert_data($query, $params);
	}

}
