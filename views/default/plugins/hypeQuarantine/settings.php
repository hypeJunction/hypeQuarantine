<?php

$entity = elgg_extract('entity', $vars);

$types = get_registered_entity_types();
unset($types['user']);

$fields = [];
foreach ($types as $type => $subtypes) {
	if (empty($subtypes)) {
		$subtypes = ['default'];
	}

	foreach ($subtypes as $subtype) {
		$key = $subtype == 'default' ? "item:$type" : "item:$type:$subtype";

		$fields[] = [
			'#type' => 'checkbox',
			'label' => elgg_echo($key),
			'name' => "params[quarantine:$type:$subtype]",
			'value' => 1,
			'default' => 0,
			'checked' => (bool) $entity->{"quarantine:$type:$subtype"},
		];
	}
}

echo elgg_view_field([
	'#type' => 'fieldset',
	'#label' => elgg_echo('quarantine:setting:quarantine'),
	'fields' => $fields,
]);