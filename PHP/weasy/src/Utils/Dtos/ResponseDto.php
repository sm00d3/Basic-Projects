<?php
    namespace Weasy\utils\Dtos;

    use http\Message;

    class ResponseDto {
        public int $responseCode;
        public array|ReturnInfoDto|null $responseObject;
        public string $ResponseErrorMessage;
    }

