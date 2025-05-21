## Mini Todo App

### Requirements
Docker & Docker Compose installed on your machine
Clone this repository

### Setup

#### Copy .env.example to .env and customize if needed
```shell
cp .env.example .env
```

```shell
docker-compose up -d --build
```

#### Run composer and npm install inside container:
```shell
docker exec -it your_app_container_name composer install
docker exec -it your_app_container_name npm install
```
#### Generate app key and migrate
```shell
docker exec -it your_app_container_name php artisan key:generate
docker exec -it your_app_container_name php artisan migrate
```

#### Run dev

```shell
docker exec -it your_app_container_name npm run dev
```

#### Open http://localhost in your browser



