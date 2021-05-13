<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post {
    public $title;
    public $publishDate;
    public $excerpt;
    public $body;
    public $slug;

    public function __construct($title, $publishDate, $excerpt, $body, $slug) {
        $this->title = $title;
        $this->publishDate = $publishDate;
        $this->excerpt = $excerpt;
        $this->body = $body;
        $this->slug = $slug;
    }

    public static function all() {
        return collect(File::files(resource_path('posts')))
            ->map(function($file) {
                return YamlFrontMatter::parseFile($file);
            })
            ->map(function($doc) {
                return new Post($doc->title, $doc->publishDate, $doc->excerpt, $doc->body(), $doc->slug);
            });
    }

    public static function find($uriSlug) {
        return static::all()->firstWhere('slug', $uriSlug);
        // $path = resource_path("posts/{$uriSlug}.html");

        // if(!file_exists($path)) {
        //     throw new ModelNotFoundException();
        // }

        // return cache()->remember("posts.{$uriSlug}", now()->addHour(), function () use ($path) {
        //     return file_get_contents($path);
        // });
    }
}