Start project
==================================

# Deploy repository #

Please, clone this repository form github, by this options:

Option|Links
-------|--------------------------
SHH|[git@github.com:cyber-collab/native-php.git](git@github.com:cyber-collab/native-php.git)
HTTPS|[https://github.com/cyber-collab/native-php.git](https://github.com/cyber-collab/native-php.git)

# How to run docker #

Dependencies:

* docker. See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
* docker-compose. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

Once you're done, simply `cd` to your project and run `docker-compose up -d`. This will initialise and start all the
containers, then leave them running in the background.

## Services exposed outside your environment ##

You can access your application via **`localhost`**. Mailhog and nginx both respond to any hostname, in case you want to
add your own hostname on your `/etc/hosts`

Service|Address outside containers
-------|--------------------------
Webserver|[localhost](http://localhost)

# Start docker #

1. Please, clone from .env.example to .env and setup settings for database
2. Run `composer install` for install dependencies for this project

# How to using this functionality #

1. Please, going to [localhost](http://localhost)
2. Press on button Register or Login if you have account
3. After login you redirect to your profile when you can add Movies or upload file with movies
4. After create survey you can going on your list movies when you can edit your Movie
5. After making all manipulation with movies, you can going to list movies which is available to all users going home.
