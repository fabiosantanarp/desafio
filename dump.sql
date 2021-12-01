create DATABASE challenge;

use challenge;


/*==============================================================*/
/* Table: Company                                               */
/*==============================================================*/
create table Company
(
   idCompany            int not null auto_increment comment 'Chave Primária. Identifica a Companhia.',
   idUser               int not null comment 'id do usuário - Identifica o usuário.',
   cnpj                 varchar(18) not null comment 'Número do CNPJ da companhia. Deverá ser informado de forma completa. Ex: "00.000.000/0000-00"',
   corporateName        varchar(50) not null comment 'Razão social da Companhia.',
   canTransfer          boolean not null default false comment 'Informa se a companhia pode transferir para outros usuários. Padrão: false.',
   primary key (idCompany)
);

alter table Company comment 'Possibilita o cadastro de pessoas jurídicas (empresas, orgai';

/*==============================================================*/
/* Table: Operation                                             */
/*==============================================================*/
create table Operation
(
   idOperation          int not null auto_increment comment 'Chave primária. Identifica uma operação.',
   idUser               int not null comment 'Chave estrangeira. Identifica um usuário',
   idTransaction        int comment 'Chave estrangeira. Identifica uma transação.',
   operationType        enum('credit', 'debit') not null comment 'Tipo de operação que será realizada.  Pode ser: Crédito (credit) ou Débito (debit).',
   operationValue       decimal(18,2) not null comment 'Valor da operação realizada. Esse valor deverá ser sempre positivo, mesmo que o tipo da operação seja ''debit''.',
   primary key (idOperation)
);

alter table Operation comment 'Identifica cada operação realizada. A operação poderá ser de';

/*==============================================================*/
/* Table: Person                                                */
/*==============================================================*/
create table Person
(
   idPerson             int not null auto_increment comment 'Chave Primária. Identifica a pessoa.',
   idUser               int not null comment 'id do usuário - Identifica o usuário.',
   cpf                  varchar(14) not null comment 'Número do CPF da pessoa. Deverá ser informado de forma completa. Ex: ''123.456.789-01''.',
   firstName            varchar(50) not null comment 'Primeiro nome da pessoa. Ex: Pedro.',
   lastName             varchar(100) not null comment 'Nome da pessoa, com exceção do primeiro nome. Ex: ''de Sousa Carvalho''',
   primary key (idPerson)
);

alter table Person comment 'Possibilita o cadastro de pessoas físicas. Não poderão ser c';

/*==============================================================*/
/* Table: Transaction                                           */
/*==============================================================*/
create table Transaction
(
   idTransaction        int not null auto_increment comment 'Chave Primária. Id da Transação.',
   idUserPayer          int not null comment 'Chave Estrangeira. Identifica o Emissor da transferência.',
   idUserPayee          int not null comment 'Chave Estrangeira. Identifica o Beneficiário da transferência.',
   createdAt            datetime not null comment 'Data da criação do registro.',
   primary key (idTransaction)
);

alter table Transaction comment 'Possibilita o cadastro de transações de transferências reali';

/*==============================================================*/
/* Table: User                                                  */
/*==============================================================*/
create table User
(
   idUser               int not null auto_increment comment 'id do usuário - Chave primária da tabela. Identifica o usuário.',
   typeUser             enum('person', 'company') not null comment 'Tipo de Usuário. Pode ser do tipo person quando pessoa física ou company quando lojista.',
   email                varchar(100) not null comment 'Campo de e-mail. Exemplo: fulano@aaa.bbb.',
   password             varchar(60) not null comment 'Hash de Senha do usuário.',
   createdAt            datetime not null comment 'Data da criação do registro.',
   updatedAt            datetime comment 'Data da ultima atualizacao do registro.',
   deletedAt            datetime comment 'Data da exclusão do registro.',
   primary key (idUser)
);

alter table User comment 'Possibilita o cadastro de usuários que acessarão o sistema e';

/*==============================================================*/
/* Index: UN_user_email                                         */
/*==============================================================*/
create unique index UN_user_email on User
(
   email
);

alter table Company add constraint FK_user_company foreign key (idUser)
      references User (idUser) on delete restrict on update restrict;

alter table Operation add constraint FK_operation_transaction foreign key (idTransaction)
      references Transaction (idTransaction) on delete restrict on update restrict;

alter table Operation add constraint FK_user_operation foreign key (idUser)
      references User (idUser) on delete restrict on update restrict;

alter table Person add constraint FK_user_person foreign key (idUser)
      references User (idUser) on delete restrict on update restrict;

alter table Transaction add constraint FK_user_transaction_payee foreign key (idUserPayee)
      references User (idUser) on delete restrict on update restrict;

alter table Transaction add constraint FK_user_transaction_payer foreign key (idUserPayer)
      references User (idUser) on delete restrict on update restrict;


# Insert First Business 

INSERT INTO User
(typeUser, email, password, createdAt, updatedAt, deletedAt)
VALUES('company', 'company@teste.com', '$2y$10$le40AFDfOKJ.zE9ldMY.Qu9Q8X2ozZxTupAb5jwVBunOkXuUQNQXS', '2021-11-30 12:11:00', NULL, NULL);

INSERT INTO Company
(idUser, cnpj, corporateName, canTransfer)
VALUES(1, '13.610.321/0001-01', 'Company Teste', true);

INSERT INTO Transaction
(idUserPayer, idUserPayee, createdAt)
VALUES(1, 1, '2021-11-30 13:02:55');

INSERT INTO Operation
(idUser, idTransaction, operationType, operationValue)
VALUES(1, 1, 'credit', 1000.00);




