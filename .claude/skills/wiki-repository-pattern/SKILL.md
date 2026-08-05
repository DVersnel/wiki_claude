---
name: wiki-repository-pattern
description: Use when adding or modifying database access — new Repository methods, new Repository classes, or queries against the wiki MySQL schema. Covers BaseRepository, the two coexisting query APIs, and row-to-object mapping.
---

# Repository pattern

Repositories live in `src/Classes/Model/Repositories`. Each extends
`BaseRepository` and implements a matching `iXxxRepository` interface from
`src/Classes/Interfaces`.

## Connection

`BaseRepository` sets `$this->db = ManKind\tools\pdo\Crud::getInstance()`
(a vendor class, not part of this codebase) and throws if not connected.
Never re-implement connection handling in a Repository — just use
`$this->db`.

## Two query styles both work — match whichever the class already uses

- **Positional** (see `ArticleRepository.php`):
  `$this->db->fetch($sql, [$id])`, `fetchAll(...)`, `execute(...)`,
  `lastInsertId()`. Placeholders are `?`.
- **Named** (see `PageDataRepository.php`):
  ```php
  $params = (new QueryParams())->add('page', $page);
  $result = $this->db->select($sql, $params);   // or selectOne(...)
  ```
  Placeholders are `:page`. Requires
  `use ManKind\tools\pdo\QueryParams;`.

Don't mix both styles within one class. If a Repository file doesn't exist
yet, prefer the named `QueryParams` style — it's the newer of the two.

## Conventions

- DB columns are **snake_case** (`user_id`, `image_path`, `last_edit`) —
  match them exactly, see [[wiki-code-style]].
- IN-clause lists: build placeholders with
  `implode(',', array_fill(0, count($ids), '?'))` (see
  `getArticlesByUserIdAndTagId`).
- Keep a private `mapToXxx(array $row): Xxx` helper at the bottom of the
  class for row→object mapping (see `ArticleRepository::mapToArticle`)
  rather than mapping inline at each call site.
- Return `false`/falsy on not-found instead of throwing — see error
  handling in [[wiki-code-style]].
- Object classes (`Article`, `User`, `Tag`, `Rating`) live in
  `src/Classes/Model/Objects` as plain property bags.

## Runtime DB config

Actual connection settings used at runtime are in `src/src/config.php`
(`Config::PDOHOST`, `PDOUSER`, `PDOPASS`, `PDODATABASE`) — **not**
`Classes/Model/Db.php`, which hardcodes different, unused credentials.
See [[docker-dev-environment]] for a note on why `config.php`'s
`PDOHOST = 'localhost'` doesn't match the docker-compose `db` service.
