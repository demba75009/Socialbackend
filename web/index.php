<?php

// web/index.php
require_once __DIR__.'/../vendor/autoload.php'; // on importe le fichier autoload.php

$app = new Silex\Application(); // On demarre l'application
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',//on importe twig.path 
));

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

/*
$blogPosts = array(
    1 => array(
        
        'date'      => '2011-03-29',
        'author'    => 'igaaw',
        'title'     => 'Using Silex',
        'body'      => '...',
        'id'      => '100',
    ),
      2 => array(
       'id'      => '12',
        'date'      => '2011-03-29',
        'author'    => 'ig',
        'title'     => 'Using Silex',
        'body'      => '...',
    ),
    
      3 => array(
       'id'      => '50',
        'date'      => '2011-03-29',
        'author'    => 'igobbbb',
        'title'     => 'Using Silex',
        'body'      => '...',
    ),
);

//get route : example

$app->get('/blog', function () use ($app,$blogPosts) { //on met en parametre de l'url blog 
   
    return $app['twig']->render('blog.twig',array( //ON AFFICHE LE TABLEAU avec les parametre mis dans le fichier blog.twig
		'posts' =>$blogPosts,
	
     ));
});

$app->get('/blog/{id}', function (Silex\Application $app, $id) use ($blogPosts){
	foreach($blogPosts as $post){
		if($post['id'] == $id ){
			return $app['twig']->render('post.twig',array('posts' =>$blogPosts,
			));
	}}
});
	*/
	
	$app->get('/api/blog', function () use ($app) {
		$posts =$app['db']->fetchAll('SELECT * FROM posts');
	return json_encode($posts);
	});



$app->run(); // on compile
