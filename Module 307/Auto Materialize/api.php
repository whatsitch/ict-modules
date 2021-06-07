<?php


/*
 *
 * Methods: tanken, delete, insert, update, get, get_by_id
 * Optional: id
 *
 */

define('DB_NAME', 'tester123');
define('DB_USER', 'm307_adm');
define('DB_PSWD', 'NuwrQvd1nTZvpYvI');
define('DB_HOST', 'localhost');

$response = [];

checkDB();

$conn = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if($conn->connect_errno) {
    $response['success'] = false;
    $response['data'] = 'DB-Connection error';
}


$method = 'none';
if(isset($_GET['method'])) {
    $method = $_GET['method'];
    $request = $_GET;
}
if(isset($_POST['method'])) {
    $method = $_POST['method'];
    $request = $_POST;
}


if($method !== 'none' && empty($response)) {
    switch ($method) {
        case 'tanken':
            $success = true;
            if(isset($_REQUEST['id'])) {
                $sql = 'UPDATE autos SET `tank` = (`tank` + 1) WHERE `id` = ' . $_REQUEST['id'];
                $conn->query($sql);
            } else {
                $success = false;
                $response['error'] = 'id not set';
            }
            $response['success'] = $success;
            $response['method'] = 'tanken';
            break;
        case 'delete':  // GET, Method, ID

            $success = true;
            if(isset($_REQUEST['id'])) {
                $sql = 'DELETE FROM autos WHERE `id` = ' . $_REQUEST['id'];
                $conn->query($sql);
            } else {
                $success = false;
            }
            $response['success'] = $success;
            $response['method'] = 'delete';

            break;
        case 'insert': // Post, Method and Form
            $name       = $_REQUEST['name'];
            $kraftstoff = $_REQUEST['kraftstoff'];
            $farbe      = $_REQUEST['color'];
            $bauart     = $_REQUEST['type'];
            $tank       = $_REQUEST['tank'];

            $query = "INSERT INTO autos (`name`, `kraftstoff`, `farbe`, `bauart`, `tank`) VALUES ('$name', '$kraftstoff', '$farbe', '$bauart', '$tank')";

            $conn->query($query);

            $response['debug'] = $conn->error;
            $response['debug2'] = $query;

            $response['success'] = true;
            $response['method'] = 'insert';
            $response['id'] = $conn->insert_id;;
            break;
        case 'update':
            $name       = $_REQUEST['name'];
            $kraftstoff = $_REQUEST['kraftstoff'];
            $farbe      = $_REQUEST['color'];
            $bauart     = $_REQUEST['type'];
            $tank       = $_REQUEST['tank'];
            $id         = $_REQUEST['id'];

            $query = "UPDATE autos SET `tank`=$tank , `name`='$name', `kraftstoff`='$kraftstoff', `farbe`='$farbe', `bauart`='$bauart' WHERE `id`=$id";
            $conn->query($query);

            $response['success'] = true;
            $response['method'] = 'update';
            $response['id'] = $id;
            break;
        case 'get':
            $query = 'SELECT * FROM autos';
            $id = isset($request['id']) ? intval($request['id']) : -1;
            if($id !== -1) $query .= ' WHERE `id`=' . $id . ';';

            $result = $conn->query($query);

            $data = [];

            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }

            $response['success'] = true;
            $response['method'] = 'delete';
            $response['data'] = $data;
            break;
        default:
            $response['success'] = false;
            $response['method'] = $method;
            $response['data'] = 'unknown method';
            break;
    }

} else {
    $response['success'] = false;
    $response['data'] = 'no method declared';
}

echo json_encode($response);

function checkDB() {
    $con = NULL;
    if($con = new mysqli(DB_HOST, DB_USER, DB_PSWD)) {
        if (!$con->select_db(DB_NAME)) {
            $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " DEFAULT CHARACTER SET utf8";
            $con->query($sql);
            $con->select_db(DB_NAME);
            $sql = "CREATE TABLE autos (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                kraftstoff VARCHAR(255) NOT NULL,
                farbe VARCHAR(9) NOT NULL,
                bauart enum('SUV', 'Limousine', 'Bus'),
                tank INT NOT NULL,
                PRIMARY KEY(id)
            )";

            $con->query($sql);

            $sql = "INSERT INTO
                      autos (name, kraftstoff, farbe, bauart, tank)
                    VALUES
                      ('Opel', 'Benzin', '#000000', 'SUV', 1),
                      ('Audi', 'Diesel', '#ffffff', 'Limousine', 50),
                      ('BMW', 'Diesel', '#ff0000', 'Limousine', 5)";
            $con->query($sql);
        }
    }
}

?>