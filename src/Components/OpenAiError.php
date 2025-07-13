<?php

namespace CisBv\OpenAi\Components;

class OpenAiError
{
    public function __construct(
        public string $code,
        public string $message,
    ) {
    }
}