## Install the Application

1 - Atualize/Installe as dependencias do composer

```bash
make update
```

2 - Rode a aplicação, o comando irá subir os tres dockers que a aplicação necessita para funcionar
    - wallet-mysql
    - wallet-php-fpm
    - wallet-webserver

```bash
make run
```

3 - Execute a migração, esse comando irá configurar as migrações contidas em resources/migrations/sql

```bash
make migrate
```

4 - Enjoy, a aplicação devera estar funcionando em [localhost](http://localhost:8080)

xdebug port 9001.

## List of users on database
- user 1 = store, balance = 50
- user 2 = comum, balance = 100
