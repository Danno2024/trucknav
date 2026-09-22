<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active'];

    protected static function booted(): void
    {
        static::deleted(function (ForumCategory $category) {
            $category->threads()->delete();
        });
    }

    public function threads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
