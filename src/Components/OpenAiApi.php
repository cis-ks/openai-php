<?php

namespace CisBv\OpenAi\Components;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OpenAiApi
{
    public function __construct(
        public string|null $api,
    ) {
    }
}