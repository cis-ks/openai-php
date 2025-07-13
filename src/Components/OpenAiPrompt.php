<?php

namespace CisBv\OpenAi\Components;

class OpenAiPrompt
{
    public function __construct(
        public string $id,
        public string $version,
        protected array $variables = [],
    ) {
        if (array_is_list($this->variables)) {
            $this->variables = [];
        }
    }

    public function setVariables(array $variables): void
    {
        if (!array_is_list($variables)) {
            $this->variables = $variables;
        }
    }

    public function addVariables(array $variables): void
    {
        if (!array_is_list($variables)) {
            $this->variables = array_replace($this->variables, $variables);
        }
    }

    public function addVariable(string $name, string $value): void
    {
        $this->variables[$name] = $value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'variables' => $this->variables,
        ];
    }

}