<?php

namespace App\Services\ElasticsearchService;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

interface ElasticsearchServiceInterface
{
    /**
     * Создать индекс
     *
     * @param string $index - индекс
     * @param array $mapping - маппинг
     * @return mixed
     */
    public function createIndex(string $index, array $mapping): array;

    /**
     * Добавить документ в индекс
     *
     * @param string $index - индекс
     * @param array $data - информация документа
     * @param $id - айди документа
     * @return array
     */
    public function addDocument(string $index, array $data, $id = null): array;

    /**
     * Массовое добавление в индекс
     *
     * @param string $index - индекс
     * @param array $documents - массив документов
     * @return array
     */
    public function bulkInsert(string $index, array $documents): array;

    /**
     * Удалить документ из индекса
     *
     * @param string $index - индекс
     * @param $id - айди документа
     * @return array
     */
    public function deleteDocument(string $index, $id): array;

    /**
     * Поиск по индексу
     *
     * @param string $index - индекс
     * @param array $query - параметры поиска
     * @return array
     */
    public function search(string $index, array $query): array;
}
