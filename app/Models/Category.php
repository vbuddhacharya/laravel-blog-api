<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(CategoryObserver::class)]
class Category extends Model
{
    use HasAudit;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
