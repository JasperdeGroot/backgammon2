<?php
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// web/index.php
$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

// ROUTING
$app->get('/', function (Request $request) use ($app) {
    return $app['twig']->render('index.twig', array(
        'action' => 'register'
    ));
});

$app->get('/register', function (Request $request) use ($app) {
    return $app['twig']->render('register.twig', array(
        'action' => 'register'
    ));
});

$app->post('/register', function (Request $request) use ($app) {
    $register = new Model\Register(new Model\Registry\FileCsv(__DIR__ .'/../data/users.csv'));
    try {
        $register->user(
            $request->get('email'), 
            $request->get('alias'),
            $request->get('password'));
    // indien geslaagd redirect naar lobby 
    return $app->redirect('/lobby/' . $request->get('alias'));
    } catch (Exception $e){
        // niet geslaagd probeer het opnieuw
        // TODO een fout melding op de pagina weergeven
        return $app->redirect('/register/');
    }
});

$app->get('/lobby/{name}', function (Request $request, $name) use ($app){
    $register = new Model\Register(new Model\Registry\FileCsv(__DIR__ .'/../data/users.csv'));
    return $app['twig']->render('lobby.twig', array(
        'name' => $name,
        'list' => $register->listUsers()
        
    ));
});

$app->run();
