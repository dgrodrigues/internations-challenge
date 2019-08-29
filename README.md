# Internations Frontend Challenge
### About 

This is the result of an exercise to present to insternations evaluation as part of the recruitment proccess. In this exercise 3 pages are created. An Homepage, for welcoming the user and buttons to create groups and users. A Users page, to list, create and delete users. Lastly, a Groups page, to list, add and remove grous. In the last page it's also possible to manage users, adding or removing from a group multiple users. 

### Tech

This was my first contact with Symfony framework. I had previous knowledge of VueJs from some tutorials, but this was my first serious implementation with the framework. Other than Synfony and VueJs, I used docker to create containers, running the website in a isolated environment and PostgreSQL for the database. This is my first project created from the beginning with docker in mind.

### How to run  
>To run this project docker must be installed in machine
    
**1.** In terminal navigate to folder `cd docker`\
**2.** Run `docker-compose build`\
**3.** Then `docker-compose up -d`
    
>At first run, composer installs dependencies, so it may take a while to load. To check logs and see if container it's up and running run in the terminal `docker logs -f docker_php_1`. docker_php_1 name can change so if an error of container not found is thrown, check the name of the containers listed in last 3 lines of command number 3. Change command for the one with php in it's name. 


### Only at first run
**a)** After container starts run `docker-compose run php /usr/bin/composer install` to install compose depedencies;\
**b)** After that, migrate entities to database running `docker-compose run php bin/console doctrine:migrations:migrate`; Agree to schema changes;\
**c)** load fixtures for a initial group `docker-compose run php bin/console doctrine:fixtures:load`; Agree to database purge (currently empty);

##### Open website accessing http://localhost:8080  

**NOTE:** In case a new composer package is needed 'docker-compose run php composer install';\
**NOTE:** In case changes in models are made run `docker-compose run php bin/console make:migration` to generate new migrations, then re-run step **b** from "only at first run" section.
