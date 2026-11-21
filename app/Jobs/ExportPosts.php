<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ExportPosts implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user) {}

    public function handle(): void
    {
        $path = "exports/user-{$this->user->id}.csv";
        Storage::put($path, 'id;title');

        // Par paquets de 500 : la mémoire reste plate,
        // même avec 100 000 posts.
        $this->user->posts()
            ->select(['id', 'title'])
            ->lazyById(500)
            ->each(fn ($p) => Storage::append(
                $path, "{$p->id};{$p->title}"
            ));
    }
}
