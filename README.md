# MetAI

versione dei linguaggi usati durante lo sviluppo

* versione PHP 7.4.3 (PHP > 7.2.5)
* versione Laravel 7.30.4
* versione mySQL 8.0.27
* versione Composer 1.10.1

Installazione 

Installare Composer
`sudo apt-get install composer`

Installare Laravel
-composer global require "laravel/installer"

-Scaricare il progetto nella cartella di lavoro.
-Creare in mySQL il Database.
-Aprire il file .env (non .env.example) e inserire a riga uno (APP_NAME) il nome della applicazione e riga 12,13 e 14 (DB_DATABASE, DB_USERNAME, DB_PASSWORD) inserendo il nome del database appena creato, il nome e la password con la quale si accede a mySQL.


Configurare il progetto
Dalla cartella principale del progetto digitare questi commandi.
php artisan key:generate
php artisan config:cache

Database 
Dalla cartella principale del progetto digitare questi commandi.
-php artisan migrate
-php artisan db:seed

Righe da cambiare all'interno del progetto (avevo inserito delle limitazioni per problemi di prestazioni))
DI QUERYCONTROLLER
-Riga 370 $retmax mettere 10000
-Riga 1067 $retmax mettere 10000
-Riga 2102 rimuovere il "limit by 100" della query $result

DI TEST.PY E APPLY.PY (Riga 59 e 46)
-Cambiare di test.py e apply.py device=torch.device('cpu') a GPU

Inserire nella cartella principale la cartella di biobert e i pesi salvati e cambiare nel file test.py e apply.py il percorso assoluto (Riga 59 e 60 per apply e riga 83 e 84 per test)

Eseguire il server
-php artisan serve

A questo punto visitando il link ​ http://127.0.0.1:8000​ , troverete la pagina predefinita
“welcome” del progetto

Fare il login tramite le credenziali : "user@progetto.it"
pw : "password"
