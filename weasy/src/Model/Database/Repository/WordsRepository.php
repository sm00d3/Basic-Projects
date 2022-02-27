<?php

    namespace Weasy\Model\Database\Repository;

    use Weasy\Utils\Dtos\WordsDto;
    use Weasy\Utils\DbExecutionTypes;

    class WordsRepository
    {

        private \PDO $_Connection;

        public function __construct(\PDO $Connection) {
            $this->_Connection = $Connection;
        }
        /*
         * @Summary: Add new Word to Database
         * @Return: True if added and false if not
        */
        public function Add(WordsDto $words): bool {
            $statement = "INSERT INTO Strings (Word,WordManipulation) 
                          VALUES (:Word, :WordManipulation)";

            $result = $this->ExecQuery($statement,array(
                'Word' => $words->getWord(),
                'WordManipulation' => $words->getWordManipulation()
            ), DbExecutionTypes::Insert);
            return  (count($result) > 0);
        }

        /*
         * @Summary: List all Word in Database
         * @Return: List of word
        */
        public function List(): array {
            $returnObj = array();
            $statement = "
                Select 
                       Id,
                       Word,
                       WordManipulation
                From Strings
            ";
            return $this->ExecQuery($statement,null, DbExecutionTypes::SELECT_ALL);;
        }

        /*
         * @Summary: Check if an Word is already in database
         * @Return: List of word
        */
        public function CheckIfWordExist(string $Word) : bool
        {
            $returnObj = array();
            $statement = "SELECT 
                Id,
               Word,
               WordManipulation
            FROM
                Strings
            WHERE Word = :Word;";

            $returnObj = $this->ExecQuery($statement, array("Word" => $Word), DbExecutionTypes::SELECT_BY_PROP);
            return (count($returnObj) > 0);
        }

        /*
         * @Summary: Get Word by Id
         * @Return: Word
        */
        public function GetByid(int $id): array {
            $statement = "SELECT 
                Id,
               Word,
               WordManipulation
            FROM
                Strings
            WHERE Id = ?;";
            return $this->ExecQuery($statement, array($id), DbExecutionTypes::SELECT_BY_PROP);
        }

        /*
         * @Summary: Get Word by Id
         * @Return: Word
        */
        public function Delete(int $id): bool {
            $returnObj = array();
            $statement = "DELETE FROM Strings WHERE id = ?;";
            $returnObj = $this->ExecQuery($statement, array($id), DbExecutionTypes::Delete);
            return (count($returnObj) > 0);
        }

        /*
         * @Summary: Generic Method to simplify cals to bd
         * @Return: Array
        */
        private function ExecQuery(string $statement, ?array $data , DbExecutionTypes $ExecutionType): array {
            try {
                $result = array();
                if($ExecutionType == DbExecutionTypes::SELECT_ALL) {
                    $query = $this->_Connection->query($statement);
                }
                else {
                    $query = $this->_Connection->prepare($statement);
                    $query->execute($data);
                }
                if($ExecutionType == DbExecutionTypes::Insert
                    || $ExecutionType == DbExecutionTypes::Delete) {

                    $result = array($query->rowCount());

                } else if($ExecutionType == DbExecutionTypes::SELECT_ALL
                         || $ExecutionType == DbExecutionTypes::SELECT_BY_PROP) {

                    $result = $query->fetchAll(\PDO::FETCH_OBJ);
                }
                return $result;
            }catch (\PDOException $ex) {
                throw $ex;
            }
        }

    }