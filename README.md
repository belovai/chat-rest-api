# Chat REST api

[![Tests](https://github.com/belovai/chat-rest-api/actions/workflows/tests.yaml/badge.svg)](https://github.com/belovai/chat-rest-api/actions/workflows/tests.yaml)

> This is a demo project, not for production use.

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

## Használat

Készítettem egy alap Postman collection-t, amivel a funkciók kipróbálhatók: [postman_collection.json](docs/postman_collection.json)

## Fejlesztés

### Code style (pint)

```bash
docker-compose exec app bash -c "./vendor/bin/pint"
```

### Statikus analízis (PHPStan)

```bash
docker-compose exec -u www-data app bash -c "./vendor/bin/phpstan analyse"
```

### Tesztek
```bash
docker-compose exec app bash -c "php artisan test"
```
