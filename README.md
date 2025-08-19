# Chat REST api

## Fejlesztés

### Code style (pint)

```bash
docker-compose exec app bash -c "./vendor/bin/pint"
```

### Code quality (PHPStan)

```bash
docker-compose exec -u www-data app bash -c "./vendor/bin/phpstan analyse"
```

### Static analysis (Psalm)
```bash
docker-compose exec -u www-data app bash -c "./vendor/bin/psalm"
```
