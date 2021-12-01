docker-compose up -d && docker-compose exec app bash -c "php artisan serve --port 8080 --host 0.0.0.0" &

docker-compose exec -T db sh -c 'exec mysql -uroot -p"desafio123"' < dump.sql

