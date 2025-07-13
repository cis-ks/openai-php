<?php

namespace CisBv\OpenAi\Components;

class OpenAiOutputContent
{
    public string $type;
    public array $values;

    public function __construct(array $values)
    {
        if (array_key_exists('type', $values)) {
            $this->type = $values['type'];
            unset($values['type']);
            $this->values = $values;
        }
    }

    public function getProperties(): array
    {
        return array_keys($this->values);
    }

    public function getProperty(string $name): mixed
    {
        return $this->values[$name] ?? null;
    }
}