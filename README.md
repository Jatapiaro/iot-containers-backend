# IoT Containers Backend

This is the backend for the IoT proyect that pretends to store all the data from different devices that read/measure the volume quantity on a container.

# Install Instructions
The following instructions asume that you already have Composer and then Laravel Installed. Please check how to install Composer on your system and laravel.

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

10. If you need to reset the database
```shell
$ php artisan migrate:refresh
```

11. Install yarn globally (if it is not installed)
```shell
$ npm install -g yarn
```

12. Install all npm dependencies
```shell
$ yarn install
```

12. Use yarn instead of npm

    1. `npm run watch` now can be used with `yarn watch`
    2. `npm run dev` now is used as `yarn dev`
    3. To add a new npm package instead of `npm i package-name` just do `yarn add package-name`

# Retrieving and using access tokens

You need to get a client for your applications/clients. So, we need to get the client token in order to allow our users to consume our API.

1. Generate a new client
```shell
$ php artisan passport:client --password
```

2. Then call it from your client
```
    // Make a post to http://your.server/oauth/token
    {
        'grant_type' => 'password',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'username' => 'taylor@laravel.com',
        'password' => 'my-password',
        'scope' => '',
    }
```

# Documenting the API with swagger

## Accesing the documentation

In your browser access `http://your.domain/api/v1/documentation`.
For the routes that need authentication:
1. In the swagger view we have a green button that says authorize.
2. Pass in the form the exact same data that you use to get your acces token when you use Postman

## Generating the documentation
Simply check the examples on the other controllers and models.

Finally execute 
```shell
$ php artisan l5-swagger:generate
```
to generate all the documentation again.

# Development Recomendations
1. Never Ever Ever Develop On Master
2. Always code in english
3. Always create a new branch for your tasks. **Do not develop on master** 
