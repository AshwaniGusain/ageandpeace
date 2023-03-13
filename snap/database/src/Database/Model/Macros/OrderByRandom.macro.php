<?php 

\Illuminate\Database\Query\Builder::macro('orderByRandom', function () {
	$randomFunctions = [
		'mysql' => 'RAND()',
		'pgsql' => 'RANDOM()',
		'sqlite' => 'RANDOM()',
		'sqlsrv' => 'NEWID()',
	];
	$driver = $this->getConnection()->getDriverName();
	return $this->orderByRaw($randomFunctions[$driver]);
});