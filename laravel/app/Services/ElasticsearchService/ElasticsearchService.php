<?php

namespace App\Services\ElasticsearchService;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ElasticsearchService implements ElasticsearchServiceInterface
{
    /**
     * Отправка запроса в эластик
     *
     * @param string $method - метод
     * @param string $uri - запрос
     * @param array|string $data - данные
     * @param bool $isNdjson - тип отправки данных (для массовой вставки)
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    protected function request(string $method, string $uri, array|string $data = [], bool $isNdjson = false): PromiseInterface|Response
    {
        $client = Http::withBasicAuth('elastic', env('ELASTICSEARCH_PASSWORD'));
        $url = env('ELASTICSEARCH_HOSTS') . $uri;

        // DELETE без тела
        $options = [];

        if ($isNdjson) {
            $options['body'] = is_array($data) ? implode("\n", $data) . "\n" : $data;
            $options['headers']['Content-Type'] = 'application/x-ndjson';
        } elseif (!empty($data)) {
            $options['json'] = $data;
        }

        return $client->send($method, $url, $options);
    }

    /**
     * @inheritDoc
     * @throws ConnectionException
     */
    public function createIndex(string $index, array $mapping): array
    {
        // Проверяем, существует ли индекс
        $existing = $this->request('GET', "/$index");

        if ($existing->getStatusCode() === 200) {
            // Если существует — удаляем
            $this->request('DELETE', "/$index");
        }

        // Создаем индекс с новым маппингом
        return $this->request('PUT', "/$index", $mapping)->json();
    }

    /**
     * @inheritDoc
     * @throws ConnectionException
     */
    public function addDocument(string $index, array $data, $id = null): array
    {
        $uri = $id ? "/$index/_doc/$id" : "/$index/_doc";
        return $this->request('PUT', $uri, $data)->json();
    }

    /**
     * @inheritDoc
     * @throws ConnectionException
     */
    public function bulkInsert(string $index, array $documents): array
    {
        $bulkData = [];
        foreach ($documents as $doc) {
            $bulkData[] = ['index' => ['_index' => $index, '_id' => $doc['id'] ?? null]];
            $bulkData[] = $doc;
        }

        $payload = implode("\n", array_map(fn($d) => json_encode($d), $bulkData)) . "\n";

        // передаем NDJSON через request
        return $this->request('POST', '/_bulk', $payload, true)->json();
    }

    /**
     * @inheritDoc
     * @throws ConnectionException
     */
    public function deleteDocument(string $index, $id): array
    {
        return $this->request('DELETE', "/$index/_doc/$id")->json();
    }

    /**
     * @inheritDoc
     * @throws ConnectionException
     */
    public function search(string $index, array $query): array
    {
        return $this->request('GET', "/$index/_search", $query)->json();
    }
}
