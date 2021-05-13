<?php

namespace App\Models;

class Post {
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