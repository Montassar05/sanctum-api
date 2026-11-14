# API Laravel 12 sécurisée avec Sanctum

Code de la série YouTube Shorts **« Sanctum en 60 s »** (chaîne *Code en 60s*).

| Short | Fichiers |
|---|---|
| 1. Protéger l'API | `routes/api.php`, `app/Models/User.php` |
| 2. Créer un token au login | `app/Http/Controllers/AuthController.php` |
| 3. Tester l'API protégée | `tests/Feature/AuthApiTest.php` |

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
| POST | `/api/login` (`email`, `password`, `device`) → `{ "token": "..." }` | non |
| GET | `/api/user` | `Authorization: Bearer <token>` |
| POST | `/api/logout` (révoque le token courant) | `Authorization: Bearer <token>` |

## Tests

```bash
php artisan test
```
