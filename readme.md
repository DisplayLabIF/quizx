# Para rodar o projeto

- docker-compose build
- docker-compose up -d
- composer install
- yarn install
- yarn dev

seu ambiente está funcionando

# Testes automatizados

Para executar os testes execute os comandos na sequencia.

//criar banco para teste. Caso o banco exista, apague e execute os comandos
php bin/console --env=test doctrine:database:create

php bin/console --env=test doctrine:schema:create

//executar fixtures no bd de teste
php bin/console doctrine:fixtures:load --env=test

//executar testes
php ./vendor/bin/phpunit

