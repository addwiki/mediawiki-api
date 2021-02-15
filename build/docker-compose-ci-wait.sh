#!/bin/bash

repo_path=$(dirname $(dirname $(realpath $0)))

# Wait for apache to actually be running
for i in {1..30}
do
    docker-compose --project-directory $repo_path --file $repo_path/docker-compose-ci.yml logs mediawiki | grep "+ apache2-foreground" | wc -l | grep 1
    if [ $? -eq 0 ]
    then
        sleep 1
        break
    fi
    sleep 1
done
