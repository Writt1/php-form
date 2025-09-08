### Запуск

Для работы приложения необходимо установить зависимости используя Composer.

```shell
composer install
```

Для запуска проекта используется Docker Compose.

```shell
docker-compose up -d
```

### База данных

Для работы приложения необходимо создать базу данных и импортировать в нее дамп, который находится в папке `database`.
```shell
docker exec -i mysql4 mysql -u root -proot form_db < database/dump.sql
```

### Конфигурация

Создайте файл .env по примеру .env.example с ключами для YandexCaptcha.
