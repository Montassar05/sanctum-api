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
        $csv = $this->user->posts()
            ->get(['id', 'title'])
            ->map(fn ($p) => "{$p->id};{$p->title}")
            ->prepend('id;title')
            ->implode("\n");

        Storage::put("exports/user-{$this->user->id}.csv", $csv);
    }
}
