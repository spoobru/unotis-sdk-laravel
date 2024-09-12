# Unotis API PHP client library

### Будьте внимательны:

Пользователи с бесплатным тарифом могут отправлять сообщения только на указанную при регистрации или подтверждённую почту.

Для отправки сообщения в Telegram, добавьте нашего бота в мессенджере и отправьте ему ключ, который можно получить в настройках.

Все подробности - в нашей [документации](https://unotis.ru/documentation).

## Установка:

`composer require spoob/unotis-laravel`

## Настройка:

1. В `.env` укажите:

```
UNOTIS_TOKEN=%ВАШ_ТОКЕН%
```

2. (необязательно) Опубликуйте файл конфигурации

```php
php artisan vendor:publish -tag=unotis-config
```

## Использование:
```
use Spoob\UnotisLaravel\UnotisClient as Unotis;

// Просто создать сообщение в системе
$response = Unotis::createMessage('Тема сообщения', 'Текс сообщения');

// Создать сообщение и отправить на почту
$response = Unotis::sendEmail('example@email', 'Тема письма', 'Текст <strong>письма</strong>');

// Создать сообщение и написать в Telegram
$response = Unotis::writeToTelegram('Тема сообщения в Телеграм', 'Текст сообщения');
```
