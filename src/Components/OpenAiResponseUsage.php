<?php

namespace CisBv\OpenAi\Components;

class OpenAiResponseUsage
{
    private(set) int $inputTokens = 0;
    private(set) int $cachedInputTokens = 0;
    private(set) int $outputTokens = 0;
    private(set) int $totalTokens = 0;

    public function __construct(array $usage)
    {
        $this->inputTokens = $usage['input_tokens'];
        $this->cachedInputTokens = $usage['input_tokens_details']['cached_tokens'] ?? 0;
        $this->outputTokens = $usage['output_tokens'];
        $this->totalTokens = $usage['total_tokens'];
    }
}