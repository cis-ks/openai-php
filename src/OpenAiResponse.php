<?php

namespace CisBv\OpenAi;

use CisBv\OpenAi\Components\OpenAiError;
use CisBv\OpenAi\Components\OpenAiOutput;
use CisBv\OpenAi\Components\OpenAiResponseUsage;
use CisBv\OpenAi\Enum\OpenAiResponseStatus;
use Psr\Http\Message\ResponseInterface;

class OpenAiResponse
{
    protected bool $validResponse = false;
    private(set) string $id = '';
    private(set) string|null $previousResponseId = null;
    private(set) OpenAiResponseStatus|null $status = null;
    private(set) OpenAiError|null $error = null;
    private(set) OpenAiResponseUsage|null $usage = null;
    private(set) string|null $user = null;
    private(set) array $outputs = [];

    private(set) string $responseBody = '';
    private(set) int $responseStatusCode = 0;

    public function __construct(ResponseInterface $response)
    {
        $this->responseBody = $response->getBody()->getContents();
        $this->responseStatusCode = $response->getStatusCode();

        if ($response->getStatusCode() === 200) {
            $body = json_decode($this->responseBody, true);

            $this->id = $body['id'];
            $this->previousResponseId = array_key_exists('previous_response_id', $body)
                ? $body['previous_response_id']
                : null;
            $this->extractOutputs($body);
            $this->status = OpenAiResponseStatus::tryFrom($body['status']);
            $this->user = array_key_exists('user', $body) ? $body['user'] : null;
            $this->usage = array_key_exists('usage', $body)
                ? new OpenAiResponseUsage($body['usage'])
                : null;

            $this->validResponse = true;
        }
    }

    public function isValid(): bool
    {
        return $this->validResponse;
    }

    public function first(): OpenAiOutput|null
    {
        if (empty($this->outputs)) {
            return null;
        }

        return array_values($this->outputs)[0];
    }

    /**
     * @param mixed $body
     * @return void
     */
    protected function extractOutputs(mixed $body): void
    {
        foreach ($body['output'] as $o) {
            $this->outputs[] = new OpenAiOutput($o);
        }
    }
}