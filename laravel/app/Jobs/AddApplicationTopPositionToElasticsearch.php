<?php

namespace App\Jobs;

use App\Models\ApplicationTopCategoryPosition;
use App\Services\ElasticsearchService\ElasticsearchServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * @deprecated
 * Планировалось использовать, но данные дольше обрабатываются и идет спам джобами
 */
class AddApplicationTopPositionToElasticsearch implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $applicationTopPositionId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ElasticsearchServiceInterface $elasticsearchService): void
    {
        $object = ApplicationTopCategoryPosition::query()
            ->find($this->applicationTopPositionId);

        $elasticsearchService->addDocument(env('ELASTIC_INDEX'), $object->serializeForElastic(), $this->applicationTopPositionId);
    }
}
