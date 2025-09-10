<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function positions():HasMany
    {
        return $this->hasMany(ApplicationTopCategoryPosition::class, 'application_id', 'id');
    }
}
