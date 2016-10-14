# Migrations

Este é um exemplo de uso para adicionar migrations no seu sistema legado onde ainda não é utilizado o composer.

## Passo 1: Baixar o composer

Execute os comandos abaixo para instalar o composer:

```sh
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
$ chmod +x /usr/local/bin/composer
```

## Passo 2: Baixar o migrations

Visto que tenho um diretório com os fontes, devo baixar dentro do diretório projeto:

```
projeto/
|
+-- fontes/
```

Execute o comando abaixo para baixar o migrations:

```
$ composer require davedevelopment/phpmig
```

Ficará assim:

```
projeto/
|
+-- fontes/
|
+-- vendor/
    |
    +-- davedevelopment/
        |
        +-- phpmig/
    |  
    +-- autoload.php
composer.json
composer.lock

```

Feito isso, a biblioteca de migrations fica disponivel no seu projeto.

## Passo 3: Criando o arquivo .gitignore

O diretório vendor não deve ficar na pasta do repositório.

Devemos criar o arquivo .gitignore na raiz do projeto:

```
./vendor
```

Novo layout do projeto:

```
projeto/
|
+-- fontes/
|
+-- vendor/
    |
    +-- davedevelopment/
        |
        +-- phpmig/
    |  
    +-- autoload.php
.gitignore    
composer.json
composer.lock

```

## Passo 4: Preparar o projeto


Execute o comando na raiz do seu projeto:

```
$ ./vendor/bin/phpmig init
```

Serão criados o diretório migrations e o arquivo phpmig.php na raiz.


```
projeto/
|
+-- fontes/
|
+-- migrations/
|
+-- vendor/
    |
    +-- davedevelopment/
        |
        +-- phpmig/
    |  
    +-- autoload.php
.gitignore    
composer.json
composer.lock
phpmig.php
```

## Passo 5: Configurar o banco de dados

Antes precisaremos baixar a biblioteca pimple para criarmos o nosso container que será utilizado nas migrations:

```
$ composer require pimple/pimple "~3.0"
```

Agora, devemos editar o arquivo **phpmig.php** da raiz do projeto, adicionando as configurações do banco de dados:

```php
<?php

use Phpmig\Adapter;
use Pimple\Container;

/*
|
| Configurar seu banco de dados
|
*/
$host       = '127.0.0.1';
$database   = 'exemplo';
$username   = 'teste';
$password   = '******';

$container = new Container();

$container['db'] = function () use ($host, $database, $username, $password) {
    $dbh = new PDO('mysql:dbname=' . $database . ';host=' . $host, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

$container['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['db'], 'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
```

Feito isso, podemos criar nossa primeira migration. 

## Passo 6: A primeira migration

Iremos criar como exemplo a tabela de usuários. Execute o comando na raiz do projeto:

```sh
$ ./vendor/bin/phpmig generate create_users_table
```

Será criado o arquivo de migration dentro do diretório de migrations:

```
projeto/
|
+-- fontes/
|
+-- migrations/
    |
    +-- 201610140914447_create_users_table.php
|
+-- vendor/
    |
    +-- davedevelopment/
        |
        +-- phpmig/
    |  
    +-- autoload.php
.gitignore    
composer.json
composer.lock
phpmig.php
```

Ele cria o arquivo no formato AAAAMMDDHHIISS acrescentando o nome da geração do migration.

Ao abrir o arquivo teremos:

```
<?php

use Phpmig\Migration\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {

    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
```

* O método **up()** executa o migration;
* O método **down()** desfaz o migration.

## Passo 7: Adicionar o código no migration

Devemos adicionar o código nos métodos **up** e **down**:

```php
<?php

use Phpmig\Migration\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `users` (
                    `id`            INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `name`          VARCHAR(80),
                    `password`      VARCHAR(80),
                    `created_at`    TIMESTAMP NOT NULL,
                    `updated_at`    TIMESTAMP NOT NULL
                )';
        $container = $this->getContainer(); 
        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `users`';
        $container = $this->getContainer(); 
        $container['db']->query($sql);
    }
}
```

* O método **up()** cria a tabela de usuários;
* O método **down()** apaga a tabela.

## Passo 8: Rodar o nosso migration

É muito simples, basta executar o comando abaixo:

```
$ ./vendor/bin/phpmig migrate
```

Se as configurações do seu banco estiverem corretas, a tabela de usuários será criada.

**Obs:** Ao rodar o comando, será executado o método **up()** dos arquivos de migrations.

## Passo 9: Desfazer o migration

Basta executar o comando abaixo:

```
$ ./vendor/bin/phpmig rollback
```

Ele irá apagar a tabela de usuários, executando o método **down()** do arquivo de migration.

## Considerações finais

Trata-se de um exemplo simples de como implementar o migrations em um sistema legado. 

Aqui não são retratadas todas as possibilidades da biblioteca de migrations. Maiores informações pode-se consultar no repositório do criador:

* [https://github.com/davedevelopment/phpmig](https://github.com/davedevelopment/phpmig)