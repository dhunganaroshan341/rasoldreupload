<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected static function booted()
    {
        static::created(function ($post) {
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'model' => 'Post',
                'model_id' => $post->id,
                'changes' => json_encode($post->getAttributes()),
                'url' => url()->current(),
            ]);
        });

        static::updated(function ($post) {
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'update',
                'model' => 'Post',
                'model_id' => $post->id,
                'changes' => json_encode($post->getChanges()),
                'url' => url()->current(),
            ]);
        });

        static::deleted(function ($post) {
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'model' => 'Post',
                'model_id' => $post->id,
                'changes' => json_encode($post->getAttributes()),
                'url' => url()->current(),
            ]);
        });
    }
}
