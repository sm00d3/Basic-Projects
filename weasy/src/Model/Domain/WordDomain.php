<?php

    namespace Weasy\Model\Domain;

    use Weasy\Model\Database\Repository\WordsRepository;
    use Weasy\utils\Dtos\ReturnInfoDto;
    use Weasy\Utils\Dtos\WordsDto;
    use Weasy\Utils\stringmanipulation;

    class WordDomain
    {
        private \PDO $_db;
        private WordsRepository $_Wordsrepository;

        public function __construct($db) {
            $this->_db = $db;
            $this->_Wordsrepository = new WordsRepository($db);
        }

        public function FindAll(): array {
            return $this->_Wordsrepository->List();
        }

        public function Add(WordsDto $word) : ReturnInfoDto {
            $result = new ReturnInfoDto();
            $wordExist = $this->_Wordsrepository->CheckIfWordExist($word->getWord());
            if($wordExist == true) {
                $result->Message = "Word already inserted.";
                $result->HasError = true;
                return $result;
            }
            if($this->ValidateInputs($word)) {
                $word->setWordManipulation(stringmanipulation::countLettersOfString($word->getWord()));
                $Inserted = $this->_Wordsrepository->Add($word);
                $result->Message = (($Inserted == true) ? "Successfully added." : "Could not add the word.");
                $result->HasError = !$Inserted;
            } else {
                $result->Message = "Word cannot be empty.";
                $result->HasError = true;
            }
            return  $result;
        }

        public function Delete(int $id) : ReturnInfoDto {
            $result = new ReturnInfoDto();
            $exists = $this->_Wordsrepository->GetByid($id);
            if(count($exists) > 0) {
                $Deleted = $this->_Wordsrepository->Delete($id);
                $result->Message = (($Deleted == true) ? "Successfully removed." : "Could not remove the word.");
                $result->HasError = !$Deleted;
            } else {
                $result->Message = "It is not possible to remove a word that does not exist.";
                $result->HasError = true;
            }
            return  $result;
        }

        private function ValidateInputs(WordsDto $wordsDto): bool {

            if (($wordsDto->getId() <= 0 !== null) && ($wordsDto->getWord() !== null && $wordsDto->getWord() != "")){
                return true;
            }

            return false;
        }
    }