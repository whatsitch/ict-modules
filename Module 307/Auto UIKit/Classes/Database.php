<?php

/**
 * @author Marco Eugster
 * @version 1.0
 * @category database
 */
class Database
{
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "";
    public $dbname = "autodb2";
    public $table = "auto";
    public $conn;

    public function __construct()
    {
        try {
            /*----- verify database -----*/
            $this->checkDB();
            /*----- get connection -----*/
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        } catch (Exception $err) {
            echo $err->getMessage();
        }
    }
    /**
     * Class destructor.
     */
    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    function checkDB()
    {
        /*----- verify connection -----*/
        if ($this->conn = mysqli_connect($this->servername, $this->username, $this->password)) {
            /*----- verify database -----*/
            if ($this->conn->select_db($this->dbname)) {
            } else {
                /*----- database doesn't exist -----*/
                $this->conn = new mysqli($this->servername, $this->username, $this->password);

                $sql = "CREATE DATABASE IF NOT EXISTS " . $this->dbname . " DEFAULT CHARACTER SET utf8";
                /*----- error handling-----*/
                if (!$this->conn->query($sql)) echo 'Datenbank konnte nicht erstellt werden';

                $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
                /*----- create table -----*/
                $sql = "CREATE TABLE IF NOT EXISTS auto (
                    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    name TEXT NOT NULL,
                    kraftstoff TEXT NOT NULL,
                    farbe TEXT NOT NULL,
                    bauart TEXT NOT NULL,
                    tank INTEGER NOT NULL DEFAULT 0,
                    added datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                )";
                if (!($this->conn->query($sql))) echo 'Tabelle konnte nicht erstellt werden';

                /*----- insert default values -----*/
                $sql = "INSERT INTO " . $this->table . " (name, kraftstoff, farbe, bauart) 
                    VALUES ('Passat', 'Diesel', '#000000', 'limousine')";
                if (!$this->conn->query($sql)) echo 'DB Daten konnten nicht hinzugefügt werden';

                $sql = "INSERT INTO " . $this->table . " (name, kraftstoff, farbe, bauart) 
                    VALUES ('Honda', 'Benzin', '#008888', 'bus')";
                if (!$this->conn->query($sql)) echo 'DB Daten konnten nicht hinzugefügt werden';

                $sql = "INSERT INTO " . $this->table . " (name, kraftstoff, farbe, bauart) 
                    VALUES ('Opel', 'Elektro', '#005555', 'suv')";
                if (!$this->conn->query($sql)) echo 'DB Daten konnten nicht hinzugefügt werden';
                
            }
            $this->conn->close();
        }
    }
}
