#!/bin/sh
set -eu

cd /app

wait_for_db() {
  host="${DB_HOST}"
  port="${DB_PORT}"
  echo "Waiting for database at ${host}:${port}..."
  i=0
  while [ "$i" -lt 60 ]; do
    if nc -z "$host" "$port" >/dev/null 2>&1; then
      return 0
    fi
    i=$((i + 1))
    sleep 2
  done
  echo "Database is not reachable after waiting."
  return 1
}

envsubst < Config.template.properties > Config.properties
wait_for_db

exec java -server -Dfile.encoding=UTF-8 -cp "/app/build/classes:/app/lib/*" nro.models.server.ServerManager
