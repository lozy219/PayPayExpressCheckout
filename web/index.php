<?php

require('../vendor/autoload.php');
require('TextBookController.php');


if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
}

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
$dbopts = parse_url(getenv('DATABASE_URL'));
$app->register(new Herrera\Pdo\PdoServiceProvider(),
  array(
    'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"],
    'pdo.port' => $dbopts["port"],
    'pdo.username' => $dbopts["user"],
    'pdo.password' => $dbopts["pass"]
  )
);

$app['textBookController'] = new TextBookController($app['pdo']);

// web handlers
$app->get('/', function() use($app) {
	return $app['twig']->render('index.twig', array(
		'textbooks' => $app['textBookController']->fetchAllTextBook()
	));
});

$app->get('/success', function() use($app) {
  return $app['twig']->render('index.twig', array(
    'textbooks' => $app['textBookController']->fetchAllTextBook(),
    'success' => '1',
  ));
});

$app->get('/failed', function() use($app) {
  return $app['twig']->render('index.twig', array(
    'textbooks' => $app['textBookController']->fetchAllTextBook(),
    'failed' => '1',
  ));
});

$app->run();

?>
