<?php

namespace App\Models;

use App\Observers\TagObserver;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[ObservedBy(TagObserver::class)]
class Tag extends Model
{
    use HasAudit;

    protected $fillable = [
        'name',
        'slug',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
