<?php

namespace Controller;

include('./Classes/Database.php');

use Database;

/**
 * @author Marco Eugster
 * @version 1.0
 * @category logic
 */
class Auto
{
    private $requestMethod = 'GET';
    public $response = [];
    private $writePermission = true;
    private $id = 0;
    private $db;

    function __construct()
    {
        /*----- initialize database instance -----*/
        $this->db = new Database();

        $this->requestMethod = $_SERVER["REQUEST_METHOD"];

        $this->id = isset($_GET['id']) ? $_GET['id'] : null;
        $this->response['id'] = isset($_GET['id']) ? $_GET['id'] : null;

        $this->{$this->requestMethod}();

        echo json_encode($this->response);
    }


    /*********************************
    ---------- REST methods ----------
    *********************************/
    function GET()
    {
        $sql = $this->id > 0
            ? "SELECT * FROM " . $this->db->table . " WHERE id=" . $this->id
            : "SELECT * FROM " . $this->db->table;

        $this->response['sql'] = $sql;
        $data = [];
        if ($result = $this->db->conn->query($sql)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            $this->response['data'] = $data;
            $this->response['success'] = true;
        } else {
            $this->response['success'] = false;
        }
    }
    function POST()
    {
        if (!($this->writePermission)) {
            $this->response['success'] = false;
            $this->response['error'] = "Authentication failed";
        }
        /*----- verification -----*/
        $name = $_POST['name'];
        // verify strings
        if(!($this->verifyString($name))) {
            $this->response['success'] = false;
            $this->response['error'] = "$name ist ungültig";
            return;
        }
        // verify numbers

        $bauart = $_POST['type'];
        $kraftstoff = $_POST['kraftstoff'];
        $farbe = $_POST['farbe'];
        $tank = $_POST['tank'];

        if ($this->id == 0) {
            $sql = "INSERT INTO " . $this->db->table . " (`name`, `kraftstoff`, `farbe`, `bauart`, `tank`) VALUES ('" . $name . "','" . $kraftstoff . "','" . $farbe . "','" . $bauart . "','" . $tank . "')";
            $this->response['method'] = "POST";
        } else {
            $sql = "UPDATE " . $this->db->table . " SET name = '" . $name . "', kraftstoff = '" . $kraftstoff . "', farbe = '" . $farbe . "', bauart = '" . $bauart . "', tank = '" . $tank . "' WHERE id = " . $this->id;
            $this->response['method'] = "PUT";
        }

        $this->response['sql'] = $sql;

        if ($this->db->conn->query($sql)) {
            $this->response['success'] = true;
            $this->response['success'] = true;
        } else {
            $this->response['success'] = false;
            $this->response['error'] = "Speichern fehlgeschlagen.";
        }
    }
    function DELETE()
    {
        if ($this->id > 0) {
            $sql = "DELETE FROM " . $this->db->table . " WHERE id = " . $this->id;
            $this->response['sql'] = $sql;
            if ($this->db->conn->query($sql)) {
                $this->response['success'] = true;
            } else {
                $this->response['success'] = false;
            }
        } else {
            $this->response['success'] = false;
        }
    }

    function TANKEN()
    {
        if ($this->id > 0) {
            $sql = "UPDATE " . $this->db->table . " SET tank = 100 WHERE id = " . $this->id;
            $this->response['sql'] = $sql;
            if ($this->db->conn->query($sql)) {
                $this->response['success'] = true;
            } else {
                $this->response['success'] = false;
            }
        }
    }




    /*************************************
    ---------- helper functions ----------
     *************************************/

    /**
     * verify string
     * @param string $string
     * @return bool true or false
     */
    function verifyString($string)
    {
        // verify string length
        if ($string == "" and strlen($string) < 5 and strlen($string) > 255) return false;
        // unerlaubte Zeichen
        if (preg_match('/[\'%&<>]/', $string)) {
            return false;
        }
        return true;
    }

        /**
     * verify integer
     * @param int 
     * @return bool true oder false
     */
    function verifyNumber($zahl){
        if (is_integer($zahl)) {
            if($zahl > 2147483647 OR $zahl < -2147483648){
                return false;
            }else{
                return true;
            }
        }else {
            return false;
        }
    }

        /**
     * verify email
     * @param string $string
     * @return bool true oder false
     */
    function verifyMail($string){
        // verify string length 
        if($string <> "" AND strlen($string) > 5 AND strlen($string) < 255){
            if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
                $array = explode('@', $string);
                if ( checkdnsrr($array[1], 'MX') ) {
                    $spam = array();
                    $spam[] = '10minutemail';
                    $spam[] = 'instantlyemail';
                    $spam[] = 'Tempmailer';
                    $spam[] = 'emaildeutschland';
                    $spam[] = 'dontmail';
                    $spam[] = 'migmail';
                    $spam[] = 'mailinator';
                    foreach($spam as $mail){
                        if(strstr($array[1], $mail)){
                            return false;
                        }
                    }
                }else {
                    return true;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
