<?php

namespace App\Services\ElasticsearchService;

interface ElasticsearchServiceInterface
{
    public function createIndex(string $index, array $mapping);
    public function addDocument(string $index, array $data, $id = null);
    public function bulkInsert(string $index, array $documents);
    public function deleteDocument(string $index, $id);
    public function search(string $index, array $query);
}
