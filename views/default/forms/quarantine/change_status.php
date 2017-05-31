<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

$svc = \hypeJunction\Quarantine\QuarantineService::getInstance();
$status = $svc->getStatus($entity, true);

$codes = \hypeJunction\Quarantine\Policy::getStatusCodes();

foreach ($codes as &$value) {
	$value = elgg_echo("quarantine:change_status:$value");
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo("quarantine:change_status:status"),
	'name' => 'status',
	'value' => $status,
	'options_values' => $codes,
	'class' => 'quarantine-status-select',
]);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('quarantine:change_status:note'),
	'name' => 'note',
	'rows' => 2,
]);

if ($entity->canDelete()) {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'#class' => [
			'quarantine-delete-field',
			$status == -7 ? '' : 'hidden',
		],
		'label' => elgg_echo('quarantine:change_status:delete'),
		'name' => 'delete',
		'default' => false,
		'value' => 1,
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);

?>
<script>
    require(['forms/quarantine/change_status']);
</script>

