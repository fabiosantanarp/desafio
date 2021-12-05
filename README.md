# Desafio
## _Autor: Fábio Sousa de Sant'Ana_


Projeto pessoal, cuja finalidade principal é apresentar uma sugestão de APi para procedimentos de transferências financeiras simplificada entre usuários. Projeto em Laravel.

## Funções

- Criação de usuário comum.
- Criação de usuário companhia.
- Transferências financeiras online entre usuários.
- Consulta de saldo.
- Notificação de transação financeira.
- Por padrão, o sistema criará uma Companhia (Company Teste, com idUser = 1) com o saldo de R$ 5000 para testarmos a API.

## Documentação API

 - A documentação da API pode ser obtida <a href="https://documenter.getpostman.com/view/13833204/UVJcnH87" target="_blank">aqui</a>

## Ferramentas

O projeto utiliza o ferramentário a seguir:

- Docker-compose
- Laravel v8.0
- Mysql 5.0


## Arquitetura do Projeto

 - A Análise de Requisitos pode ser obtida em <a href="analise-requisitos-proposta-tecnica.pdf">analise-requisitos-proposta-tecnica.pdf</a>.
 - A documentação do banco de dados pode ser obtida em <a href="database-description.pdf">database-description.pdf</a>.
 - Diagrama Casos de Uso pode ser obtido <a href="casos-de-uso.pdf">aqui</a>
 - A documentação da API pode ser obtida <a href="https://documenter.getpostman.com/view/13833204/UVJcnH87" target="_blank">aqui</a>


## Dependências

- <a href="https://docs.docker.com/compose/">Docker-Compose</a>

## Clone do projeto

```git clone https://github.com/fabiosantanarp/desafio.git```

## Configuração do Docker

- Abra o diretório ```desafio``` e edite o arquivo docker-compose.yml informando uma senha para o usuário ```root``` através da variável ``` MYSQL_ROOT_PASSWORD ```.

## Crie o ambiente Laravel dentro do container.

- Dentro do diretório ```desafio```, digite os comandos abaixo:

    ```
    docker-compose up -d
    docker-compose exec app bash -c "cd .. && composer create-project laravel/laravel new"
    docker-compose exec app bash -c "cd .. && cp -r sistema/* new"
    docker-compose exec app bash -c "cd .. && rm -rf sistema && mv new sistema"
    docker-compose exec app bash -c "composer update"    
    ```
    
## Configuração do Variáveis

- Copie o arquivo ```app/sistema/.env.example``` para ```app/sistema/.env``` e edite conforme abaixo.

    ```
    EXTERNAL_AUTHORIZATION_MOCK=https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6
    NOTIFICATION_SENDER_MOCK=http://o4d9z.mocklab.io/notify
    ```

- Altere as configurações do seu banco de dados e do servidor de envio de e-mail.

    ```
    DB_CONNECTION=mysql
    DB_HOST={IP DO HOST FISICO}
    DB_PORT=3306 (previamente configurada em docker-composer.yml)
    DB_DATABASE=challenge
    DB_USERNAME=root
    DB_PASSWORD= (previamente configurada em docker-composer.yml)

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.server.com
    MAIL_PORT=587
    MAIL_USERNAME=username
    MAIL_PASSWORD=password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=desafio@teste
    ```

- Altere o mecanismo de filas para database, se não estiver.
    ```
    QUEUE_CONNECTION=database
    ```

- Crie uma nova encryption key
    ```
    docker-compose exec app bash -c "php artisan key:generate"
    ```
- Crie uma nova chave para o JWT responsável pela autenticação da API

    ```
    docker-compose exec app bash -c "php artisan jwt:secret"
    ```

## Incialização dos serviços


Ainda dentro do diretório ```desafio```, proceda como abaixo:

- Inicie o webserver integrado do Laravel, na porta 8080.
    ```
    docker-compose exec app bash -c "php artisan serve --port 8080 --host 0.0.0.0 &" 
    ```
    Aperte ```ENTER``` após a execução do comando.        

- Faz o carregamento das estruturas iniciais do banco de dados com a {SENHA} criada no arquivo docker-compose.yml

    ```
    docker-compose exec -T db sh -c 'exec mysql -uroot -p{SENHA}' < dump.sql
    ```
    Caso retorne uma mensagem de insegurança, não se preocupe. Esse contexto de importação de estrutura inicial é para ambientes de desenvolvimento e não corremos riscos.


- Agora, devemos rodar as migrations:

    ```
    docker-compose exec app bash -c "php artisan migrate:refresh"
    ```

- E também o Seeder.

    ```
    docker-compose exec app bash -c "php artisan db:seed --class=UserApiSeeder"
    ```

- (opcional) Caso queira executar as filas de e-mail, utilize:
    ```
    docker-compose exec app bash -c "php artisan queue:work"
    ```
    Obs.: Esse comando pode travar o terminal, aguardando disparo de e-mails.

## Realização de Testes (PHPUnit)

- (opcional) Para rodar os testes, execute:

    ```
    docker-compose exec app bash -c "php artisan test"
    ```    

## Enjoy!
