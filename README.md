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
php artisan vendor:publish --tag=unotis-config
```

## Использование:

Отправка сообщений

```
use Unotis;

// Просто создать сообщение в системе
$response = Unotis::createMessage('Тема сообщения', 'Текс сообщения');

// Создать сообщение и отправить на почту
$response = Unotis::sendEmail('example@email', 'Тема письма', 'Текст <strong>письма</strong>');

// Создать сообщение и написать в Telegram
$response = Unotis::writeToTelegram('Тема сообщения в Телеграм', 'Текст сообщения');
```

Отладка ошибок

1. В .env укажите 
```
UNOTIS_PROJECT_TOKEN=%ТОКЕН_ПРОЕКТА%
``` 

2a. (Laravel < 11) Добавьте вызов `Unotis::catchException()` в метод **report()** класса **app\Exceptions\Handler.php**:


```
<?php

use Unotis;
...

class Handler extends ExceptionHandler
{
    ...

    public function report(Throwable $exception)
    {
        ...

        if ($this->shouldReport($exception)) {
            Unotis::catchException($exception);
        }

        ...
    }
}

...
```
2b. (Laravel 11) Добавьте вызов `Unotis::catchException()` в **bootstrap/app.php**:

```php
...

use Unotis;

...
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->report(function (InvalidOrderException $e) {
        Unotis::catchException($exception);
    });
})
...
```
