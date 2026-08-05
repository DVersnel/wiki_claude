---
name: wiki-manual-tests
description: Use when writing or running tests for this project. There is no PHPUnit — tests are plain executable PHP scripts under src/test that hit a real database. Covers how to run them and the existing conventions.
---

# Manual test scripts

There is no test framework installed — no `phpunit.xml`, no PHPUnit
dependency in `composer.json`. `src/test/*.php` are standalone scripts
that require the autoloader/config, instantiate real Repositories, and
`var_dump()` the results (see `src/test/dbtest.php`).

## Running them

These scripts hit the live database configured in `src/src/config.php` —
the `db` service must be up and seeded first, see
[[docker-dev-environment]]. Run with the PHP CLI:
```
php src/test/dbtest.php
```
`src/test/index.php` also appears to expose these under the web server.

## Writing a new one

Follow the shape of `dbtest.php`:
```php
namespace MDJ\test;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/ManKind/tools/pdo/QueryParams.php';
require_once __DIR__ . '/../vendor/ManKind/tools/pdo/Crud.php';

echo "=== Testing SomeRepository ===\n\n";
$repo = new SomeRepository();
echo "Test someMethod():\n";
var_dump($repo->someMethod());
echo "\n";
```
Group related checks under an `echo "=== Testing X ===\n\n";` banner, one
`echo "Test method():\n"; var_dump($result);` block per call.

## Caution

These scripts perform real writes (`createRating`, `updateRating`, etc.)
against whatever database `config.php` points at. Prefer running them
against the docker-compose `db` service, not a database you care about.
