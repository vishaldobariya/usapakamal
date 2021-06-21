<?php

define('GRID_SIZES', [5, 10, 20, 50, 100, 200, 500]);

return [
	'icon-framework'      => 'fas',
	'bsVersion'           => '4.x',
	'bsDependencyEnabled' => false,
	'grid'                => [
		'default-size' => 20,
		'sizes'        => array_combine(GRID_SIZES, GRID_SIZES),
	],
];
