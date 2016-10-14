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
projeto
|_ fontes
```

Execute o comando abaixo para baixar o migrations:

```
$ composer require davedevelopment/phpmig
```

Ficará assim:

```
projeto
|_ fontes
|_ vendor
   |_ davedevelopment
      |_ phpmig
   |_ autoload.php

```

Feito, agora a biblioteca de migrations fica disponivel no seu projeto.