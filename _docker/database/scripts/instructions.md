<!-- psql in docker list databases -->

docker exec -it app_db psql -U postgres -c '\l'

<!-- psql in docker exec file check.sql -->

docker exec -it app_db psql -U postgres -f './scripts/check.sql'
