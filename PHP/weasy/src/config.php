<?php
    require 'vendor/autoload.php';

    use Dotenv\Dotenv;
    use Weasy\Model\Database\DbConnection;

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();

    $con = new DbConnection();
    $dbConnection = $con->getDbConnection();
    $con->CreateTable();