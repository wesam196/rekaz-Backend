<?php
namespace App\Storage;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class S3StorageBackend
{
    protected Client $client;
    protected string $bucket;
    protected string $basePath;

    
    public function __construct(array $config)
    {
        if (empty($config['bucket'])) {
            throw new \InvalidArgumentException("S3 bucket name is required");
        }
        if (empty($config['endpoint'])) {
            throw new \InvalidArgumentException("S3 endpoint is required");
        }

        $this->bucket = $config['bucket'];
        $this->basePath = rtrim($config['base_path'] ?? '', '/');

        $endpoint = rtrim($config['endpoint'], '/');
        $this->client = new Client([
            'base_uri' => $endpoint . '/',
            'http_errors' => false, // منع Guzzle من رمي الاستثناء تلقائيًا
        ]);
    }

    
    public function save(string $id, string $data, int $size): array
    {
        $payload = [
            'data' => $data,
            'size' => $size,
            'created_at' => now()->toDateTimeString(),
        ];

        $jsonData = json_encode($payload);
        $filePath = $this->getFilePath($id);

        try {
            $response = $this->client->put($filePath, [
                'body' => $jsonData,
            ]);

            if ($response->getStatusCode() >= 400) {
                throw new \Exception("S3 save failed: HTTP " . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            throw new \Exception("S3 save failed: " . $e->getMessage());
        }

        return $payload;
    }

    
    public function retrieve(string $id): ?array
    {
        $filePath = $this->getFilePath($id);

        try {
            $response = $this->client->get($filePath);

            if ($response->getStatusCode() === 404) return null;
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("S3 retrieve failed: HTTP " . $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return null;
        }
    }

    
    public function delete(string $id): void
    {
        $filePath = $this->getFilePath($id);

        try {
            $this->client->delete($filePath);
        } catch (RequestException $e) {
        }
    }

  
    protected function getFilePath(string $id): string
    {
        $path = $this->basePath ? trim($this->basePath, '/') : '';
        if ($path) {
            return "{$this->bucket}/{$path}/{$id}.json";
        }
        return "{$this->bucket}/{$id}.json";
    }
}
