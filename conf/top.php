<?php

require_once('settings.php');

// Connexion à la BDD
try {
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$db  = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_BASE, DB_USER, DB_PASS, $pdo_options);
}   
catch(Exception $e) {
	// On error message display
	die('Error : '.$e->getMessage());
}

require_once(dirname(__FILE__).'/../'.LIB_PATH .'functions.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'products.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'artists.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'categories.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'cart.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'customers.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'address.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'orders.php');
require_once(dirname(__FILE__).'/../'.LIB_PATH .'imagine/Image.class.php');

/* Initialisation moteur Twig */
require_once(dirname(__FILE__).'/../lib/Twig/Autoloader.php');
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/../templates');
$tpl = new Twig_Environment($loader, array(
     'cache' => false, // Désactiver le cache en développement
     //'cache' => 'compilation_cache' // Décommenter cette ligne en production
));


// Extensions twig
require_once(dirname(__FILE__).'/../'.LIB_PATH .'TwigExtension.php');

