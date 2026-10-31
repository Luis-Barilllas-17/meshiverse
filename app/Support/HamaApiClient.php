<?php

namespace App\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HamaApiClient
{
    public function handshake(string $baseUrl, string $accessToken): Response
    {
        return $this->request($accessToken)->asJson()->post(
            $this->endpoint($baseUrl, '/api/v1/connections/handshake'),
            ['project_name' => config('app.name', 'MeshiVerse')],
        );
    }

    public function hamas(string $baseUrl, string $accessToken): Response
    {
        return $this->request($accessToken)->get(
            $this->endpoint($baseUrl, '/api/v1/hamas'),
        );
    }

    /** @param array<string, mixed> $payload */
    public function createHama(string $baseUrl, string $accessToken, array $payload): Response
    {
        return $this->request($accessToken)
            ->asJson()
            ->post($this->endpoint($baseUrl, '/api/v1/hamas'), $payload);
    }

    private function request(string $accessToken): PendingRequest
    {
        return Http::acceptJson()
            ->withToken($accessToken)
            ->connectTimeout(3)
            ->timeout(8);
    }

    private function endpoint(string $baseUrl, string $path): string
    {
        return rtrim($baseUrl, '/').$path;
    }
}
