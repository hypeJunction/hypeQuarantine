<?php

$filter = get_input('filter', 'pending');
if (!elgg_view_exists("quarantine/listing/$filter")) {
	forward('', '404');
}

$title = elgg_echo("quarantine:status:$filter");
$content = elgg_view("quarantine/listing/$filter");
$filter = elgg_view('quarantine/filter', [
	'filter' => $filter,
]);

$layout = elgg_view_layout('content', [
	'filter' => $filter,
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $layout);