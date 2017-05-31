<?php

namespace hypeJunction\Quarantine;

use ElggEntity;

class Policy {

	/**
	 * Returns available status codes
	 *
	 * @return mixed|null
	 */
	public static function getStatusCodes() {

		$codes = [
			QuarantineService::STATUS_CLEARED => 'cleared',
			QuarantineService::STATUS_PENDING => 'pending',
			QuarantineService::STATUS_CHANGE_REQUESTED => 'change_requested',
			QuarantineService::STATUS_REJECTED => 'rejected',
		];

		return elgg_trigger_plugin_hook('status_codes', 'quarantine', null, $codes);
	}

	/**
	 * Set capability types for CRUD roles
	 *
	 * @param string $hook   "capability_types"
	 * @param string $type   "roles"
	 * @param array  $return Capability types
	 * @param array  $params Hook params
	 *
	 * @return array
	 */
	public static function setCapabilityTypes($hook, $type, $return, $params) {

		$role = elgg_extract('role', $params);

		$is_visitor = $role->name == VISITOR_ROLE;

		$return['update:quarantine_status'] = !$is_visitor;

		return $return;
	}

	/**
	 * Check if user can change quarantine status of an entity
	 *
	 * @param ElggEntity $entity Entity
	 * @param ElggUser   $user   User
	 *
	 * @return bool
	 */
	public static function canChangeStatus(ElggEntity $entity, ElggUser $user = null) {

		if (!isset($user)) {
			$user = elgg_get_logged_in_user_entity();
		}

		$permission = false;
		if (!$user) {
			$permission = false;
		} else if ($user->isAdmin()) {
			$permission = true;
		} else {
			if (elgg_is_active_plugin('roles')) {

				$entity_type = $entity->getType();
				$entity_subtype = $entity->getSubtype() ?: 'default';

				$role = roles_get_role($user);
				$setting = elgg_get_plugin_setting("role:$role->name", 'roles_crud');
				if ($setting) {
					$conf = unserialize($setting);
					if (isset($conf[$entity_type][$entity_subtype]['update:quarantine_status'])) {
						$perm = $conf[$entity_type][$entity_subtype]['update:quarantine_status'];
						if ($perm == 'allow') {
							$permission = true;
						}
					}
				}
			}
		}

		$params = [
			'entity' => $entity,
			'user' => $user,
		];

		return elgg_trigger_plugin_hook('permissions_check:quarantine_status', $entity->getType(), $params, $permission);
	}

	/**
	 * Quarantine an entity
	 *
	 * @param string     $event  "create"
	 * @param string     $type   "object"
	 * @param ElggEntity $entity Entity
	 *
	 * @return void
	 */
	public static function quarantine($event, $type, $entity) {

		// No point in queueing entities that can be unquarantineed by the user
		if (self::canChangeStatus($entity)) {
			return;
		}

		$entity_type = $entity->getType();
		$entity_subtype = $entity->getSubtype() ?: 'default';

		if (elgg_get_plugin_setting("quarantine:$entity_type:$entity_subtype", 'hypeQuarantine')) {
			$svc = QuarantineService::getInstance();
			$svc->quarantine($entity);
		}

	}

	/**
	 * Restrict access to quarantined entities
	 *
	 * @param string $hook   "get_sql"
	 * @param string $type   "access"
	 * @param array  $return Access SQL queries
	 * @param array  $params Hook params
	 *
	 * @return array
	 */
	public static function setAccessSql($hook, $type, $return, $params) {

		static $catch;

		if (elgg_extract('ignore_access', $params)) {
			return;
		}

		$dbprefix = elgg_get_config('dbprefix');

		$user_guid = (int)elgg_extract('user_guid', $params);
		$table = $params['table_alias'] ? $params['table_alias'] . '.' : '';
		$guid = elgg_extract('guid_column', $params, 'guid');
		$owner_guid = elgg_extract('owner_guid_column', $params, 'owner_guid');

		if ($table != 'e.') {
			return;
		}

		$visible_status = (int)QuarantineService::STATUS_CLEARED;

		// Give access to all entities that are either approved, or have not been added to the quarantine
		$subselect = "(
			NOT EXISTS (
				SELECT 1 FROM {$dbprefix}quarantine
				WHERE guid = {$table}{$guid}
			) 
			OR 
			EXISTS (
				SELECT 1 FROM {$dbprefix}quarantine
				WHERE guid = {$table}{$guid} and status = $visible_status
			)
		)";

		$return['ands']['quarantine_status'] = "({$table}{$owner_guid} = {$user_guid} OR {$subselect})";

		if (!elgg_is_active_plugin('roles')) {
			return $return;
		}

		if ($catch) {
			return;
		}

		$catch = true;

		$user = get_entity($user_guid);
		$role = roles_get_role($user ?: null);

		$catch = false;

		$setting = elgg_get_plugin_setting("role:$role->name", 'roles_crud');
		if (!$setting) {
			return $return;
		}

		$allow = [
			'update:quarantine_status' => [],
		];

		$conf = unserialize($setting);
		foreach ($conf as $type => $subtypes) {
			foreach ($subtypes as $subtype => $capabilities) {
				foreach ($capabilities as $capability => $permission) {
					if (!array_key_exists($capability, $allow)) {
						continue;
					}
					if ($permission == 'allow') {
						if ($subtype == 'default') {
							$allow[$capability][$type] = [];
						} else {
							$allow[$capability][$type][] = get_subtype_id($type, $subtype);
						}
					}
				}
			}
		}

		$or_clauses = [];

		foreach ($allow['update:quarantine_status'] as $type => $subtypes) {
			// Moderators will have access to all entities of a given subtype
			if (!empty($subtypes)) {
				$subtypes_in = implode(',', $subtypes);
				$or_clauses[] = "{$table}subtype IN ({$subtypes_in})";
			} else {
				$or_clauses[] = "{$table}type = {$type}";
			}
		}

		if ($or_clauses) {
			unset($return['ands']['quarantine_status']);
			$return['ors']['quarantine_status'] = '(' . implode(' OR ', $or_clauses) . ')';
		}

		return $return;
	}
}