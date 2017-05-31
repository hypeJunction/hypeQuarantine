<?php

$guid = elgg_extract('guid', $vars);

elgg_ajax_gatekeeper();
elgg_entity_gatekeeper($guid);

$entity = get_entity($guid);

if (!\hypeJunction\Quarantine\Policy::canChangeStatus($entity)) {
	forward('', '403');
}

echo elgg_view_form('quarantine/change_status', [], [
	'entity' => $entity,
]);
