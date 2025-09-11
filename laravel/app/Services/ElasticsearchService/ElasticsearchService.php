<?php

namespace App\Services\ElasticsearchService;

use Illuminate\Support\Facades\Http;

class ElasticsearchService implements ElasticsearchServiceInterface
{
    protected function request(string $method, string $uri, array|string $data = [], bool $isNdjson = false)
    {
        $client = Http::withBasicAuth('elastic', env('ELASTICSEARCH_PASSWORD'));
        $url = env('ELASTICSEARCH_HOSTS') . $uri;

        // DELETE без тела
        if ($method === 'DELETE' && empty($data) && !$isNdjson) {
            $response = $client->delete($url);
        } elseif ($isNdjson) {
            // NDJSON как raw body
            $response = $client->send($method, $url, [
                'body' => $data,
                'headers' => ['Content-Type' => 'application/x-ndjson'],
            ]);
        } else {
            $response = $client->send($method, $url, ['json' => $data]);
        }

        return $response->json();
    }

    public function createIndex(string $index, array $mapping)
    {
        return $this->request('PUT', "/$index", $mapping);
    }

    public function addDocument(string $index, array $data, $id = null)
    {
        $uri = $id ? "/$index/_doc/$id" : "/$index/_doc";
        return $this->request('PUT', $uri, $data);
    }

    public function bulkInsert(string $index, array $documents)
    {
        $bulkData = [];
        foreach ($documents as $doc) {
            $bulkData[] = ['index' => ['_index' => $index, '_id' => $doc['id'] ?? null]];
            $bulkData[] = $doc;
        }

        $payload = implode("\n", array_map(fn($d) => json_encode($d), $bulkData)) . "\n";

        // передаем NDJSON через request
        return $this->request('POST', '/_bulk', $payload, true);
    }

    public function deleteDocument(string $index, $id)
    {
        return $this->request('DELETE', "/$index/_doc/$id");
    }

    public function search(string $index, array $query)
    {
        return $this->request('GET', "/$index/_search", $query);
    }
}
