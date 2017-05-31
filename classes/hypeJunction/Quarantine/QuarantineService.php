<?php

namespace hypeJunction\Quarantine;

/**
 * @access private
 */
class QuarantineService {

	const STATUS_REJECTED = -7;
	const STATUS_CHANGE_REQUESTED = -5;
	const STATUS_PENDING = 0;
	const STATUS_CLEARED = 1;

	/**
	 * @var self
	 */
	static $_instance;

	/**
	 * @var QuarantineTable
	 */
	private $table;

	/**
	 * @var QuantineLogTable
	 */
	private $log_table;

	/**
	 * Constructor
	 *
	 * @param QuarantineTable    $table     DB table
	 * @param QuarantineLogTable $log_table DB log table
	 */
	public function __construct(QuarantineTable $table, QuarantineLogTable $log_table) {
		$this->table = $table;
		$this->log_table = $log_table;
	}

	/**
	 * Returns a singleton
	 * @return self
	 */
	public static function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self(new QuarantineTable(), new QuarantineLogTable());
		}

		return self::$_instance;
	}

	/**
	 * Returns DB table
	 * @return QuarantineTable
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * Returns DB log table
	 * @return QuarantineLogTable
	 */
	public function getLogTable() {
		return $this->log_table;
	}

	/**
	 * Quarantine an entity
	 *
	 * @param \ElggEntity $entity Entity
	 * @param int         $status Status to set
	 *
	 * @return bool
	 */
	public function quarantine(\ElggEntity $entity, $status = self::STATUS_PENDING) {
		$prev_status = $this->getStatus($entity, true);

		if ($prev_status == $status) {
			return true;
		}

		$result = $this->getTable()->update($entity->guid, $status);
		if ($result) {
			$this->getLogTable()->add($entity->guid, $status, null, $prev_status);
		}

		$status_codes = Policy::getStatusCodes();

		if (isset($status_codes[$status])) {
			$status_name = $status_codes[$status];
			elgg_trigger_event("quarantine_status_$status_name", $entity->getType(), $entity);
		}

		return $result;
	}

	/**
	 * Get entity quarantine status
	 *
	 * @param \ElggEntity $entity  Entity
	 * @param bool        $as_code Return status code value
	 *
	 * @return false|\stdClass
	 */
	public
	function getStatus(\ElggEntity $entity, $as_code = false) {
		$row = $this->getTable()->get($entity->guid);
		if (!$row) {
			$status = self::STATUS_CLEARED;
		} else {
			$status = $row->status;
		}

		if ($as_code) {
			return $status;
		}

		$codes = Policy::getStatusCodes();

		return elgg_extract($status, $codes, $status);
	}

}
