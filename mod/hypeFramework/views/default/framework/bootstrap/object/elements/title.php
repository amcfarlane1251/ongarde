<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) return true;

echo elgg_view('output/url', array(
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'title' => $entity->title,
	'class' => 'elgg-entity-title',
	'is_trusted' => true
));
