## How to run
1. 'docker-compose build' 
2. 'docker-compose up -d'

## At first run
3. migrate entities to database running 'docker-compose run php bin/console doctrine:migrations:migrate'
4. load fixtures for a initial group 'docker-compose run php bin/console doctrine:fixtures:load'

### At first run, composer installs dependencies so it may take a while to load

check ---- to se logs

### How was built
    *Previously, when developing, the following command was used to create a symfony boilerplate*
    *'docker-compose run php composer create-project symfony/website-skeleton .'*

NOTE: In case new composer packages are needed 'docker-compose run php composer install'
NOTE: In case changes in models are made run 'docker-compose run php bin/console make:migration' to generate new migrations, then re-run number 3.
NOTE: Generate Repositories with 'docker-compose run php bin/console make:entity --regenerate'

