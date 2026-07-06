<?php

declare(strict_types=1);

namespace Crumbls\Layup\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRevision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'content',
        'note',
        'author',
        'created_at',
    ];

    protected $casts = [
        'content' => 'array',
        'created_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('layup.revisions.table', 'layup_page_revisions');
    }

    public function page(): BelongsTo
    {
        $modelClass = config('layup.pages.model', Page::class);

        return $this->belongsTo($modelClass, 'page_id');
    }
}
