---
name: wiki-view-component
description: Use when creating or modifying a View class — page elements, form fields, list items, nav items, info panel items. Covers the iView contract and the open/render/close template pattern used throughout Classes/View.
---

# View components

Views live under `src/Classes/View` and its subfolders (`Forms/`,
`Forms/FormFields/`, `ListItems/`, `NavItems/`, `InfopanelItems/`,
`Titles/`).

## Contract

Every renderable class implements `MDJ\Interfaces\iView`:
```php
interface iView { public function show(); }
```
`show()` echoes HTML directly — it has no return value and Views never
return markup as a string.

## Shape (template method pattern)

See `View/Title.php` as the canonical example: constructor sets
protected/private state, then `show()` calls a sequence of small protected
`open../show../close..` helpers, each echoing one fragment:
```php
protected function openTitle()  { echo '<div>'; }
protected function showTitle()  { /* subclass overrides this */ }
protected function closeTitle() { echo '</div>'; }
public function show() { $this->openTitle(); $this->showTitle(); $this->closeTitle(); }
```
Keep each helper to one concern so a subclass can override just one piece.

## Composition and subtyping

- Composite views (`Header`, `Footer`, `ContentBox`, `ViewList`,
  `Infopanel`) take arrays of already-built `iView` child objects in their
  constructor and call `->show()` on each child from their own `show()`.
  A View never fetches its own children — that's the Factory's job, see
  [[wiki-factory-pattern]].
- Subtype through inheritance, not a type flag: `Titles/ArticleTitle.php`
  and `Titles/PageTitle.php` both `extend View/Title.php` rather than
  `Title` taking a `$type` constructor param.

## Boundary

Views never touch the database or Repositories directly. If a component
needs data, it must already be passed into its constructor.
