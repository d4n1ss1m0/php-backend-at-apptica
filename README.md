# PHP BACKEND at apptica

Описание проекта:  
_Модуль Application Top Category Positions – получение данных о позициях
приложения в топе по категориям за определенный день._

---

## Содержание

- [Описание проекта](#project-name)
- [Установка](#установка)
- [Запуск](#запуск)
- [Конфигурация](#конфигурация)
- [Используемые технологии](#используемые-технологии)
- [API](#api)
- [Логирование](#логирование)
- [Возможные проблемы и их решение](#возможные-проблемы-и-их-решение)

---

## Установка

1. Клонируем репозиторий:
```bash
git clone https://github.com/d4n1ss1m0/php-backend-at-apptica
cd php-backend-at-apptica
make install
```

2. Устанавливаем зависимости:
```bash
docker compose exec app composer install
```

3. Создаем базу данных и применяем миграции:
```bash
docker compose exec app php artisan migrate --seed
```

4. На выбор: Заполняем БД данными и создаем индекс + добавляем в ElasticSearch
```bash
docker compose exec app php artisan app-top-category:fill
```
или создать только индекс
```bash
docker compose exec app php artisan elasticsearch:mapping_top_position
```
В БД добавятся данные с (Текущая дата - 30 дней) по (Текущая дата - 10 дней), то есть за месяц без последних 10 дней (для теста автоматического добавления в БД и ElasticSearch).

Если команда не будет запущена, то сервис по поиску будет выдавать ошибку "Index not found".

## Запуск
```bash
make up
```

Сервисы
- laravel_app - Laravel
- laravel_web - Nginx
- laravel_db - PosgreSQL
- laravel_redis - Redis
- laravel_elastic - ElasticSearch

## Конфигурация
.env – основные переменные окружения: настройки API, ключи и токены

## Используемые технологии
- PHP 8.2 / Laravel 12
- PostgreSQL 15
- Docker + Docker Compose
- Elasticsearch (для поиска)
- Redis (ограничение по количеству запросов)

## API
Эндпоинт для получения позиций в топ чарте маркета по категориям.
```
GET /api/appTopCategory?date={{date}}
- date - дата загрузки данных. Формт Y-m-d
```

Если данные есть в БД (и в ElasticSearch соответственно), то данные берутся из ElasticSearch
Если данных нет, то отправляется запрос в предоставленное API и сохраняется в БД и в ElasticSearch

## Логирование
Логи хранятся в 
```
storage/logs/laravel.log
```

## Возможные проблемы и их решение

Иногда при работе с Composer или `curl` внутри контейнера запросы к GitHub
(`https://api.github.com`) могут зависать или падать с ошибкой `curl error 28`.

Это связано с тем, что Docker bridge-сеть наследует MTU=1500 с хоста, но на некоторых
сетях (Wi-Fi, VPN, некоторые провайдеры) реально доступный размер пакета меньше (~1400).  
В результате HTTPS-пакеты режутся, и соединение обрывается.

В `docker-compose.yml` сеть `laravel` создаётся с `MTU=1400`:
```yaml
networks:
  laravel:
    driver: bridge
    driver_opts:
      com.docker.network.driver.mtu: 1400
```