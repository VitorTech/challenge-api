
## Requirements

-   Docker: https://docs.docker.com/engine/install/
-   Docker Compose: https://docs.docker.com/compose/install/

## Installation

-   `git clone https://github.com/VitorTech/challenge-api.git`
-   `cd challenge-api/`
-   `mkdir _docker/database/data`
-   `sudo chmod -R 777 _docker/database/data`
-   `sudo chmod -R 777 storage`
-   `cp .env.example .env`
-   `docker-compose up -d`
-   `Done! You can access all available API resources on http://localhost:8888/api`

> If you are using a local database and needs to take a look on it, open your favorite database DBMS (Ex: DBeaver), create a PostgreSQL connection and connect it to challenge_api database

-   `docker exec -it challenge-api bash -c "zsh"`
-   `composer install`
-   `php artisan migrate --seed`
-   `php artisan passport:install`
-   `service supervisor start`

## Running Tests

-  `./vendor/bin/phpunit`
