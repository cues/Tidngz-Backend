#!/bin/zsh
# run_migration.sh
# Small helper to run the migration SQL using XAMPP's mysql binary.
# Prompts for DB host/user/password/database (defaults to local XAMPP values).
# Usage: chmod +x run_migration.sh && ./run_migration.sh

SQL_FILE="$(dirname "$0")/0001_add_unique_index_places_following.sql"
MYSQL_BIN="/Applications/XAMPP/xamppfiles/bin/mysql"

if [ ! -f "$SQL_FILE" ]; then
  echo "Migration file not found: $SQL_FILE"
  exit 1
fi

if [ ! -x "$MYSQL_BIN" ]; then
  echo "MySQL binary not found or not executable at: $MYSQL_BIN"
  echo "Please ensure XAMPP is installed and adjust MYSQL_BIN in this script if needed."
  exit 1
fi

read -r -p "MySQL host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -r -p "MySQL user [root]: " DB_USER
DB_USER=${DB_USER:-root}

# Read password silently
read -s -r -p "MySQL password (will be hidden, press Enter if empty): " DB_PASS
echo

read -r -p "Database name [Tidngz]: " DB_NAME
DB_NAME=${DB_NAME:-Tidngz}

echo "About to run migration: $SQL_FILE"
echo "Host: $DB_HOST  User: $DB_USER  Database: $DB_NAME"
read -r -p "Proceed? [y/N]: " CONFIRM
CONFIRM=${CONFIRM:-N}

if [[ "$CONFIRM" != "y" && "$CONFIRM" != "Y" ]]; then
  echo "Aborted by user."
  exit 0
fi

# Run the migration. Use -p"$DB_PASS" to avoid an interactive prompt. If password is empty, omit -p flag.
if [ -n "$DB_PASS" ]; then
  "$MYSQL_BIN" -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"
else
  "$MYSQL_BIN" -h "$DB_HOST" -u "$DB_USER" "$DB_NAME" < "$SQL_FILE"
fi

RC=$?
if [ $RC -eq 0 ]; then
  echo "Migration completed successfully."
else
  echo "Migration failed (exit code: $RC). Check the SQL file and the MySQL logs for details."
fi

exit $RC
