<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class ApplicationTopCategoryPosition extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function application():BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

}
