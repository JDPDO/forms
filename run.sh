#!/bin/bash

# Start dev server on `localhost:8081`
# Assuming executing user has access to a running docker daemon.

function onCancel {
    docker-compose down -v --rmi local
    docker-compose rm -sv 
}

trap onCancel EXIT

NODE_ENV=development npm run dev
# @see https://unix.stackexchange.com/questions/611675/run-a-command-in-parallel-and-wait-for-specific-output
docker-compose up --build |
    tee /dev/tty | 
    {
        grep -q "apache2 -D FOREGROUND" && sleep 3 && echo "Enable forms app." && docker-compose exec -d -u www-data nextcloud php occ app:enable forms
        cat > /dev/null
    }