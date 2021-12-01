# Desafio
## _Autor: Fábio Sousa de Sant'Ana_

Projeto pessoal, cuja finalidade principal é apresentar uma sugestão de APi para procedimentos de transferências financeiras simplificada entre usuários. Projeto em Laravel.

## Funções

- Criação de usuário 
- Transferências financeiras online entre usuários.
- Consulta de saldo.
- Notificação de transação financeira.
- Por padrão, o sistema criará uma Companhia (Company Teste, com idUser = 1) com o saldo de R$ 1000.00 para testarmos a API.

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

## Incialização do Projeto

Após ter clonado o repositório e acessar o diretório ```desafio```, proceda conforme abaixo.

- Inicie o container e também o webserver integrado do Laravel, na porta 8080.
    ```
    docker-compose up -d && docker-compose exec app bash -c "php artisan serve --port 8080 --host 0.0.0.0" &
    ```
    Aperte ```ENTER``` após a execução do comando.
    
- Faz o carregamento das estruturas iniciais do banco de dados.

    ```
    docker-compose exec -T db sh -c 'exec mysql -uroot -p"DGCq!54Lbr*7"' < dump.sql
    ```

    Caso retorne uma mensagem de insegurança, não se preocupe. Esse contexto de importação de estrutura inicial é para ambientes de desenvolvimento e não corremos riscos.
-     
    
## Configuração de Ambiente (Laravel)

- Adicione as seguintes variáveis ao arquivo ```app/sistemas/.env```. Por padrão, algumas configurações já foram inseridas, verifique.

    ```
    EXTERNAL_AUTHORIZATION_MOCK=https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6
    NOTIFICATION_SENDER_MOCK=http://o4d9z.mocklab.io/notify
    ```

- Altere as configurações do seu banco de dados e do servidor de envio de e-mail.

    ```
    DB_CONNECTION=mysql
    DB_HOST={IP DO HOST FISICO}
    DB_PORT=3307
    DB_DATABASE=challenge
    DB_USERNAME=root
    DB_PASSWORD=DGCq!54Lbr*7 (previamente configurada em docker-composer.yml)

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=username
    MAIL_PASSWORD=password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=desafio@teste
    ```

- Altere o mecanismo de filas para database.
    ```
    QUEUE_CONNECTION=database
    ```

- Baixe as dependências do projeto através do comando:

    ```
    docker-compose exec app bash -c "composer update"
    ```    

- Agora, devemos rodar as migrations. No console, dentro do diretório ```app/sistemas``` (diretório raíz do Laravel), digite:

    ```
    docker-compose exec app bash -c "php artisan migrate:refresh"
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