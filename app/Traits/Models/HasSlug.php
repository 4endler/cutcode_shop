<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Generates a unique slug for the model and assigns it to the model's
     * {@see slugColumn()} attribute.
     *
     * The generated slug is based on the value of the attribute returned by
     * {@see slugFrom()}.
     */
/*******  caca6c3c-9fe1-4a1a-bd6e-8098c8ddd885  *******/
    protected function makeSlug(): void
    {
        if (!$this->{$this->slugColumn()}) {
            $slug = $this->slugUnique(
                str($this->{$this->slugFrom()})
                    ->slug()
                    ->value()
            );

            $this->{$this->slugColumn()} = $slug;
        }
    }
    protected function slugColumn(): string
    {
        return 'slug';
    }
    protected function slugFrom(): string
    {
        return 'title';
    }

    private function slugUnique(string $slug): string
    {
        $originalSlug = $slug;
        $i = 0;
        
        while ($this->isSlugExists($slug)) {
            $i++;
            $slug = $originalSlug . '-' . $i;
        }

        return $slug;
    }

    private function isSlugExists(string $slug): bool
    {
        $query = $this->newQuery()
            ->where(self::slugColumn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();

        return $query->exists();
    }
}