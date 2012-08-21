<?php
// doctrine.php - Put in your application root

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;

if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

$bootstrap = dirname(dirname(__FILE__)) . DS. 'src' . DS . 'bootstrap.php';
require_once($bootstrap);

// Any way to access the EntityManager from  your application
$em = EntityManagerFactory::getNewManager();

$helperSet = new HelperSet(array(
    'db' => new ConnectionHelper($em->getConnection()),
    'em' => new EntityManagerHelper($em)
));

ConsoleRunner::run($helperSet);
