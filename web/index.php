<?php

require('../vendor/autoload.php');
require('TextBookController.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register the Twig templating engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/views',
));

// Register databse
$dbopts = parse_url("postgres://oyiauwtvjccquc:M7oFgcrR7pgtDL0LVmF50A5ZrO@ec2-184-73-221-47.compute-1.amazonaws.com:5432/dbehetivepig6p");  
$app->register(new Herrera\Pdo\PdoServiceProvider(),
  array(
    'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"],
    'pdo.port' => $dbopts["port"],
    'pdo.username' => $dbopts["user"],
    'pdo.password' => $dbopts["pass"]
  )
);

$app['textBookController'] = new TextBookController($app);

// Our web handlers

$app->get('/', function() use($app) {
  // $app['monolog']->addDebug('logging output.');
  // return 'Hello';
	return $app['twig']->render('index.twig', array(
		'textbooks' => $app['textBookController']->fetchAllTextBook(),
	));
});

$app->get('/twig/{name}', function ($name) use ($app) {
    return $app['twig']->render('index.twig', array(
        'name' => $name,
    ));
});

$app->run();

?>
