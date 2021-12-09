# MetAI

Software versions
* version of PHP 7.4.3 (PHP > 7.2.5)
* version of Laravel 7.30.4
* version of mySQL 8.0.27
* version of Composer 1.10.1 

Installation of Composer
`sudo apt-get install composer`

Installation of Npm
`sudo apt install npm`

Installation of Laravel
`composer global require laravel/installer`

Installation of mySQL-Server 
* `sudo apt install mysql-server`
* https://dev.mysql.com/downloads/workbench/ choose Ubuntu Linux and Ubuntu Linux 20.04 (x86, 64-bit), DEB Package

Creation of User for mySQL
* `sudo mysql -u root -p`
* `CREATE USER 'yourname'@'localhost' IDENTIFIED BY 'password';`
* `GRANT ALL PRIVILEGES ON * . * TO 'yourname'@'localhost';`
* `FLUSH PRIVILEGES;`

Creation of a connection
* Open mySQL workbench 
* Click on the plus icon
* Set Connection name 
* Check that hostname and port are 127.0.0.1 and 3306
* Enter the username you created earlier with the corresponding password

Installion of PHP-mySQL
`sudo apt-get install php-mysql`

Installion of Php-xml
`sudo apt-get install php7.X-xml` (the x marks your php version check with `php --version`)

Configure the project
* Download the project to your working folder.
* Create on mySQL the Database (right click on schemas and select create new schema).
* Open the file .env (not .env.example) and change to line one (APP_NAME) the name of the application and line 12,13 e 14 (DB_DATABASE, DB_USERNAME, DB_PASSWORD) by entering the name of the database just created, the name and the password with which you access mySQL.
<br/>From the root folder.<br/>
* `php artisan key:generate`
* `php artisan config:cache`

Database 
<br/>From the root folder.<br/>
* `php artisan migrate`
* `php artisan db:seed`

Parameters to change within the project.<br/>
1. QUERYCONTROLLER
- Line 370 $retmax change to 10000
- Line 1067 $retmax change to 10000
- Line 2102 remove the "limit by 100" from the query $result

2. TEST.PY E APPLY.PY (Riga 59 e 46)
- Change of test.py and apply.py device=torch.device('cpu') to GPU
- Insert in the main folder the biobert folder and the saved_weights change in the file test.py and apply.py the absolute path (Line 59 e 60 for apply and line 83 and 84 for test)

Execute the server
`php artisan serve`

open a browser to go to the local page (127.0.0.1:8000) and log in

Login info
* Username: `user@progetto.it`
* password: `password`
