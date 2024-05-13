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
docker compose --env-file .\.env.local up -d --wait
```

Stop containers
```
docker compose down --remove-orphans
```

### Prod:

You can use docker compose for prod or build and deploy a prod image.

**Build Prod image**
```
docker build -t somniarooms:prod --target frankenphp_prod .
```

**Deploy prod image**
```
docker run -d -it -p 80:80 -p 443:443 --name somniarooms --env-file ./env.prod.local somniarooms:prod
```
