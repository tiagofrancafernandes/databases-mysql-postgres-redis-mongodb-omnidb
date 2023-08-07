#!/bin/bash

CURRENT_DIR=$(dirname $(readlink -f "$0"))
BASE_DIR=$(realpath "${CURRENT_DIR}/../")

PROJECT_DIR=${PROJECT_DIR:-"${BASE_DIR}"}

if [ ! -d ${PROJECT_DIR} ]; then
    echo -e "Error!"
    echo -e "env 'PROJECT_DIR' is not a valid directory"
    echo -e ""
    exit 4
fi

DOCKER_COMPOSE_BIN=${DOCKER_COMPOSE_BIN:-/usr/local/bin/docker-compose}

SERVICES_TO_STOP=${SERVICES_TO_STOP}

echo -e "Runing file:"
echo -e "'${CURRENT_DIR}/$0' $@"
echo -e ""
echo -e "Params: $@"
echo -e ""

if [ -d $PROJECT_DIR ]; then
    cd "${PROJECT_DIR}"

    if [ -f "docker-compose.yml" ]; then
        ${DOCKER_COMPOSE_BIN} stop ${SERVICES_TO_STOP} $@
    fi
fi
