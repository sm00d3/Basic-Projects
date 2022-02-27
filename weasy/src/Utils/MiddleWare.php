<?php

    namespace Weasy\Utils;
    use Weasy\Controllers\wordController;

    class MiddleWare
    {
        private string $_Uri = "";
        private string $_RequestType = "";
        private string $_Domain= "";
        private string $_RequestScheme = "";
        private \PDO $_dbConnection;


        public function __construct($dbConnection) {
            $this->_dbConnection = $dbConnection;
            $this->_Uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $this->_RequestType = $_SERVER["REQUEST_METHOD"];
            $this->_Domain = $_SERVER['HTTP_HOST'];
            $this->_RequestScheme = $_SERVER['REQUEST_SCHEME'];
            $this->ProcessURl();

        }

        private function ProcessURl() {
            $var = explode("/",$this->_Uri);
            // Redirect between API and UserInterface
            if(isset($var[1]) && $var[1] != "" && strtoupper($var[1]) == "API") {
                $this->InjectHeaders();
                $id = null;
                if(isset($var[2]) && $var[2] != "" && strtoupper($var[2]) == "WORDS") {
                    if($this->_RequestType == "DELETE" && isset($var[3]) && $var[3] != ""){
                        $id =(int)$var[3];
                    }

                    if(($this->_RequestType == "POST" || $this->_RequestType == "GET" || $this->_RequestType == "PUT") && isset($var[3])){
                        header("HTTP/1.1 404 Not Found");
                        exit();
                    }

                    $controller = new wordController($this->_dbConnection,$this->_RequestType, $id);
                    printf(json_encode($controller->ProcessRequest()));
                } else {
                    header("HTTP/1.1 404 Not Found");
                    exit();
                }

            } else {
                $url = $this->_RequestScheme."://".$this->_Domain."/"."Client";
                header("location:".$url);
            }

        }

        private function InjectHeaders() {

            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        }
    }