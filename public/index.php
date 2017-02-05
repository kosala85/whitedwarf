<?php

try
{
	require(__DIR__ . '/../api/bootstrap.php');

	$app->run();
}
catch(\Exception $exception)
{
	require(__DIR__ . '/../api/anomaly.php');
}
