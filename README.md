start projeto

*Passo 1:*
docker-compose build
*Passo 2:*
docker-compose up -d
*Passo 3:*
 Entre no container
docker exec -it laravel_app bash
*Passo 4:*
composer install ;)
*Passo 5:*
php artisan migrate
*Passo 6:*
php artisan db:seed




 [x] Docker
 [x] Lógica Upload
 [x] Logica Status Import
 [x] Lógica listagem de User Paginada
 [x] Autenticação JWT
 [x] Testes API
 [x] Logs
