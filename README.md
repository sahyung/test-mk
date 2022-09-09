# MamiKos Backend Test

laravel app for Backend technical test at MamiKos

## Quickstart

```sh
git clone https://github.com/sahyung/test-mk.git
cd test-mk
cp .env.example .env

# config database and .env
composer install
php artisan migrate --seed
php artisan serve
```

## Recharge user's credit

- Manually

    ```sh
    php artisan schedule:run
    ```

    you can also modify `app/Console/Kernel.php:28` from `monthly()` to `everyMinute()` for testing

- Using Crontab

    ```sh
    crontab -e 
    ```

    add this line

    ```shell
    * * * * * /path/to/php /path/to/artisan schedule:run 1>> /dev/null 2>&1
    ```

## To-do list

### Backend Developer is asked to create API to

- :white_check_mark: Register as owner / regular user / premium user
- :white_check_mark: Allow owner to add, update, and delete kost
- :white_check_mark: Allow owner to see his kost list
- :white_check_mark: Allow user to search kost that have been added by owner
- :white_check_mark: Allow user to see kost detail
- :white_check_mark: Allow user to ask about room availability
- :white_check_mark: Allow user to ask about room availability

### Requirements

- :white_check_mark: Regular user will be given 20 credit, premium user will be given 40 credit after register. Owner will have no credit.
- :white_check_mark: Owner can add more than 1 kost
- :white_check_mark: Search kost by several criteria: name, location, price
- :white_check_mark: Search kost sorted by: price
- :white_check_mark: Ask about room availability will reduce user credit by 5 point
- :white_check_mark: Owner API & ask room availability API need to have authentication
- :white_check_mark: Implement scheduled command to recharge user credit on every start of the month
- :x: Bonus point if you can create Owner dashboard that use your API
