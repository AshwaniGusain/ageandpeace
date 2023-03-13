# Snap Decorator
A decorator package for the Laravel SNAP toolkit.

## Examples
	Decorator::bind('image' => ['class' => 'Daylight\Fuel\Data\ImageData']);
	Decorator::bind('date' => 'Daylight\Fuel\Data\DateData');
	$data = Decorator::make(['h1' => 'This is an array']);

	$data->add('h1', 'This is an h1', 'auto');
	$data->add('date', 'This is an h1', 'date');
	$data['h1'] = 'This is an h1';
	$data['h1'] = 'date::This is an h1';
	$data['h1']->lower();