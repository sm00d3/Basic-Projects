<?php

    namespace Weasy\Model\Database;

    class DbConnection
    {
        private \PDO $Connection;
        private string $_HOST = "";
        private int $_PORT = 3386;
        private string $_DB = "";
        private string $_USERNAME = "";
        private string $_PASSWORD = "";

        public function  __construct() {
            $this->_HOST .= $_ENV["DB_HOST"];
            $this->_PORT .= $_ENV["DB_PORT"];
            $this->_DB .= $_ENV["DB_DATABASE"];
            $this->_USERNAME .= $_ENV["DB_USERNAME"];
            $this->_PASSWORD .= $_ENV["DB_PASSWORD"];
            try {
                $this->Connection = new \PDO("mysql:host=".$this->_HOST.";dbname=".$this->_DB,$this->_USERNAME,$this->_PASSWORD);
                $this->Connection->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
            }catch (\PDOException $ex) {
                throw $ex;
            }
        }

        public function getDbConnection(): \PDO
        {
            return  $this->Connection;
        }

        public function CreateTable()
        {
            $statement = "create table IF NOT EXISTS Strings (
                                Id int AUTO_INCREMENT  NOT NULL,
                                Word varchar(100) not null,
                                WordManipulation varchar(100) not null,
                                PRIMARY KEY (Id)
                          )
                          ";
            if(isset($this->Connection)) {
                try {
                    $this->Connection->exec($statement);
                } catch (\PDOException $ex) {
                    exit($ex->getMessage());
                }
            }
        }
    }