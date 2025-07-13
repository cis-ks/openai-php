<?php

namespace CisBv\OpenAi\Components;

use CisBv\OpenAi\Enum\OpenAiRole;

class OpenAiOutput
{
    public string $id;
    public string $type;
    public OpenAiRole $role;
    public array $content = [];

    public function __construct(array $output)
    {
        $this->id = $output['id'];
        $this->type = $output['type'];
        $this->role = OpenAiRole::tryFrom($output['role']);

        foreach ($output['content'] as $content) {
            $this->content[] = new OpenAiOutputContent($content);
        }
    }
}