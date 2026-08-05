---
name: wiki-factory-pattern
description: Use when wiring a new content/element type into the page-building pipeline, or creating a new Factory. Covers the iFactory contract, the Collection helper, and how PageController's page_info array turns into rendered View trees.
---

# Factory pattern

Factories live in `src/Classes/Factories` and implement
`MDJ\Interfaces\iFactory`:
```php
interface iFactory { public function createItem(array $data): iView; }
```
Each is a pure dispatcher: a `switch` on a `type` (or `page`) discriminator
key that `new`s the matching View class, throwing `\Exception` in the
`default` case for unknown types (see `ContentFactory.php`).

## Collection

`Factories/Collection.php` turns an array of raw item-info arrays plus a
Factory into an array of built `iView` objects:
```php
$items = (new Collection($raw_items, $someFactory))->getItems();
```
Use it instead of hand-rolling a `foreach` + `array_map`.

## Nesting

A factory can recurse into another factory for nested structures. In
`ContentFactory`, the `list` case builds a nested `Collection` of list
items using itself; `infopanel` and `form` cases delegate to
`InfopanelFactory`/`FormFieldFactory` respectively.

## Entry point

`ElementFactory.php` is the top-level factory: it takes the full
`page_info` response array assembled by `PageController`, always emits a
`Header` and `Footer`, then switches on `page_info['page']` to decide
which content Factory/Views to add.

## Adding a new content type

1. Add a `case` to the relevant Factory's `switch`.
2. Add a matching View class implementing `iView` — see
   [[wiki-view-component]].
3. Make sure whatever builds the info array (usually a Repository) sets
   the matching `type` key the new `case` expects.
