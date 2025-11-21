#!/bin/bash
set -e

echo "Starting database with fresh volume..."
docker compose down -v
docker compose build db
docker compose up -d db

echo "Waiting for Postgres to be ready..."

until [ "$(docker inspect -f '{{.State.Health.Status}}' defi-fullstack-db-1)" == "healthy" ]; do
  sleep 1
  echo "Waiting for DB container to be healthy..."
done

until docker exec defi-fullstack-db-1 pg_isready -U admin_user -d mydb > /dev/null 2>&1; do
  sleep 1
  echo " Waiting for Postgres to be ready..."
done


echo "Running tests..."

# Test stations exists
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "\dt stations" | grep stations \
  && echo "✔ stations exists" \
  || (echo "✘ stations does NOT exist" && sleep 5 && exit 1)

# Test distances exists
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "\dt distances" | grep distances \
  && echo "✔ distances exists" \
  || (echo "✘ distances does NOT exist" && sleep 5 && exit 1)

# Test inserted data
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "SELECT * FROM distances;" | grep 10.5 >/dev/null \
  && echo "✔ Inserted data OK" \
  || (echo "✘ Data not inserted correctly" && sleep 5 && exit 1)

echo "All DB init tests passed!"
sleep 5