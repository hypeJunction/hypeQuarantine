<?php

$entity = elgg_extract('entity', $vars);

if (!$entity) {
	return;
}

$svc = \hypeJunction\Quarantine\QuarantineService::getInstance();
$status = $svc->getStatus($entity);
if ($status == 'cleared') {
    return;
}

?>
<span class="quarantine-badge quarantine-badge-<?= $status ?>">
	<?= elgg_echo("quarantine:status:$status") ?>
</span>
