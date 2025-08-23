# Chat REST api

## Telepítés

```bash
## pull repository
git clone git@github.com:belovai/chat-rest-api.git

## .env létrehozása
cp .env.example .env

## docker indítása
docker-compose up -d

## app key generálása
docker-compose exec app bash -c "php artisan key:generate"

## migráció és db seed
docker-compose exec app bash -c "php artisan migrate --seed"
```

Az alkamazás a http://localhost:8080/ címen érhető el, a port módosítható a .env-ben és a docker-compose.yaml-ben.

A regisztráció során küldött email a http://localhost:8025/ címen érhető el a Mailpit-ben. Ennek a portját is lehet módosítani a docker-compose.yaml-ben.


## Fejlesztés

### Code style (pint)

```bash
docker-compose exec app bash -c "./vendor/bin/pint"
```

### Statikus analízis (PHPStan)

```bash
docker-compose exec -u www-data app bash -c "./vendor/bin/phpstan analyse"
```

### Statikus analízis (Psalm)
```bash
docker-compose exec -u www-data app bash -c "./vendor/bin/psalm"
```

### Tesztek
```bash
docker-compose exec app bash -c "php artisan test"
```
