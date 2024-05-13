# SomniaRooms - How to deploy.

SomniaRooms is designed to be easy to deploy and use.

Production images for Docker Containers are available from Gitea Registry.

Documentation on how to deploy them as containers can be found at [Readme](../README.md).

## Docker compose examples.

Here are some deploy examples using docker compose:

SomniaRooms back-end and front-end can run on the same machine, but some config is needed to work.

### Deploy back-end and front-end on different servers (different ip address).
If your servers have different IP addresses everything is a little bit easier. You can set-up subdomains for each one.

Use the following docker compose files for back-end and front-end.

Back-end:
```yml
services:
  somniarooms-back:
    image: gitea.uberelectronnetwork.cc/somnia/somniarooms:devbuild
    restart: unless-stopped
    environment:
      SERVER_NAME: https://api.somnia.dev # <-- Your hostname here!
      DATABASE_URL: postgresql://somnia:ChangeMe!@database/somniarooms?serverVersion=16&ch>      MERCURE_PUBLISHER_JWT_KEY: ChangeThisMercureHubJWTSecretKey!
      MERCURE_SUBSCRIBER_JWT_KEY: ChangeThisMercureHubJWTSecretKey!
      APP_SECRET: ChangeMySecret
      FRANKENPHP_CONFIG: "" # <-- Dont touch this line unless you know what are doing.
    volumes:
      - caddy_data:/data
      - caddy_config:/config
    ports:
      # HTTP
      - target: 80
        published: 80
        protocol: tcp
      # HTTPS
      - target: 443
        published: 443
        protocol: tcp
      # HTTP/3
      - target: 443
        published: 443
        protocol: udp
    depends_on:
      - database

  database:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: somniarooms
      # You should definitely change the password in production
      POSTGRES_PASSWORD: ChangeMe!
      POSTGRES_USER: somnia
    healthcheck:
      test: ["CMD", "pg_isready"]
      timeout: 5s
      retries: 5
      start_period: 60s
    volumes:
      - database_data:/var/lib/postgresql/data:rw
      # You may use a bind-mounted host directory instead, so that it is harder to acciden>      # - ./docker/db/data:/var/lib/postgresql/data:rw

volumes:
  caddy_data:
  caddy_config:
  database_data:
```

Config details
- SERVER_NAME -> if set to https a SSL cert will be obtained. If you set http, SSL will be disabled.


Front-end:
```yml
services:
  somniarooms-front:
    image: gitea.uberelectronnetwork.cc/somnia/somniaroomsapp:devbuild
    restart: unless-stopped
    environment:
      # You should set your public IP/hostname to back-end.
      SOMNIAROOMS_BACKEND_HOST: https://api.somnia.dev # <-- Your back-end hostname here!
      SOMNIAROOMS_BACKEND_PORT: 443 # <-- Your back-end port here! (443 if using https)
    ports:
      # HTTP
      - target: 80
        published: 80
      # HTTPS
      - target: 443
        published: 443
```


### Deploy on the same server.
Right now, it's quite difficult to set back-end app on a port different from 80/443. Back-end uses Caddy as webserver and automatically try to get SSL certs from let's encrypt.

My go-to option will be using some reverse-proxy to redirect requests to back or front by domain name.

This is an example of deployment using Traefik.
```yml
services:
  reverse-proxy:
    # The official v3 Traefik docker image
    image: traefik:v3.0
    restart: unless-stopped
    # Enables the web UI and tells Traefik to listen to docker
    command:
      - --api.insecure=true
      - --providers.docker
      - --entrypoints.web.address=:80
      - --entrypoints.websecure.address=:443
      - --entrypoints.web.http.redirections.entryPoint.to=websecure
      - --entrypoints.web.http.redirections.entryPoint.scheme=https
      - --certificatesresolvers.lets-encrypt.acme.tlschallenge=true
      - --certificatesresolvers.lets-encrypt.acme.email=your_email # <-- Your email here!
      - --certificatesresolvers.lets-encrypt.acme.storage=/letsencrypt/acme.json
    ports:
      # The HTTP port
      - "80:80"
      # The HTTPS port
      - "443:443"
      # The Web UI (enabled by --api.insecure=true)
      - "8080:8080"
    volumes:
      # So that Traefik can listen to the Docker events
      - /var/run/docker.sock:/var/run/docker.sock
      # acme.json should be created on host instance
      - .certs/:/letsencrypt/

  somniarooms-back:
    image: gitea.uberelectronnetwork.cc/somnia/somniarooms:devbuild
    restart: unless-stopped
    environment:
      SERVER_NAME: https://api.somnia.dev # <-- Your hostname here!
      DATABASE_URL: postgresql://somnia:ChangeMe!@database/somniarooms?serverVersion=16&charset=utf8
      MERCURE_PUBLISHER_JWT_KEY: ChangeThisMercureHubJWTSecretKey!
      MERCURE_SUBSCRIBER_JWT_KEY: ChangeThisMercureHubJWTSecretKey!
      APP_SECRET: ChangeMySecret
      FRANKENPHP_CONFIG: "" # <-- Dont touch this line unless you know what are doing.
    volumes:
      - caddy_data:/data
      - caddy_config:/config
    depends_on:
      - database
    labels:
      #  HTTPS YOUR APP
      - "traefik.enable=true"
      - "traefik.http.routers.somniarooms-back.rule=Host(`api.somnia.dev`)" # <-- Your back-end hostname here!
      - "traefik.http.routers.somniarooms-back.entrypoints=websecure"
      - "traefik.http.routers.somniarooms-back.tls=true"
      - "traefik.http.routers.somniarooms-back.tls.certresolver=lets-encrypt"

  somniarooms-front:
    image: gitea.uberelectronnetwork.cc/somnia/somniaroomsapp:devbuild
    restart: unless-stopped
    environment:
      # You should set your public IP/hostname to back-end.
      SOMNIAROOMS_BACKEND_HOST: https://api.somnia.dev # <-- Your back-end hostname here!
      SOMNIAROOMS_BACKEND_PORT: 443 # <-- Your back-end port here! (443 if using https)
    depends_on:
      - somniarooms-back
    labels:
      - "traefik.http.routers.somniarooms-front.rule=Host(`app.somnia.dev`)" # <-- Your front-end hostname here!


  database:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: somniarooms
      # You should definitely change the password in production
      POSTGRES_PASSWORD: ChangeMe!
      POSTGRES_USER: somnia
    healthcheck:
      test: ["CMD", "pg_isready"]
      timeout: 5s
      retries: 5
      start_period: 60s
    volumes:
      - database_data:/var/lib/postgresql/data:rw
      # You may use a bind-mounted host directory instead, so that it is harder to acciden>      # - ./docker/db/data:/var/lib/postgresql/data:rw

volumes:
  caddy_data:
  caddy_config:
  database_data:

```


## What's next?

Remember you can run your docker compose files using:
```
docker compose up -d --wait
```