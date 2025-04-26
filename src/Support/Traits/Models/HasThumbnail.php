<?php

namespace Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

trait HasThumbnail
{
    abstract protected function thumbnailDir(): string;
    public function makeThumbnail(string $size, string $method = 'cover'): string
    {
        return route('thumbnail', [
            'size' => $size,
            'dir' => $this->thumbnailDir(),
            'method' => $method,
            'file' => File::name($this->{$this->thumbnailColumn()}),
            'ext'=> File::extension($this->{$this->thumbnailColumn()}),
            'toext' => 'webp',
        ]);
    }

    protected function thumbnailColumn(): string
    {
        return 'thumbnail';
    }
}