<?php 
return [
		//'image'  => 'Snap\Decorator\Types\ImageDecorator',
		'media'   => 'Snap\Decorator\Types\MediaDecorator',
		'date'    => 'Snap\Decorator\Types\DateDecorator',
		'link'    => ['class' => 'Snap\Decorator\Types\LinkDecorator', 'priority' => 2],
		'array'   => 'Snap\Decorator\Types\ArrayDecorator',
		'number'  => 'Snap\Decorator\Types\NumberDecorator',
		'string'  => 'Snap\Decorator\Types\StringDecorator',
		'boolean' => 'Snap\Decorator\Types\BooleanDecorator',
		'email'   => 'Snap\Decorator\Types\EmailDecorator',
	];