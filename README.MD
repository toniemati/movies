# laravel tmdb movies app

## Project setup

### Clone project
```
git clone https://github.com/toniemati/movies.git
```

### Copy env, change DB CREDENTIALS and TMDB_API_KEY
```
cp .env.example .env
```

### Instal dependencies
```
composer install
```
```
npm install
```

### Generate key
```
php artisan key:gen
```

### Build assets
```
npm run build
```

### Run migrations
```
php artisan migrate:fresh
```

### Dispatch jobs
```
php artisan sync:movies
```
```
php artisan sync:series
```
```
php artisan sync:genres
```

### Start application
```
php artisan serve
```

### Visit
```
localhost:8000/movies
```
