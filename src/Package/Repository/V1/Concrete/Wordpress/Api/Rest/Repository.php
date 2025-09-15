<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Wordpress\Api\Rest;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

use WP_REST_Response;
use WP_Error;

class Repository extends BaseRepository
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['namespace'])) 
        {
            throw new \InvalidArgumentException("REST API namespace must be configured");
        }

        if (!isset($this->config['version'])) 
        {
            $this->config['version'] = 'v1';
        }
    }

    public function find($id, array $options = []): ?array
    {
        $response = $this->request($id, 'GET', [], $options);
        return $this->parseResponse($response);
    }

    public function findBy(array $criteria, array $options = []): array
    {
        $response = $this->request('', 'GET', $criteria, $options);
        return $this->parseResponse($response) ?? [];
    }

    public function findAll(array $options = []): array
    {
        return $this->findBy([], $options);
    }

    public function create(array $data): int
    {
        $response = $this->request('', 'POST', $data);
        $body = $this->parseResponse($response);
        return $body['id'] ?? 0;
    }
    
    public function update($id, array $data): bool
    {
        $response = $this->request($id, 'PUT', $data);
        return $this->isSuccessful($response);
    }

    public function delete($id): bool
    {
        $response = $this->request($id, 'DELETE');
        return $this->isSuccessful($response);
    }

    protected function request(
        string $endpoint,
        string $method = 'GET',
        array $data = [],
        array $options = []
    ): array {
        $url = rest_url(
            $this->config['namespace'] . '/' . 
            $this->config['version'] . '/' . 
            ltrim($endpoint, '/')
        );

        $args = [
            'method' => $method,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-WP-Nonce' => wp_create_nonce('wp_rest')
            ],
            'timeout' => $options['timeout'] ?? 30
        ];

        if (!empty($data)) {
            $args['body'] = json_encode($data);
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            throw new \RuntimeException(
                "API request failed: " . $response->get_error_message()
            );
        }

        return [
            'status' => wp_remote_retrieve_response_code($response),
            'body' => json_decode(wp_remote_retrieve_body($response), true)
        ];
    }

    protected function parseResponse(array $response): ?array
    {
        if ($response['status'] >= 400) {
            throw new \RuntimeException(
                "API request failed with status: {$response['status']}"
            );
        }

        return $response['body'];
    }

    protected function isSuccessful(array $response): bool
    {
        return $response['status'] >= 200 && $response['status'] < 300;
    }
}