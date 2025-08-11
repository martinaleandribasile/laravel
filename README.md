
# Magazzino Corsi.it – Documentazione Progetto

Gestione magazzino informatizzata, sviluppata con stack Laravel + Vue 3 + Inertia.js. Questo progetto implementa una SPA moderna con autenticazione, gestione utenti/admin, richieste materiali e statistiche.

---

## Stack Tecnologico

- **Laravel 12.x**
- **Laravel Breeze** (autenticazione, scaffolding)
- **Inertia.js** (SPA Laravel + Vue 3)
- **Vue 3**
- **Tailwind CSS** (UI, responsive, forms)
- **Chart.js** + **vue-chartjs** (grafici statistici)
- **Ziggy** (routing JS <-> Laravel)
- **Sanctum** (API authentication)
- **Vite** (build assets)
- **Composer, npm**

## Dipendenze principali

### Composer (PHP)
- laravel/framework
- laravel/breeze
- laravel/sanctum
- inertiajs/inertia-laravel
- tightenco/ziggy
- nunomaduro/collision (debug)
- fakerphp/faker (test)
- mockery/mockery (test)
- phpunit/phpunit (test)
- laravel/pint (code style)
- laravel/tinker (REPL)
- laravel/sail (dev env)

### npm (JS)
- @inertiajs/inertia
- @inertiajs/inertia-vue3
- @inertiajs/progress
- @tailwindcss/forms
- @vitejs/plugin-vue
- axios
- chart.js
- vue
- vue-chartjs
- laravel-vite-plugin
- postcss, autoprefixer
- tailwindcss

## Comandi CLI custom

Esegui da terminale nella root Laravel:

### Creazione rapida di un nuovo item
```sh
php artisan items:create --name="Monitor LED" --category="Monitor" --description="Monitor 27 pollici"
```

### Aggiornamento stato pezzi in base alle date di utilizzo
```sh
php artisan items:update-status
```

## Seeder e Factory

- Seeder e factory personalizzati per popolare rapidamente il database con dati realistici (vedi `database/seeders` e `database/factories`).

## Test

- Test automatici (vedi cartella `tests/`).

## Struttura principale del progetto

- `app/Console/Commands/` – Comandi artisan custom
- `app/Http/Controllers/` – Controller Laravel
- `resources/js/Pages/` – Componenti Vue 3 (Admin/User)
- `resources/css/app.css` – Stili globali (Tailwind + override)
- `routes/web.php` – Routing Laravel (con Inertia, Ziggy)
- `database/seeders/` – Seeder
- `database/factories/` – Factory
- `tests/` – Test automatici

## Avvio rapido

1. Clona la repo e installa le dipendenze:
	```sh
	composer install
	npm install
	cp .env.example .env
	php artisan key:generate
	# Configura DB in .env
	php artisan migrate --seed
	npm run dev
	php artisan serve
	```
2. Accedi su http://localhost:8000

