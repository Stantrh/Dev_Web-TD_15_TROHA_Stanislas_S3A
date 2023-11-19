<?php
session_start();

require_once '../vendor/autoload.php';

// Utilisation des alias
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\dispatch\Dispatcher;

ConnectionFactory::setConfig(__DIR__ . '/../config/.ini');
ConnectionFactory::makeConnection();

$dispatcher =  new Dispatcher();
$dispatcher->run();
