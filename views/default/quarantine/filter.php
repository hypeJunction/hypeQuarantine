<?php

$filter = elgg_extract('filter', $vars);

$tabs = [];

$codes = \hypeJunction\Quarantine\Policy::getStatusCodes();

foreach ($codes as $code) {
	$tabs[] = [
		'name' => $code,
		'text' => elgg_echo("quarantine:change_status:$code"),
		'href' => "quarantine?filter=$code",
		'selected' => $code == $filter,
	];
}

echo elgg_view_menu('filter', [
	'items' => $tabs,
	'sort_by' => 'priority',
]);
