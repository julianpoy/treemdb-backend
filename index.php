<?php

include 'Slim/Slim.php';

$app = new Slim();

$app->get('/contacts', 'getContacts');
$app->get('/contacts/:id', 'getContact');
$app->post('/contacts', 'addContact');
$app->put('/contacts/:id', 'updateContact');
$app->delete('/contacts/:id', 'deleteContact');
$app->get('/contacts/search/:query', 'findByParameter');

$app->run();

function getContacts() {
	$sql = "SELECT * FROM contacts ORDER BY name";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"contacts": ' . json_encode($contacts) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getContact($id) {
	$sql = "SELECT * FROM contacts WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $contact = $stmt->fetchObject();
        $db = null;
        echo json_encode($contact);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addContact() {
	$request = Slim::getInstance()->request();
    $contact = json_decode($request->getBody());
    $sql = "INSERT INTO contacts (name) VALUES (:name)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $contact->name);
        $stmt->execute();
        $contact->id = $db->lastInsertId();
        $db = null;
        echo json_encode($contact);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateContact($id) {
	$request = Slim::getInstance()->request();
    $body = $request->getBody();
    $contact = json_decode($body);
    $sql = "UPDATE contacts SET name=:name WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $contact->name);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($contact);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteContact($id) {
	$sql = "DELETE FROM contacts WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function findByParameter($query) {
	$sql = "SELECT * FROM contacts WHERE UPPER(name) LIKE :query ORDER BY name";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $query = "%".$query."%";
        $stmt->bindParam("query", $query);
        $stmt->execute();
        $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"contacts": ' . json_encode($contacts) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getConnection() {
	$dbhost="kondeo.com";
	$dbuser="treemadmin";
	$dbpass="13Brownies";
	$dbname="treemdb";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>
