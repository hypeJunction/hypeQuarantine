<?php

use hypeJunction\Quarantine\QuarantineService;

elgg_set_context('quarantine');

$moderator = elgg_get_logged_in_user_entity();

$guid = get_input('guid');

elgg_entity_gatekeeper($guid);

$entity = get_entity($guid);

if (!\hypeJunction\Quarantine\Policy::canChangeStatus($entity)) {
	return elgg_error_response(elgg_echo('quarantine:change_status:error:permissions'));
}

$status_codes = \hypeJunction\Quarantine\Policy::getStatusCodes();
$status_code = get_input('status');
if (!isset($status_code) || !array_key_exists($status_code, $status_codes)) {
	return elgg_error_response(elgg_echo('quarantine:change_status:error:unknown_status'));
}

$svc = QuarantineService::getInstance();

if ($svc->quarantine($entity, $status_code)) {
	$note = get_input('note', '');
	if ($note) {
		$note = elgg_echo("quarantine:notify:note", [$note]);
	}

	$status = $status_codes[$status_code];

	$subject = elgg_echo("quarantine:notify:$status:subject");
	$message = elgg_echo("quarantine:notify:$status:message", [
		$moderator->getDisplayName(),
		$entity->getDisplayName() ? : elgg_echo('untitled'),
		$note,
		$entity->getURL(),
		$moderator->getURL(),
	]);

	notify_user($entity->owner_guid, elgg_get_logged_in_user_guid(), $subject, $message, [
		'action' => $status,
		'object' => $entity,
		'actor' => $moderator,
		'url' => $entity->getURL(),
	]);

	if ($status_code == QuarantineService::STATUS_REJECTED && get_input('delete') && $entity->canDelete()) {
		$entity->delete();
	}

	return elgg_ok_response('', elgg_echo('quarantine:change_status:success'));
} else {
	return elgg_error_response(elgg_echo('quarantine:change_status:error'));
}
