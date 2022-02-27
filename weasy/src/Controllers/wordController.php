<?php

    namespace Weasy\Controllers;

    use Weasy\Model\Domain;
    use Weasy\utils\Dtos\ResponseDto;
    use Weasy\Utils\Dtos\WordsDto;

    class wordController
    {
        private Domain\WordDomain  $_WordDomain;
        private string $_RequestType = "";
        private ?int $_WordId;

        public function  __construct($db, string $requestType, ?int $Id) {
            $this->_WordDomain = new Domain\WordDomain($db);
            $this->_RequestType = $requestType;
            $this->_WordId = $Id;
        }

        public function  ProcessRequest(): ResponseDto
        {
            return match ($this->_RequestType) {
                'POST' => $this->AddWord(),
                'DELETE' => $this->DeleteWord($this->_WordId),
                'GET' => $this->getAll(),
                default => array($this->NotFoundResponse())
            };
        }

        private  function  getAll(): ResponseDto {
            $rep = new ResponseDto();
            try {

                $result = $this->_WordDomain->FindAll();
                if($result) {
                    $rep->responseObject = $result;
                    $rep->responseCode = 200;
                    $rep->ResponseErrorMessage = "";
                } else {
                    $rep = $this->NotFoundResponse();
                }

            } catch (\Exception $ex) {
                $rep->responseCode = 400;
                $rep->ResponseErrorMessage = $ex->getMessage();
            }
            return  $rep;
        }

        private function AddWord(): ResponseDto {
            $rep = new ResponseDto();
            try {
                $word = (string) file_get_contents('php://input',false);
                $wordsObj = new WordsDto(0,$word,"");

                $result = $this->_WordDomain->Add(word: $wordsObj);

                $rep->responseObject = $result;
                if ($result->HasError == true) {
                    $rep->responseCode = 202;
                    $rep->ResponseErrorMessage = $result->Message;
                } else {
                    $rep->responseCode = 201;
                    $rep->ResponseErrorMessage = "";
                }

            } catch (\Exception $ex) {
                $rep->responseCode = 500;
                $rep->ResponseErrorMessage = $ex->getMessage();
            }
            return  $rep;
        }

        private function DeleteWord(?int $id): ResponseDto {
            if(isset($id) && $id > 0){
                $rep = new ResponseDto();
                try {
                    $result = $this->_WordDomain->Delete($id);
                    $rep->responseObject = $result;
                    if ($result->HasError == true) {
                        $rep->responseCode = 202;
                        $rep->ResponseErrorMessage = $result->Message;
                    } else {
                        $rep->responseCode = 200;
                        $rep->ResponseErrorMessage = "";
                    }

                } catch (\Exception $ex) {
                    $rep->responseCode = 500;
                    $rep->ResponseErrorMessage = $ex->getMessage();
                }
            } else {
                $rep = $this->BadRequestResponse();
            }
            return  $rep;
        }

        private function NotFoundResponse(): ResponseDto
        {
            $rep = new ResponseDto();
            $rep->responseCode = 404;
            $rep->ResponseErrorMessage = "HTTP/1.1 404 Not Found";
            $rep->responseObject = array("");;
            return $rep;
        }

        private function BadRequestResponse(): ResponseDto
        {
            $rep = new ResponseDto();
            $rep->responseCode = 400;
            $rep->ResponseErrorMessage = "HTTP/1.1 400 Bad Request";
            $rep->responseObject = array("");
            return $rep;
        }
    }