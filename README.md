# Symfony 6

## Установка проекта

1. Переименовываем `.env.dist` в `.env`
   Сгенерировать ключ
```shell
echo -n $(date) | md5sum
```
   Откройте файл `.env` и установите секретный ключ:
   APP_SECRET=your_secret_here

2. Для запуска проекта выполните следующую команду в корневой директории:
```shell
$ make up
```

3. Для установки зависимостей, выполните:
```shell
$ make composer_install
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

7. Генерируем ключи для lexik 
```shell
make jwt_generate
```

8. Для запуска тестов:
Один раз запускаем 
```shell
   test_install
```
Для запуска тестов:
```shell
   make test
```

Тесты проверяют подключение, создание, обновление, получение, удаление из списка Workers

## Рабочие URL
```shell
    GET    http://127.0.0.1:9580/api/doc
    GET    http://127.0.0.1:9580/api/workers
    GET    http://127.0.0.1:9580/api/worker/{id}
    PUT    http://127.0.0.1:9580/api/worker/{id}
    DELETE http://127.0.0.1:9580/api/worker/{id}
    POST   http://127.0.0.1:9580/api/worker/create
```