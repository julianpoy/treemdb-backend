<?php

header("Access-Control-Allow-Origin: *");

include 'Slim/Slim.php';

$app = new Slim();

$app->get('/contacts', 'getContacts');
$app->get('/contacts/:id', 'getContact');
$app->post('/contacts', 'addContact');
$app->put('/contacts/:id', 'updateContact');
$app->delete('/contacts/:id', 'deleteContact');
$app->post('/contacts/search', 'findByParameter');

$app->run();

function getContacts() {
	$sql = "SELECT id, FirstName, LastName FROM contacts ORDER BY LastName";
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

function findByParameter() {
	$request = Slim::getInstance()->request();
    $requestparams = json_decode($request->getBody());
    //$requestparams->FirstName = $FirstName;
    //$requestparams->LastName = $LastName;
    //$requestparams->Address1 = $Address1;
    $FirstName = $requestparams->FirstName;
    $LastName = $requestparams->LastName;
    $Address1 = $requestparams->Address1;
    $Email1 = $requestparams->Email1;
    $Phone1 = $requestparams->Phone1;
    $City = $requestparams->City;

    // Keep track of received parameters
    $paramsreceived = 0;

    // Check parameters for activity. If not active, assign wildcard for search.
    // Additionally, add to the received counter if active.
    if(isset($requestparams->FirstName)) {
    	$FirstName = "%".$FirstName."%";
    	$paramsreceived = $paramsreceived + 1;
    } else {
    	$FirstName = "%";
    }
    if(isset($requestparams->LastName)) {
    	$LastName = "%".$LastName."%";
    	$paramsreceived = $paramsreceived + 1;
    } else {
    	$LastName = "%";
    }
    if(isset($requestparams->Address1)) {
    	$Address1 = "%".$Address1."%";
    	$paramsreceived = $paramsreceived + 1;
    } else {
    	$Address1 = "%";
    }
    if(isset($requestparams->Email1)) {
    	$Email1 = "%".$Email1."%";
    	$paramsreceived = $paramsreceived + 1;
    } else {
    	$Email1 = "%";
    }
    if(isset($requestparams->Phone1)) {
    	$Phone1 = "%".$Phone1."%";
    	$paramsreceived = $paramsreceived + 1;
    } else {
    	$Phone1 = "%";
    }
    if(isset($requestparams->City)) {
    	$City = "%".$City."%";
    	$paramsreceived = $paramsreceived + 1;
    } else {
    	$City = "%";
    }

    // If no parameters are active, throw an error and exit.
    // If this were not here, the entire database would be returned when no parameters were entered.
    if($paramsreceived = 0){
    	echo '{"error":{"text":'. $e->getMessage() .'}}';
    	exit;
    }

    $sql = "SELECT * FROM contacts WHERE
    	FirstName LIKE :firstname AND
    	LastName LIKE :lastname AND
    	Address1 LIKE :address1 AND
    	Email LIKE :email1 AND
    	HomePhone LIKE :phone1 AND
    	City LIKE :city 
    ORDER BY LastName
    LIMIT 200";
    

    
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        //$query = "%".$query."%";
        
        $stmt->bindParam("firstname", $FirstName);
        $stmt->bindParam("lastname", $LastName);
        $stmt->bindParam("address1", $Address1);
        $stmt->bindParam("email1", $Email1);
        $stmt->bindParam("phone1", $Phone1);
        $stmt->bindParam("city", $City);

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
