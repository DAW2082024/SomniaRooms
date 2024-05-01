# SomniaRooms

A Hotel Room booking system.

Powered by:
- PHP Symfony.
- Easyadmin.

## Commands:

Build containers:
```
docker compose build --no-cache
```

Start Containers:
```
docker compose up --pull always -d --wait
docker compose --env-file .\.env.local up -d --watch
```

Stop containers
```
docker compose down --remove-orphans
```