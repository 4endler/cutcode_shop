<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThumbnailController extends Controller
{
    public function __invoke(string $dir, string $method, string $size, string $ext, string $file): BinaryFileResponse
    {
        abort_if(!in_array($size, config('thumbnail.allowed_sizes')), 403, 'Size not allowed');

        $storage = Storage::disk('images');

        $realPath = "$dir/" .File::name($file) . '.' . $ext;
        $newDirPath = "$dir/$method/$size/$ext";
        $resultPath = "$newDirPath/". File::name($file) . '.webp';
        //TODO SVG
        if (!$storage->exists($newDirPath)) {
            $storage->makeDirectory($newDirPath, 0755, true);
        }

        if (!$storage->exists($resultPath)) {
            // dd($storage->get($realPath));
            $image = Image::read($storage->get($realPath));
            [$w, $h] = explode('x', $size);

            $image->{$method}($w, $h);
            // $image->resize($w, $h);
            // dd($realPath, $newDirPath,$resultPath);
            $image->toWebp()->save($storage->path($resultPath));
        }
        // dd($resultPath);
        return response()->file($storage->path($resultPath));

    }
}
