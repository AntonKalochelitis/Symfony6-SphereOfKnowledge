# Symfony

### Install project 

- 1) Откройте файл .env и установите секретный ключ APP_SECRET=your_secret_here
- 
- 2) Для старта проекта запустите в корневой дирректории
- `$ make up`
- или если не установлен make, запустите
- `sudo docker-compose -f docker-compose.yml --env-file=.env up -d --build --remove-orphans`
- 
- 3) Для установки зависимостей, запускаем `compose install`
- 
- 4) В корне проекта  для папки var выделяем расширенные права `sudo chmod -R 777 ./var`
- 
- 5) Для применения миграций запускаем `$ make migration`
- 
- 6) Получение документации по ссылке:
- http://127.0.0.1:9580/api/doc

### Working url

- http://127.0.0.1:9580/api/workers
- http://127.0.0.1:9580/api/worker/{id}
- http://127.0.0.1:9580/api/worker/{id}
- http://127.0.0.1:9580/api/worker/{id}
- http://127.0.0.1:9580/api/worker/create