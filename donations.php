<?php

 // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

   

include 'Slim/Slim.php';

$app = new Slim();

$app->get('/donations', 'getDonations');
$app->get('/donations/:id', 'getDonation');
$app->post('/donations', 'addDonation');
$app->put('/donations/:id', 'updateDonation');
$app->delete('/donations/:id', 'deleteDonation');
$app->post('/donations/search', 'findByParameter');

$app->run();

function getDonations($Contact_id) {
    $sql = "SELECT id, Date, Amount FROM donations WHERE Contact_id=:Contact_id ORDER BY Date";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("Contact_id", $Contact_id);
        $stmt->execute();
        $donations = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"donations": ' . json_encode($donations) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getDonation($id) {
    $sql = "SELECT * FROM donations WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $donation = $stmt->fetchObject();
        $db = null;
        echo json_encode($donation);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addDonation() {
    $request = Slim::getInstance()->request();
    $donation = json_decode($request->getBody());
    $sql = "INSERT INTO donations

    (Contact_id, Date, Amount) 

    VALUES

    (:Contact_id, :Date, :Amount)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("Contact_id", $donation->Contact_id);
        $stmt->bindParam("Date", $donation->Date);
        $stmt->bindParam("Amount", $donation->Amount);

        $stmt->execute();
        $donation->id = $db->lastInsertId();
        $db = null;
        echo json_encode($donation);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateDonation($id) {
    echo '{"error": "Update function not availible for this API. Please delete and add new instead."}}';
}

function deleteDonation($id) {
    $sql = "DELETE FROM donations WHERE id=:id";
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
    echo '{"error": "Search function not availible for this API. Please use a local search instead."}}';
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
