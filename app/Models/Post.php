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
        return cache()->rememberForever('posts.all', function() {
            return collect(File::files(resource_path('posts')))
            ->map(function($file) {
                return YamlFrontMatter::parseFile($file);
            })
            ->map(function($doc) {
                return new Post($doc->title, $doc->publishDate, $doc->excerpt, $doc->body(), $doc->slug);
            })->sortByDesc('publishDate');
        });
    }

    public static function find($uriSlug) {
        return static::all()->firstWhere('slug', $uriSlug);
    }

    public static function findOrFail($uriSlug) {
        $post = static::find($uriSlug);

        if(!$post) {
            throw new ModelNotFoundException();
        }

        return $post;
    }
}