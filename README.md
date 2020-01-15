To install project:

**composer install**

then configure database in .env

**php bin/console doctrine:database:create**

To run the console command:

**php bin/console app:import --filePath <path-to-your-csv-file>**