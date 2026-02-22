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

### Generate key
```
php artisan key:gen
```

### Instal dependencies
```
composer install
```
```
npm install
```

### Build assets
```
npm run build
```

### Build assets
```
npm run build
```

### Run migrations
```
php artisan migrate:fresh
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
