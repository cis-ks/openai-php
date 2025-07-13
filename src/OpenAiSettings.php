<?php

namespace CisBv\OpenAi;

use CisBv\OpenAi\Components\OpenAiApi;
use CisBv\OpenAi\Components\OpenAiApiFieldName;
use ReflectionClass;

class OpenAiSettings
{
    #[OpenAiApi("responses")]
    #[OpenAiApiFieldName("max_output_tokens")]
    public int|null $maxOutputTokens = 500;

    #[OpenAiApi("responses")]
    public string $user = '';

    public function toArray(string $api): array
    {
        $data = [];

        $properties = new ReflectionClass($this)->getProperties();
        foreach ($properties as $property) {
            $attributes = $property->getAttributes(OpenAiApi::class);
            $apiAttributes = array_filter($attributes, fn($attribute) => $attribute->newInstance()->api == $api);
            if (empty($attributes) || !empty($apiAttributes)) {
                $fieldNames = $property->getAttributes(OpenAiApiFieldName::class);
                if (empty($fieldNames)) {
                    $data[$property->getName()] = $property->getValue();
                } else {
                    $fieldName = array_find($fieldNames, fn($attribute) => $attribute->newInstance()->api == $api);
                    $fieldName = $fieldName?->newInstance()->fieldName ?? array_find(
                        $fieldNames,
                        fn($attribute) => $attribute->newInstance()->api === null
                    )->newInstance()->fieldName;
                    $data[$fieldName] = $property->getValue();
                }
            }
        }

        return $data;
    }
}