<?php

// web/index.php
require_once __DIR__.'/../vendor/autoload.php'; // on importe le fichier autoload.php

$app = new Silex\Application(); // On demarre l'application
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',//on importe twig.path 
));

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


//on récupere les données de la base de donnée mysql crée
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array (
        
            'driver'    => 'pdo_mysql',
            'host'      => '127.0.0.1',
            'dbname'    => 'social',
            'user'      => 'root',
            'password'  => '',
            'charset'   => 'utf8mb4',
        ),
      
    
));





	$app->get('/api/blog', function () use ($app) {
		$posts =$app['db']->fetchAll('SELECT * FROM posts');
	return json_encode($posts);
	});
	
	$app->get('/api/blog/{id}', function ($id) use ($app) {
		$sql="SELECT * FROM posts WHERE id = ?";
		$post =$app['db']->fetchAll($sql, array((int) $id));
	return json_encode($post);
	});
	
	
	$app->post('/api/post', function (Request $request) use ($app) {
      $title = $request->get('title');
      $body = $request->get('body');
            $author = $request->get('author');
      
	$app['db']->insert('posts', array(
        'title' => $request ->get('title'),
         'body' => $body,
         'author' =>$author,
    ));
    
    $id = $app['db']->lastInsertId();
    $sql = "SELECT * FROM posts WHERE id = ?";
    $post = $app['db']->fetchAssoc($sql, array((int) $id));
    return json_encode($post);


      
      var_dump($title);
	});

Request::enableHttpMethodParameterOverride();


$app->run(); // on compile
