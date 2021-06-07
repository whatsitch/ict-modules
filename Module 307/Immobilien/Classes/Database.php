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
    public $dbname = "m307_marco";
    public $table = "marco_immo";
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
                if (!($this->conn->query($sql))) echo 'Datenbank konnte nicht erstellt werden';

                $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
                /*----- create table -----*/
                $sql = "CREATE TABLE IF NOT EXISTS marco_immo (
                    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    objekt VARCHAR(255) NOT NULL,
                    adresse VARCHAR(255)  NOT NULL,
                    plz INT NOT NULL,
                    kategorie VARCHAR(255)  NOT NULL,
                    bemerkung VARCHAR(255),
                    status TINYINT NOT NULL DEFAULT 0,
                    added datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                )";
                if (!($this->conn->query($sql))) echo 'Tabelle konnte nicht erstellt werden';

                /*----- insert default values -----*/
                $sql = "INSERT INTO " . $this->table . " (objekt, adresse, plz, kategorie, status) 
                    VALUES ('4.5 Zimmer', 'Haupstrasse 45', '8000', 'Wohnung', 0)";
                if (!$this->conn->query($sql)) echo 'DB Daten konnten nicht hinzugefügt werden';

                $sql = "INSERT INTO " . $this->table . " (objekt, adresse, plz, kategorie, status) 
                VALUES ('MFH 5.5', 'Haupstrasse 46', '9000', 'Haus', 0)";
                if (!$this->conn->query($sql)) echo 'DB Daten konnten nicht hinzugefügt werden';

                $sql = "INSERT INTO " . $this->table . " (objekt, adresse, plz, kategorie, status) 
                VALUES ('Garage mit Zusatzraum', 'Haupstrasse 75', '3000', 'Objekt', 1)";
                if (!$this->conn->query($sql)) echo 'DB Daten konnten nicht hinzugefügt werden';
            }
            $this->conn->close();
        }
    }
}
