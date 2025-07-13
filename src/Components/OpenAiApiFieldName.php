<?php

namespace CisBv\OpenAi\Components;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OpenAiApiFieldName
{
    public function __construct(
        public string|null $fieldName,
        public string|null $api = null,
    ) {
    }
}