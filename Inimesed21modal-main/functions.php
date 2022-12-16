<?php
require ('conf.php');

// tagastab isAdmin session
function isAdmin(){
    return isset($_SESSION['onAdmin'])&&$_SESSION['onAdmin'];
}

//sorteerimine
function countryData($sort_by = "eesnimi") {
    global $connection;
    $sort_list = array("eesnimi", "perekonnanimi");
    if(!in_array($sort_by, $sort_list)) {
        return "Seda tulpa ei saa sorteerida";
    }
    $request = $connection->prepare("SELECT arvutiID, arvutiNimi, pilt, hind FROM arvuti");
    $request->bind_result($id, $eesnimi, $perekonnanimi, $hind);
    $request->execute();
    $data = array();
    while($request->fetch()) {
        $person = new stdClass();
        $person->id = $id;
        $person->eesnimi = htmlspecialchars($eesnimi);
        $person->perekonnanimi = htmlspecialchars($perekonnanimi);
        $person->hind = htmlspecialchars($hind);
        array_push($data, $person);
    }
    return $data;
}
function createSelect($query, $name) { /** valitud rea näitamine */
    global $connection;
    $query = $connection->prepare($query);
    $query->bind_result($id, $data);
    $query->execute();
    $result = "<select name='$name'>";
    while($query->fetch()) {
        $result .= "<option value='$id'>$data</option>";
    }
    $result .= "</select>";
    return $result;
}
function addPerson($first_name, $last_name, $hind) { /** Inimese andmete lisamine andmetabelisse */
    global $connection;
    $query = $connection->prepare("INSERT INTO arvuti (arvutiNimi, pilt, hind) VALUES (?, ?, ?)");
    $query->bind_param("ssd", $first_name, $last_name, $hind);
    $query->execute();
}
function deletePerson($person_id) { /** Inimese andmete kustutamine */
    global $connection;
    $query = $connection->prepare("DELETE FROM arvuti WHERE arvutiID=?");
    $query->bind_param("i", $person_id);
    $query->execute();
}
//Inimese andmete muutmine
function savePerson($person_id, $first_name, $last_name, $hind) {
    global $connection;
    $query = $connection->prepare("UPDATE arvuti SET arvutiNimi=?, pilt=?, hind=? WHERE arvutiID=?");
    $query->bind_param("ssdi", $first_name, $last_name, $hind, $person_id);
    $query->execute();
}
?>