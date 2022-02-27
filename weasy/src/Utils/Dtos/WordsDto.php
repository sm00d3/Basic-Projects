<?php

namespace Weasy\Utils\Dtos;

class WordsDto
{
    private int $_Id;
    private string $_Word;
    private string $_WordManipulation;


    public function __construct(int $Id, string $Word, string $WordManipulation) {
        $this->_Id = $Id;
        $this->_Word = $Word;
        $this->_WordManipulation = $WordManipulation;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_Id;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->_Word;
    }

    /**
     * @return string
     */
    public function getWordManipulation(): string
    {
        return $this->_WordManipulation;
    }

    /**
     * @param string $WordManipulation
     */
    public function setWordManipulation(string $WordManipulation): void
    {
        $this->_WordManipulation = $WordManipulation;
    }
}