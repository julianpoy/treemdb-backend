<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/contacts', 'getContacts');
$app->get('/contacts/:id', 'getContact');
$app->get('/contacts/search/:query', 'findByParameter');
$app->post('/contacts', 'addContact');
$app->put('/contacts/:id', 'updateContact');
$app->delete('/contacts/:id', 'deleteContact');

$app->run();

function getContacts() {
	
}

function getContact($id) {
	
}

function addContact() {
	
}

function updateContact($id) {
	
}

function deleteContact($id) {
	
}

function findByParameter($query) {
	
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="contacts";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>
