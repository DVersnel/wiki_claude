---
name: docker-dev-environment
description: Use when starting, stopping, or debugging the local dev stack for this project — running the site, resetting the database, or figuring out why PHP/MySQL isn't reachable. Covers the docker-compose services, ports, volumes, and a known credential mismatch.
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

## Known credential mismatch — worth knowing before debugging a DB error

Three different sets of DB credentials exist in this codebase and none of
them agree:

- `docker-compose.yml`: `db` service — user `dbuser`/`dbpass`, database
  `mydb`.
- `src/src/config.php` (`Config` class, actually used at runtime by
  `BaseRepository`/`Crud`): `PDOHOST = 'localhost'`, `PDOUSER = 'root'`,
  `PDOPASS = 'educom'`, `PDODATABASE = 'wiki'`.
- `src/Classes/Model/Db.php` (a separate, apparently-unused PDO wrapper):
  hardcodes `dbname=wiki`, user `root`, password `Password!`.

`config.php`'s `PDOHOST = 'localhost'` in particular won't resolve to the
`db` container from inside `web` — container-to-container traffic needs
the service name `db`, not `localhost`. If DB connections fail inside
Docker, check `config.php` first (see [[wiki-repository-pattern]]) rather
than assuming the code is broken.
