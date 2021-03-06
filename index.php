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

    if(!isset($contact->Prefix)){
        $contact->Prefix = "";
    }
    if(!isset($contact->FirstName)){
        $contact->FirstName = "";
    }
    if(!isset($contact->LastName)){
        $contact->LastName = "";
    }
    if(!isset($contact->Company)){
        $contact->Company = "";
    }
    if(!isset($contact->Title)){
        $contact->Title = "";
    }
    if(!isset($contact->HomePhone)){
        $contact->HomePhone = "";
    }
    if(!isset($contact->WorkPhone)){
        $contact->WorkPhone = "";
    }
    if(!isset($contact->CellPhone)){
        $contact->CellPhone = "";
    }
    if(!isset($contact->Fax)){
        $contact->Fax = "";
    }
    if(!isset($contact->Email)){
        $contact->Email = "";
    }
    if(!isset($contact->WebAddress)){
        $contact->WebAddress = "";
    }
    if(!isset($contact->Address1)){
        $contact->Address1 = "";
    }
    if(!isset($contact->Address2)){
        $contact->Address2 = "";
    }
    if(!isset($contact->City)){
        $contact->City = "";
    }
    if(!isset($contact->StateRegion)){
        $contact->StateRegion = "";
    }
    if(!isset($contact->Zip)){
        $contact->Zip = "";
    }
    if(!isset($contact->Country)){
        $contact->Country = "";
    }
    if(!isset($contact->AdditionalInfo)){
        $contact->AdditionalInfo = "";
    }
    if(!isset($contact->Notes)){
        $contact->Notes = "";
    }
    if(!isset($contact->CurbSideNotes)){
        $contact->CurbSideNotes = "";
    }
    //Flags
    if(!isset($contact->YMT)){
        $contact->YMT = "0";
    }
    if(!isset($contact->YouthDirector)){
        $contact->YouthDirector = "0";
    }
    if(!isset($contact->Board)){
        $contact->Board = "0";
    }
    if(!isset($contact->APT)){
        $contact->APT = "0";
    }
    if(!isset($contact->TreeGuardian)){
        $contact->TreeGuardian = "0";
    }
    if(!isset($contact->FosterCare)){
        $contact->FosterCare = "0";
    }
    if(!isset($contact->Volunteer)){
        $contact->Volunteer = "0";
    }
    if(!isset($contact->Small)){
        $contact->Small = "0";
    }
    if(!isset($contact->Tall)){
        $contact->Tall = "0";
    }


    $sql = "INSERT INTO contacts

    (Prefix, FirstName, LastName, Company, Title,
        HomePhone, WorkPhone, CellPhone, Fax,
        Email, WebAddress, Address1, Address2,
        City, StateRegion, Zip, Country,
        AdditionalInfo, Notes, CurbSideNotes, YMT,
        YouthDirector, Board, APT, TreeGuardian,
        FosterCare, Volunteer, Small, Tall)

    VALUES

    (:Prefix, :FirstName, :LastName, :Company, :Title,
        :HomePhone, :WorkPhone, :CellPhone, :Fax,
        :Email, :WebAddress, :Address1, :Address2,
        :City, :StateRegion, :Zip, :Country,
        :AdditionalInfo, :Notes, :CurbSideNotes, :YMT,
        :YouthDirector, :Board, :APT, :TreeGuardian,
        :FosterCare, :Volunteer, :Small, :Tall)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("Prefix", $contact->Prefix);
        $stmt->bindParam("FirstName", $contact->FirstName);
        $stmt->bindParam("LastName", $contact->LastName);
        $stmt->bindParam("Company", $contact->Company);
        $stmt->bindParam("Title", $contact->Title);
        $stmt->bindParam("HomePhone", $contact->HomePhone);
        $stmt->bindParam("WorkPhone", $contact->WorkPhone);
        $stmt->bindParam("CellPhone", $contact->CellPhone);
        $stmt->bindParam("Fax", $contact->Fax);
        $stmt->bindParam("Email", $contact->Email);
        $stmt->bindParam("WebAddress", $contact->WebAddress);
        $stmt->bindParam("Address1", $contact->Address1);
        $stmt->bindParam("Address2", $contact->Address2);
        $stmt->bindParam("City", $contact->City);
        $stmt->bindParam("StateRegion", $contact->StateRegion);
        $stmt->bindParam("Zip", $contact->Zip);
        $stmt->bindParam("Country", $contact->Country);
        $stmt->bindParam("AdditionalInfo", $contact->AdditionalInfo);
        $stmt->bindParam("Notes", $contact->Notes);
        $stmt->bindParam("CurbSideNotes", $contact->CurbSideNotes);
        $stmt->bindParam("YMT", $contact->YMT);
        $stmt->bindParam("YouthDirector", $contact->YouthDirector);
        $stmt->bindParam("Board", $contact->Board);
        $stmt->bindParam("APT", $contact->APT);
        $stmt->bindParam("TreeGuardian", $contact->TreeGuardian);
        $stmt->bindParam("FosterCare", $contact->FosterCare);
        $stmt->bindParam("Volunteer", $contact->Volunteer);
        $stmt->bindParam("Small", $contact->Small);
        $stmt->bindParam("Tall", $contact->Tall);

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
    $sql = "UPDATE contacts
    SET
    Prefix=:Prefix,
    FirstName=:FirstName,
    LastName=:LastName,
    Company=:Company,
    Title=:Title,
    HomePhone=:HomePhone,
    WorkPhone=:WorkPhone,
    CellPhone=:CellPhone,
    Fax=:Fax,
    Email=:Email,
    WebAddress=:WebAddress,
    Address1=:Address1,
    Address2=:Address2,
    City=:City,
    StateRegion=:StateRegion,
    Zip=:Zip,
    Country=:Country,
    AdditionalInfo=:AdditionalInfo,
    Notes=:Notes,
    CurbSideNotes=:CurbSideNotes,
    YMT=:YMT,
    YouthDirector=:YouthDirector,
    Board=:Board,
    APT=:APT,
    TreeGuardian=:TreeGuardian,
    FosterCare=:FosterCare,
    Volunteer=:Volunteer,
    Small=:Small,
    Tall=:Tall

    WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);
        $stmt->bindParam("Prefix", $contact->Prefix);
        $stmt->bindParam("FirstName", $contact->FirstName);
        $stmt->bindParam("LastName", $contact->LastName);
        $stmt->bindParam("Company", $contact->Company);
        $stmt->bindParam("Title", $contact->Title);
        $stmt->bindParam("HomePhone", $contact->HomePhone);
        $stmt->bindParam("WorkPhone", $contact->WorkPhone);
        $stmt->bindParam("CellPhone", $contact->CellPhone);
        $stmt->bindParam("Fax", $contact->Fax);
        $stmt->bindParam("Email", $contact->Email);
        $stmt->bindParam("WebAddress", $contact->WebAddress);
        $stmt->bindParam("Address1", $contact->Address1);
        $stmt->bindParam("Address2", $contact->Address2);
        $stmt->bindParam("City", $contact->City);
        $stmt->bindParam("StateRegion", $contact->StateRegion);
        $stmt->bindParam("Zip", $contact->Zip);
        $stmt->bindParam("Country", $contact->Country);
        $stmt->bindParam("AdditionalInfo", $contact->AdditionalInfo);
        $stmt->bindParam("Notes", $contact->Notes);
        $stmt->bindParam("CurbSideNotes", $contact->CurbSideNotes);
        $stmt->bindParam("YMT", $contact->YMT);
        $stmt->bindParam("YouthDirector", $contact->YouthDirector);
        $stmt->bindParam("Board", $contact->Board);
        $stmt->bindParam("APT", $contact->APT);
        $stmt->bindParam("TreeGuardian", $contact->TreeGuardian);
        $stmt->bindParam("FosterCare", $contact->FosterCare);
        $stmt->bindParam("Volunteer", $contact->Volunteer);
        $stmt->bindParam("Small", $contact->Small);
        $stmt->bindParam("Tall", $contact->Tall);

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
    $sql = "DELETE FROM donations WHERE Contact_id=:id";
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
    $params = json_decode($request->getBody(), true);

    //Keep track of the keys a user could search for (anti-sql injection)
    $allowedKeys = array(
        "Prefix", "FirstName", "LastName", "Company", "Title",
        "HomePhone", "WorkPhone", "CellPhone", "Fax",
        "Email", "WebAddress", "Address1", "Address2",
        "City", "StateRegion", "Zip", "Country",
        "AdditionalInfo", "Notes", "CurbSideNotes", "YMT",
        "YouthDirector", "Board", "APT", "TreeGuardian",
        "FosterCare", "Volunteer", "Small", "Tall", "Absolute", "Random", "query");

    //Check all provided keys to ensure only valid keys are being passed
    $keys = array_keys($params);
    $fuzzy = true;
    $badKey;
    for($j = 0;$j<count($keys);$j++){
        if(!in_array($keys[$j], $allowedKeys)) $badKey = $keys[$j];
        if($keys[$j] == "Absolute" && $params[$keys[$j]] == "1"){
            $fuzzy = false;
            array_splice($keys, $j, 1);
            $j--;
        }
        if($keys[$j] == "Random" || $keys[$j] == "query" || $keys[$j] == "Absolute" || $params[$keys[$j]] == "undefined" || $params[$keys[$j]] == "0" || $params[$keys[$j]] == ""){
            array_splice($keys, $j, 1);
            $j--;
        }
    }

    // If no parameters are active, throw an error and exit.
    // If this were not here, the entire database would be returned when no parameters were entered.
    if(count($keys) == 0){
        echo '{"error":{"text":"You must send some search parameters."}}';
        exit;
    }

    if(isset($badKey)){
        echo '{"error":{"text":"You passed an invalid parameter ' . $badKey . '"}}';
        exit;
    }


    try {
        $db = getConnection();

        //Standard sql select to be concatinated to
        $sql = "SELECT * FROM contacts WHERE ";

        //Build query onto select from passed keys
        for($i=0;$i<count($keys);$i++){
            //Only on subsequent and not the last
            if($i != 0 && $i != count($params)-1) $sql .= "AND ";
            //Build the sql

            if($params[$keys[$i]] == "1"){
                $sql .= $keys[$i] . " = :" . $keys[$i] . " ";
            } else {
                $sql .= $keys[$i] . " LIKE :" . $keys[$i] . " ";
            }
        }

        //Finish the sql off
        $sql .= "ORDER BY LastName LIMIT 400 ";
        //Add to database preparation
        $stmt = $db->prepare($sql);

        //Values array to hold modified values
        //This is needed because it turns out pdo bindParam() passes by reference,
        //therefore each loop iteration would overwrite a single variable. Thus, an array is needed.
        $values = array();

        //Add values
        for($i=0;$i<count($keys);$i++){
            //Bind a parameter
            $value[$i] = $params[$keys[$i]];
            if($fuzzy && $value[$i] != "1") $value[$i] = "%" . $value[$i] . "%";
            if($value[$i] == "1") $value[$i] = 1;

            $stmt->bindParam($keys[$i], $value[$i]);
        }

        $stmt->execute();
        $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"contacts": ' . json_encode($contacts) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="treemdb";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

?>
