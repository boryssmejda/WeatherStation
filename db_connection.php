<?php

class DB_connection
{
    private $db_conn;
    private $servername;
    private $dbname;
    private $username;
    private $password;

    private function establish_db_connection()
    {
        $this->db_conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->db_conn->connect_error)
        {
            die("Connection failed: " . $this->db_conn->connect_error);
        }
    }

    function __construct()
    {
        $this->servername = "localhost";
        $this->dbname = "dbc17_stud_005";
        $this->username = "dbc17_stud_005";
        $this->password = "8AS_d2Qsr";

        $this->establish_db_connection();
    }

    function __destruct()
    {
        $this->db_conn->close();
    }

    public function get()
    {
        return $this->db_conn;
    }
}

?>