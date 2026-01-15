<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;

// ===== Show Category Model =====
class Category extends Model
{
    use HasFactory;

    protected $table = 'show_categories';

    protected $fillable = ['name', 'slug', 'description', 'icon', 'color', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function shows()
    {
        return $this->hasMany(Show::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
