<?php

$dbprefix = elgg_get_config('dbprefix');

$status_code = \hypeJunction\Quarantine\QuarantineService::STATUS_PENDING;

echo elgg_list_entities([
	'types' => ['object', 'group'],
	'joins' => [
		"JOIN {$dbprefix}quarantine qr ON e.guid = qr.guid",
	],
	'wheres' => [
		"qr.status = $status_code",
	],
	'full_view' => false,
]);