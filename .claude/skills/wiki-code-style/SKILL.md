---
name: wiki-code-style
description: Use whenever writing or editing PHP code in this wiki project — new classes, methods, or refactors. Enforces the project's styleguide.md conventions and the actual patterns already in the codebase, since the two don't always agree.
---

# PHP code style for this project

Full written guide: `src/styleguide.md`. This skill adds the parts that guide
alone doesn't tell you, based on what the existing code actually does.

## Core conventions (from styleguide.md)

- Allman brace style: opening `{` on its own line.
- Tabs are 4 spaces.
- `camelCase` for variables/functions, `PascalCase` for classes.
- Interfaces are prefixed with a lowercase `i`: `iArticleRepository`, `iView`.
- Class member order: properties → constructor → public methods → protected
  methods → private methods.
- SQL: always prepared statements, capitalized keywords, meaningful table
  aliases (`a` for `articles`).

## Where the real codebase diverges from styleguide.md

- **DB columns are snake_case**, not camelCase — `user_id`, `image_path`,
  `last_edit` (see `ArticleRepository.php`). styleguide.md's "use camelCase
  for columns" section is aspirational; match the real schema, don't
  "correct" existing column names.
- **Method doc comments are terse `//` lines, not PHPDoc blocks.** The
  dominant pattern (see `Db.php`, `ArticleRepository.php`) is:
  ```php
  // Short description of what the method does
  // Input: $id; What it is
  // Output: What gets returned
  public function getArticleById(int $id): Article
  ```
  Follow this style for Repository/Model methods even though styleguide.md
  shows a full `/** @param @return */` block — match the file you're in.
- **Separator banners** (`// === ... ===`) are used inconsistently. Add them
  only if the file you're editing already uses them; don't introduce them
  into files that don't.

## Error handling

- Methods that can fail return a union with `false`/`bool`
  (`array|bool`, `string|false`, `int|bool`) and return `false` on
  not-found — see `PageDataRepository.php`.
- Reserve `throw new \Exception(...)` for programmer errors and unreachable
  states (unknown factory `type`, failed DB connection in
  `BaseRepository`) — not for expected "no rows" lookups.
