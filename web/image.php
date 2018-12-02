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
  // Create database connection
  $db = mysqli_connect("localhost", "root", "", "social");
  
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
		$posts =$app['db']->fetchAll('SELECT * FROM image');
	return json_encode($posts);
	});
	$app->post('/api/post', function (Request $request) use ($app) {
      $image = $request->get('image');
      $image_text = $request->get('image_text');
      
	$app['db']->insert('image', array(
        'image' => $request ->get('image'),
        'text' => $request->get('image_text'),

     
    ));
     $id = $app['db']->lastInsertId();
    $sql = "SELECT * FROM image WHERE id = ?";
    $post = $app['db']->fetchAssoc($sql, array((int) $id));
    return json_encode($post);


      
      var_dump($image);
	});

    
    $app->run(); // on compile

  // Initialize message variable
  $msg = "";

  // If upload button is clicked ...
  if (isset($_POST['upload'])) {
  	// Get image name
  	$image = $_FILES['image']['name'];
  	// Get text
  	$image_text = mysqli_real_escape_string($db, $_POST['image_text']);

  	// image file directory
  	$target = "image/".basename($image);

  	$sql = "INSERT INTO image (image, image_text) VALUES ('$image', '$image_text')";
  	// execute query
  	mysqli_query($db, $sql);

  	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
  		$msg = "Image uploaded successfully";
  	}else{
  		$msg = "Failed to upload image";
  	}
  }
  $result = mysqli_query($db, "SELECT * FROM image");
?>
<!DOCTYPE html>
<html>
<head>
<title>Image Upload</title>
<style type="text/css">
   #content{
   	width: 50%;
   	margin: 20px auto;
   	border: 1px solid #cbcbcb;
   }
   form{
   	width: 50%;
   	margin: 20px auto;
   }
   form div{
   	margin-top: 5px;
   }
   #img_div{
   	width: 80%;
   	padding: 5px;
   	margin: 15px auto;
   	border: 1px solid #cbcbcb;
   }
   #img_div:after{
   	content: "";
   	display: block;
   	clear: both;
   }
   img{
   	float: left;
   	margin: 5px;
   	width: 300px;
   	height: 140px;
   }
</style>
</head>
<body>
<div id="content">

  <form method="POST" action="http://localhost/social/web/image.php/api/post?image= &image_text=" enctype="multipart/form-data">
  	<input type="hidden" name="size" value="1000000">
  	<div>
  	  <input type="file" name="image">
  	</div>
  	<div>
      <textarea 
      	id="text" 
      	cols="40" 
      	rows="4" 
      	name="image_text" 
      	placeholder="Say something about this image..."></textarea>
  	</div>
  	<div>
  		<button type="submit" name="upload">POST</button>
  	</div>
  </form>
</div>
</body>
</html>
