<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $table = 'cms_pages';

    protected $fillable = [
        'page_key',
        'title',
        'content',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Scope to get only published pages
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Get specific section content from JSON
     */
    public function section($key)
    {
        return $this->content[$key] ?? null;
    }
}
