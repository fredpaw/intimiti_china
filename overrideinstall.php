<?php


// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}


require_once(DIR_SYSTEM . 'startup.php');
// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);
// Config
$config = new Config();
$registry->set('config', $config);



// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);





$query = $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `tpl` varchar(225) NOT NULL ");
$query = $db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `tpl` varchar(225) NOT NULL ");
$query = $db->query("ALTER TABLE `" . DB_PREFIX . "information` ADD `tpl` varchar(225) NOT NULL ");


echo "done";
die();
?>

