<?php

declare(strict_types=1);

namespace Crumbls\Layup\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

/**
 * Adds parent/child nesting with a denormalized URL path.
 *
 * The `path` column is the canonical URL component
 * (e.g. "about/team/jane"); `slug` is just the segment ("jane").
 * `path` carries a unique index, which enforces both global URL
 * uniqueness and slug-uniqueness-scoped-to-parent without a
 * composite index that would have to wrestle with NULL parents.
 */
trait HasNestedPath
{
    protected static function bootHasNestedPath(): void
    {
        static::saving(function (self $model): void {
            $model->guardAgainstAncestorCycles();
            $model->path = $model->buildPath();
        });

        static::saved(function (self $model): void {
            // Cascade only when the path actually changed — this single trigger
            // covers slug renames, parent moves, and downstream cascades alike.
            if (! $model->wasChanged('path')) {
                return;
            }

            foreach ($model->children()->get() as $child) {
                $child->save();
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function buildPath(): string
    {
        if (! $this->parent_id) {
            return (string) $this->slug;
        }

        // Bypass the relation cache: parent_id may have just changed in this save.
        $parent = static::query()->find($this->parent_id);

        if (! $parent) {
            return (string) $this->slug;
        }

        return $parent->path . '/' . $this->slug;
    }

    protected function guardAgainstAncestorCycles(): void
    {
        if (! $this->parent_id) {
            return;
        }

        $maxDepth = (int) config('layup.pages.max_depth', 10);
        $cursor = static::query()->find($this->parent_id);
        $depth = 1;

        while ($cursor) {
            if ($this->exists && (int) $cursor->getKey() === (int) $this->getKey()) {
                throw ValidationException::withMessages([
                    'parent_id' => 'A page cannot be its own ancestor.',
                ]);
            }

            if ($depth >= $maxDepth) {
                throw ValidationException::withMessages([
                    'parent_id' => "Page nesting cannot exceed {$maxDepth} levels.",
                ]);
            }

            $cursor = $cursor->parent_id
                ? static::query()->find($cursor->parent_id)
                : null;

            $depth++;
        }
    }
}
