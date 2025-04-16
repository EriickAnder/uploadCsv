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
Crie um database chamado vexpense
*ATUALIZAR O ENV PARA AS CREDENCIAIS DO BANCO CRIADO *

*Passo 6:*
php artisan migrate

*Passo 7:*
php artisan db:seed


-----------------------------------

* Comando para startar a fila de jobs *
php artisan queue:work
( é necessário rodar dentro do bash do terminal docker [docker exec -it laravel_app bash])


------------------------------------

Usuário criado no seed para consumo da api:
User: admin@admin.com
Pass: password

Url: 127.0.0.1:8000/api/

- O projeto foi criado em MVC, conforme solicitado nos requisitos.
- Services criados como camada na aplicação
- Decidi criar uma tabela auxiliar para salvar o histórico dos jobs, visto que ao utilizar queue do próprio laravel a tabela sempre é limpa após a execução.
- Tomei a liberdade de personalizar alguns arquivos, o middleware/authenticate e o handler, visto que a aplicação será apenas para consumo de API.
- Preferi criar um helper de upload, caso tenha a necessidade de escalar a api, facilitar na reutilização de código e definir bem a responsabilidade de cada função no sistema

* Observações
- Alguns testes adicionais poderiam ter sido realizados, principalmente no consumo do CSV e atualização dos status da queue. Caso seja necessário posso realiza-los.
- Caso tenha a necessidade que a rota /users retorne apenas os usuarios cadastrados pelo usuário autenticado na api, basta criar uma coluna a mais na tabela vinculando o user ao responsável pelo envio.
- O mesmo se aplica para a fila e logs.







 [x] Docker
 [x] Lógica Upload
 [x] Logica Status Import
 [x] Lógica listagem de User Paginada
 [x] Autenticação JWT
 [x] Testes API
 [x] Logs
