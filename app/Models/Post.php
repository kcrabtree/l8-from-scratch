<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

class Post {
    public static function all() {
        $files = File::files(\resource_path('posts/'));
 
        return array_map(function ($file) {
            return $file->getContents();
        }, $files);
    }

    public static function find($uriSlug) {
        $path = resource_path("posts/{$uriSlug}.html");

        if(!file_exists($path)) {
            throw new ModelNotFoundException();
        }

        return cache()->remember("posts.{$uriSlug}", now()->addHour(), function () use ($path) {
            return file_get_contents($path);
        });
    }
}