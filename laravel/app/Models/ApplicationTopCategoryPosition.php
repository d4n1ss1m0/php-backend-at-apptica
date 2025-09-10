<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationTopCategoryPosition extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function application():BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }
}
