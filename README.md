

Step 1: Install

Clone the project using the command "git clone <URL_DU_REPO>"

Step 2: Configuration in docker-compose.yml and .env files

1) In the backend/.env file, set your own values for the following paramaters : MYSQL_ROOT_PASSWORD , MYSQL_DATABASE, MYSQL_USER, MYSQL_PASSWORD

2) Modify DATABASE_URL like this : DATABASE_URL="mysql://MYSQL_USER:MYSQL_PASSWORD@127.0.0.1:3306/MYSQL_DATABASE?serverVersion=8.0.32&charset=utf8mb4". Of course, instead of MYSQL_USER, you will write the values you set earlier.
   
3) Copy the file .backend/.env in local : cp .env .env.local
   
4) In the docker-compose.yml file , replace "${MYSQL_ROOT_PASSWORD}", "${MYSQL_DATABASE}", "${MYSQL_USER}", "${MYSQL_PASSWORD}" by the values of this parameter you set in the backend/.env file

Step 3: Set up of the Docker containers 

In the console, at the root of the project , type "docker-compose build" and then "docker-compose up -d"

To check the installation :
Symfony : http://localhost:8080 ; 
PHPMyAdmin : http://localhost:8081 ;  
MySQL will be accessible at port 3307

Step 4: Create the database :

Type docker-compose exec php bin/console doctrine:database:create

Step 5 : Making migrations :

Type docker-compose exec php bin/console make:migration
Type docker-compose exec php bin/console doctrine:migrations:migrate

Step 6: Charger les données de test (fixtures)
Type docker-compose exec php bin/console doctrine:fixtures:load

