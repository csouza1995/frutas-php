<?php

namespace app;

use mysqli;

class Database
{
    protected string $servername = "localhost";
    protected string $username = "root";
    protected string $password = "";
    protected string $dbname = "loja";

    protected $connection = null;

    public function __construct()
    {
        // Create connection
        $connection = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $this->connection = $connection;
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    public function run(string $sql)
    {
        if ($this->connection->query($sql) !== true) {
            return $this->connection->error;
        }

        return true;
    }

    public function fetch(string $sql)
    {
        $data = [];
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
}
