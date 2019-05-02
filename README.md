# IoT Containers Backend

This is the backend for the IoT proyect that pretends to store all the data from different devices that read/measure the volume quantity on a container.

# Install Instructions

1. Clone the repo
2. Move to the recently created folder
```shell
$ cd folder
```
3. Make your own environment settings
```shell
$ cp .env.example .env
```

4. Set your database settings don't forget to create your databases 
```shell
$ vi .env
```

5. Install and update composer
```shell
$ composer install
```

6. Generate your artisan key
```shell
$ php artisan key:generate
```

7. Make the migrations
```shell
$ php artisan migrate
```

8. Install passport
```shell
$ php artisan passport:install
```

9. Run the seeds 
```shell
$ php artisan db:seed
```

10. Install yarn globally (if it is not installed)
```shell
$ npm install -g yarn
```

11. Install all npm dependencies
```shell
$ yarn install
```

12. Use yarn instead of npm

    1. `npm run watch` now can be used with `yarn watch`
    2. `npm run dev` now is used as `yarn dev`
    3. To add a new npm package instead of `npm i package-name` just do `yarn add package-name`

