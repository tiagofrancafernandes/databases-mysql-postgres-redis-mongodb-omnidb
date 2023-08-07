#!/bin/bash
### Beta version

command_exists() {
    command -v "$1" > /dev/null 2>&1
}

PSQL_BIN=${PSQL_BIN:-$(which psql)}
DB_HOST=${DB_HOST:-'localhost'}
DB_PORT=${DB_PORT:-5432}
DB_USER=${DB_USER:-postgres}
PGPASSWORD=${PGPASSWORD:-postgres}
DB_PASSWORD=${DB_PASSWORD:-${PGPASSWORD}}
DB_DATABASE=${DB_DATABASE:-db_to_import}
SQL_FILE=${SQL_FILE:-$1}

if [ ! -f "${SQL_FILE}" ]; then
    echo -e "";
    echo -e "File not found";
    echo -e "";
    exit 40;
fi

if [ ! -f "${PSQL_BIN}" ]; then
    echo -e "";
    echo -e "Invalid psql binary";
    echo -e "";
    exit 50;

    if command_exists psql; then
        PSQL_BIN=$(which psql)
    else
        echo "Please install a postgres CLI client (psql)"
        exit 60
    fi
fi

export PGPASSWORD=$DB_PASSWORD
#PGPASSWORD="postgres" psql -U postgres -p 5432 -h 192.168.100.52 dd_name < sctipt-sql.sql
${PSQL_BIN} -U ${DB_USER} -p ${DB_PORT} -h ${DB_HOST} ${DB_DATABASE} < "${SQL_FILE}"
