<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class GithubController extends Controller
{
    public function repos(string $username)
    {
        $repos = Http::get("https://api.github.com/users/{$username}")
            ->throw()
            ->json('public_repos');

        return ['repos' => $repos];
    }
}
