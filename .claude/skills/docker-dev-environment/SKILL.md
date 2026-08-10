---
name: docker-dev-environment
description: Use when starting, stopping, or debugging the local dev stack for this project — running the site, resetting the database, or figuring out why PHP/MySQL isn't reachable. Covers the docker-compose services, ports, volumes, first-time setup (composer + DB seed), and a database-name gap between docker-compose and the app.
---

# Local dev environment

Defined in `docker-compose.yml` at the repo root:

| Service      | Image/build       | Port (host)     | Notes                               |
|--------------|--------------------|------------------|--------------------------------------|
| `web`        | local `Dockerfile` (php:8.3-apache) | 8080 → 80 | serves `./src`, mounted live |
| `db`         | mysql:8.0          | 3306             | user `dbuser`/`dbpass`, db `mydb`, root pass `rootpass` |
| `phpmyadmin` | phpmyadmin          | 8081             | connects to `db` as root/rootpass    |

## Common commands

- Start: `docker-compose up -d` (add `--build` after editing the
  Dockerfile).
- Site: http://localhost:8080 — phpMyAdmin: http://localhost:8081.
- Logs: `docker-compose logs -f web` / `logs -f db`.
- Status: `docker-compose ps`.
- `./src` is bind-mounted into `web` at `/var/www/html` — PHP edits take
  effect on refresh, no rebuild/restart needed. Only rebuild when the
  Dockerfile itself changes.
- `docker-compose down -v` wipes the `db_data` volume (destructive —
  confirm with the user before running this; it deletes the database).

## First-time setup (fresh clone or fresh `db_data` volume)

Two things `docker-compose up` does **not** do for you:

1. **Composer autoloader.** `vendor/` is gitignored and the `web` image
   doesn't ship Composer, so a fresh clone has no `vendor/autoload.php` —
   `index.php` fatals with "Failed opening required vendor/autoload.php".
   Install Composer into the running container (one-off, not persisted in
   the image) and run it:
   ```
   docker exec wiki-web-1 sh -c "curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"
   docker exec -w /var/www/html wiki-web-1 composer install --no-interaction
   ```
   `composer.lock` has zero packages — this just regenerates the PSR-4
   autoloader for the `MDJ\` and `ManKind\` namespaces, no network deps to
   resolve. See [[wiki-repository-pattern]] for what `ManKind\` actually is.

2. **The `wiki` database.** `docker-compose.yml`'s `db` service only
   auto-creates a database called **`mydb`** (via `MYSQL_DATABASE`), but
   `config.php` (`Config::PDODATABASE`) and every seed dump in
   `src/assets/db/` target a database literally named **`wiki`**. On a
   fresh volume you must create and seed it yourself:
   ```
   docker exec wiki-db-1 mysql -uroot -prootpass -e "CREATE DATABASE IF NOT EXISTS wiki;"
   docker exec -i wiki-db-1 mysql -uroot -prootpass wiki < src/assets/db/tables.sql
   ```
   (`tables.sql` is the fuller of the two dumps in `assets/db/` — 12 tables
   with seed data; `db.sql` is a smaller, partial, work-in-progress dump.)
   `config.php`'s current credentials (`PDOHOST = 'db'`, `PDOUSER = 'root'`,
   `PDOPASS = 'rootpass'`) are valid against this container — `root`/
   `rootpass` come from `MYSQL_ROOT_PASSWORD` and have full access to any
   database, including `wiki`, not just the auto-created `mydb`.

If `docker-compose down -v` wipes `db_data`, both of the above need
redoing.
