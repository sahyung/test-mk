# MamiKos Backend Test
laravel app for Backend technical test at MamiKos

## Quickstart
```sh
$ git clone https://github.com/sahyung/test-mk.git
$ cd test-mk
$ cp .env.example .env

# config database and .env
$ composer install
$ php artisan migrate --seed
$ php artisan serve
```

## Recharge user's credit
- Manually 
    ```sh
    $ php artisan schedule:run
    ```
    you can also modify `app/Console/Kernel.php:28` from `monthly()` to `everyMinute()` for testing

- Using Crontab
    ```sh
    $ crontab -e 
    ```
    add this line
    ```shell
    * * * * * /path/to/php /path/to/artisan schedule:run 1>> /dev/null 2>&1
    ```
