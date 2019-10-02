Clone the repository

    git clone https://github.com/aren-nalbandyan/fazwaz.git
    
Switch to the repo folder

    cd fazwaz/
    
Install all the dependencies using composer

    composer install
    
Copy the example env file and make the required configuration changes in the .env file
    
    cp .env.example .env
    
Create database
    
Open .env file via any text editor and change configs of database 

    DB_DATABASE="Your Database Name"
    DB_USERNAME="Your Database USERNAME"
    DB_PASSWORD="Your Database Password"
    
Run Your local server application (You can run it at the beginning)
    
Generate unique key for Your application

    php artisan key:generate

Run Database Migrations

    php artisan migrate
    
Run database seeds

    php artisan db:seed
    
Install node modules

    npm install
    
