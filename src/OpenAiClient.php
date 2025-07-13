<?php

namespace CisBv\OpenAi;

use CisBv\OpenAi\Components\OpenAiInput;
use CisBv\OpenAi\Components\OpenAiPrompt;
use CisBv\OpenAi\Enum\OpenAiModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\RedirectMiddleware;
use Psr\Http\Client\ClientExceptionInterface;

class OpenAiClient
{
    protected Client $client;
    protected string|null $lastError = null;

    protected string $api = 'responses';
    protected array $requestData = [];

    public function __construct(
        protected string $token,
        protected OpenAiModel|string $model = OpenAiModel::GPT_4_1_MINI,
        protected bool $verifySsl = true,
        protected OpenAiSettings|null $settings = null,
    ) {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'timeout' => 5,
            'allow_redirects' => array_replace(
                RedirectMiddleware::$defaultSettings,
                [
                    'protocols' => ['https'],
                    'referer' => true,
                    'strict' => true,
                    'track_redirects' => true
                ]
            ),
            'verify' => $this->verifySsl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json; indent=4',
                'Authorization' => "Bearer $this->token"
            ]
        ]);
    }

    public function setModel(OpenAiModel|string $model): self
    {
        $this->model = is_string($model) ? OpenAiModel::tryFrom($model) : $model;
        return $this;
    }

    public function responses(): self
    {
        $this->api = 'responses';
        $this->resetRequestData();
        return $this;
    }

    public function resetRequestData(): self
    {
        $this->requestData = [];
        return $this;
    }

    public function input(string|OpenAiInput $inputData): self
    {
        if (!array_key_exists('input', $this->requestData)) {
            $this->requestData['input'] = [];
        }

        if ($inputData instanceof OpenAiInput) {
            $this->requestData['input'][] = $inputData;
        } else {
            $this->requestData['input'] = $inputData;
        }

        return $this;
    }

    public function instructions(string $instructions): self
    {
        if (trim($instructions) !== '') {
            $this->requestData['instructions'] = $instructions;
        }

        return $this;
    }

    public function prompt(OpenAiPrompt $prompt): self
    {
        $this->requestData['prompt'] = $prompt;
        return $this;
    }

    public function send(): OpenAiResponse|false
    {
        $this->lastError = null;
        try {
            $request = new Request('POST', $this->api)->withBody(Utils::streamFor($this->getRequestData()));
            $response = $this->client->sendRequest($request);

            return new OpenAiResponse($response);
        } catch (GuzzleException|ClientExceptionInterface $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function response(string $responseId): OpenAiResponse|false
    {
        return $this->get("responses/$responseId");
    }
    
    private function get(string $url): OpenAiResponse|false
    {
        $this->lastError = null;
        try {
            $request = new Request('GET', $url);
            return new OpenAiResponse($this->client->sendRequest($request));
        } catch (GuzzleException|ClientExceptionInterface $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function hasError(): bool
    {
        return !is_null($this->lastError);
    }

    public function getError(): string
    {
        return $this->lastError ?? '';
    }

    protected function resetError(): void
    {
        $this->lastError = null;
    }

    protected function getRequestData(): string
    {
        return json_encode(
            array_replace(
                $this->getDefaultData(),
                array_map(
                    fn(mixed $data) => match (true) {
                        $data instanceof OpenAiInput => $data->toArray(),
                        default => $data,
                    },
                    $this->requestData
                )
            )
        );
    }

    protected function getDefaultData(): array
    {
        $defaultData = match ($this->api) {
            'responses' => [
                'model' => $this->model
            ]
        };

        return array_merge($defaultData, $this->settings?->toArray($this->api) ?? []);
    }
}