# API Laravel 12 sécurisée avec Sanctum

Code des YouTube Shorts de la chaîne **Code en 60s** (série Sanctum + astuces Laravel). Tout le code montré dans les vidéos est ici et testé.

| Short | Fichiers |
|---|---|
| 1. Protéger l'API | `routes/api.php`, `app/Models/User.php` |
| 2. Créer un token au login | `app/Http/Controllers/AuthController.php` |
| 3. Tester l'API protégée | `tests/Feature/AuthApiTest.php` |
| 4. Le piège N+1 (`with`, `preventLazyLoading`) | `app/Providers/AppServiceProvider.php`, `tests/Feature/LazyLoadingTest.php` |
| 5. Bloquer le bruteforce du login (`throttle:5,1`) | `routes/api.php`, `tests/Feature/ThrottleLoginTest.php` |
| 6. API Resource : choisir ses champs | `app/Http/Resources/UserResource.php`, `tests/Feature/UserResourceTest.php` |
| 7. `Cache::remember` : zéro requête au 2e appel | `routes/api.php`, `tests/Feature/StatsCacheTest.php` |
| 8. Mass assignment (`$fillable`) | `app/Models/User.php`, `app/Http/Controllers/RegisterController.php`, `tests/Feature/MassAssignmentTest.php` |
| 9. Form Request | `app/Http/Requests/StorePostRequest.php`, `tests/Feature/StorePostRequestTest.php` |
| 10. Policy : seul l'auteur modifie | `app/Policies/PostPolicy.php`, `tests/Feature/PostPolicyTest.php` |
| 11. SoftDeletes + restore | `app/Models/Post.php`, `tests/Feature/SoftDeleteTest.php` |
| 12. Scope `Post::published()` | `app/Models/Post.php`, `tests/Feature/PublishedScopeTest.php` |
| 13. Job en queue (202 immédiat) | `app/Jobs/ExportPosts.php`, `tests/Feature/ExportQueueTest.php` |
| 14. `Http::fake` : tests sans internet | `app/Http/Controllers/GithubController.php`, `tests/Feature/GithubHttpFakeTest.php` |

## Lancer le projet

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

## Endpoints

| Méthode | URL | Auth |
|---|---|---|
| POST | `/api/login` (`email`, `password`, `device`) → `{ "token": "..." }` — 5 essais/min | non |
| GET | `/api/user` | `Authorization: Bearer <token>` |
| GET | `/api/me` (UserResource : `id`, `name`, `email`) | `Authorization: Bearer <token>` |
| GET | `/api/stats` (mis en cache 10 min) | non |
| POST | `/api/register` (`name`, `email`, `password`) | non |
| GET | `/api/posts` (publiés uniquement) | non |
| POST/PUT/DELETE | `/api/posts/{id}` (auteur uniquement) | `Authorization: Bearer <token>` |
| POST | `/api/exports` → 202 | `Authorization: Bearer <token>` |
| GET | `/api/github/{username}` | non |
| POST | `/api/logout` (révoque le token courant) | `Authorization: Bearer <token>` |

## Tests

```bash
php artisan test
```
