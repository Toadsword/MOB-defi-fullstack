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

until docker exec defi-fullstack-db-1 pg_isready -U admin_user -d mydb; do
  sleep 1
  echo " Waiting for Postgres to be ready..."
done


echo "Running tests..."

# Test stations exists
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "\dt stations" | grep stations >/dev/null \
  && echo "✔ stations exists" \
  || (echo "✘ stations does NOT exist" && sleep 5 && exit 1)

# Test distances exists
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "\dt distances" | grep distances >/dev/null\
  && echo "✔ distances exists" \
  || (echo "✘ distances does NOT exist" && sleep 5 && exit 1)
  
# Test anayltics_routes exists
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "\dt anayltics_routes" | grep anayltics_routes >/dev/null\
  && echo "✔ anayltics_routes exists" \
  || (echo "✘ anayltics_routes does NOT exist" && sleep 5 && exit 1)


# Test inserted data
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "SELECT * FROM stations;" | grep CHCO >/dev/null\
  && echo "✔ Inserted data into stations OK" \
  || (echo "✘ Data not inserted into stations correctly" && sleep 5 && exit 1)

# Test inserted data
docker exec defi-fullstack-db-1 psql -U admin_user -d mydb -c "SELECT * FROM distances;" | grep 1.68 >/dev/null\
  && echo "✔ Inserted data into distances OK" \
  || (echo "✘ Data not inserted into distances correctly" && sleep 5 && exit 1)

echo "All DB init tests passed!"
sleep 5