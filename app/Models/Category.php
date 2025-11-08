<?php

namespace App\Models;

use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;

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
