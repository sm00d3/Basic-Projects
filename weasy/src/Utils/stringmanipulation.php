<?php
    namespace Weasy\Utils;

    class stringmanipulation {
        public static function countLettersOfString(string $value = null): string
        {
            $responseString = "";
            $CharsAmount = count_chars(strtolower(str_replace(" ","",strval($value))), 1);
            $StringArray = array_unique(str_split(strtolower(str_replace(" ","",strval($value)))));
            foreach ($StringArray as $value) {
                $responseString .= $value.":".str_repeat("*", $CharsAmount[ord($value)]).',';
            }
            return substr($responseString,0,(strlen($responseString) - 1));
        }


        public static  function JsonToObject($data) : string {
            return json_encode($data);
        }
    }