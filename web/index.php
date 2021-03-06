<?php

require_once('../vendor/autoload.php');
require_once("model/Textbook.php");
require_once('controller/TextbookController.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
    ));

// Register the Twig templating engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
    ));

// Register databse
$dbopts = parse_url(getenv('DATABASE_URL'));
$app->register(new Herrera\Pdo\PdoServiceProvider(),
    array(
        'pdo.dsn'      => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"],
        'pdo.port'     => $dbopts["port"],
        'pdo.username' => $dbopts["user"],
        'pdo.password' => $dbopts["pass"]
        )
    );

TextbookController::establish($app['pdo']);

// web handlers
$app->get('/', function() use($app) {
    return $app['twig']->render('index.twig', array(
        'textbooks' => TextbookController::fetchAllTextbook()
        ));
});

$app->get('/success', function() use($app) {
    return $app['twig']->render('index.twig', array(
		'textbooks' => TextbookController::fetchAllTextbook(),
		'success'   => '1',
        ));
});

$app->get('/failed', function() use($app) {
    return $app['twig']->render('index.twig', array(
		'textbooks' => TextbookController::fetchAllTextbook(),
		'failed'    => '1',
        ));
});

$app->get('/mark/{ids}', function($ids) use($app) {
    TextbookController::markSold(json_decode($ids));
    // small trick to hide the get request from the users
    return $app['twig']->render('mark.twig', array());
});

$app->run();

?>
