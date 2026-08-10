---
name: wiki-repository-pattern
description: Use when adding or modifying database access — new Repository methods, new Repository classes, or queries against the wiki MySQL schema. Covers BaseRepository, the Crud/QueryParams query API, and row-to-object mapping.
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

## Only one query API actually works — use it

`Crud` (the vendor class behind `$this->db`) only implements: `select($sql, ?QueryParams)`,
`selectOne($sql, ?QueryParams)`, `selectAsPairs($sql)`, `doInsert($sql, QueryParams)`,
`doUpdate($sql, QueryParams)`, `doDelete($sql, QueryParams)`. Placeholders are always named
(`:page`), built with `QueryParams`:
```php
use ManKind\tools\pdo\QueryParams;

$params = (new QueryParams())->add('page', $page, false); // 3rd arg: is it an int?
$result = $this->db->select($sql, $params);   // or selectOne(...)
```
See `PageDataRepository.php`, `UserRepository.php`, `RatingRepository.php`,
`TagRepository.php`, or the current `ArticleRepository.php` for the pattern.

**There is no positional (`?` placeholder) style** — `Crud` has no `fetch`,
`fetchAll`, `execute`, or `lastInsertId` methods. Those only exist on the
legacy, unused `Classes/Model/Db.php` wrapper class, which `BaseRepository`
does **not** use. If you see a Repository calling `$this->db->fetch(...)` /
`fetchAll(...)` / `execute(...)`, or a typo'd method like `selectMore(...)`,
it's a bug — it'll throw `Call to undefined method` at runtime — not an
alternate valid style. Convert it to `select`/`selectOne`/`doInsert`/
`doUpdate`/`doDelete` + `QueryParams`.

## Conventions

- DB columns are **snake_case** (`user_id`, `last_edit`, `article_id`) —
  match them exactly, see [[wiki-code-style]]. Don't assume a column exists
  because an old version of the code referenced it — e.g. `articles` has no
  `image_path`/`image_description`; images are a separate, currently-unwired
  `images` table with its own `article_id` FK.
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
`Classes/Model/Db.php`, which is a separate, unused legacy wrapper with its
own hardcoded (and irrelevant) credentials. See [[docker-dev-environment]]
for the current values and a note on the `wiki` vs `mydb` database-name gap.
