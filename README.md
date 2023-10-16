# Symfony 6

## Установка проекта

1. Откройте файл `.env` и установите секретный ключ:
   APP_SECRET=your_secret_here

2. Для запуска проекта выполните следующую команду в корневой директории:
```shell
$ make up
```
Или, если make не установлен, используйте:
```shell
$ sudo docker-compose -f docker-compose.yml --env-file=.env up -d --build --remove-orphans
```
3. Для установки зависимостей, выполните:
```shell
$ make composer_install
```
Или, если make не установлен, используйте:
```shell
$ sudo docker exec -t symfony6-php-fpm bash -c 'composer install'
```
4. Для папки var в корне проекта установите расширенные права:
```shell
$ sudo chmod -R 777 ./var
```
5. Для применения миграций, в корне проекта, выполните:
```shell
make migration
```
Или, если make не установлен, используйте:
```shell
sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:migrations:migrate --no-interaction'
```
6. После успешной установки, получите документацию по следующей ссылке:
   http://127.0.0.1:9580/api/doc

## Рабочие URL
```shell
    http://127.0.0.1:9580/api/doc
    http://127.0.0.1:9580/api/workers
    http://127.0.0.1:9580/api/worker/{id}
    http://127.0.0.1:9580/api/worker/{id}
    http://127.0.0.1:9580/api/worker/{id}
    http://127.0.0.1:9580/api/worker/create
```