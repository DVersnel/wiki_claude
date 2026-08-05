---
name: wiki-add-page
description: Use when adding a brand-new page/route to the wiki (a new value handled by PageController's page switch). Walks through every layer that must change together, since they're not co-located in one file.
---

# Adding a new page

Pages aren't separate route files — they're a `page` GET/POST value
(`?page=xxx`) handled entirely inside
`src/Classes/Controller/PageController.php::handleRequest()`, which runs
`validateRequest()` → `collectPageData()` → `showResponse()` in sequence.

## Checklist (model after the `about` page)

1. **`validateRequest()`** — add `case 'yourpage':` to the non-POST switch,
   setting `$this->response['page'] = 'yourpage'`. Add a matching case in
   the POST switch too if the page has a form (see the `contact`/`login`/
   `register` cases for the pattern — construct a manager, act, then set
   `page`/`error_message`/`success_message`).
2. **`collectPageData()`** — add `'yourpage'` to whichever switch arm
   fetches the right title/description/content: the
   `contact|login|register|edit` form arm (uses
   `PageDataRepository::getFormContent`), the default article-listing arm,
   or a new arm you add.
3. **`showResponse()`** — add `'yourpage'` to the case list that calls
   `$element_factory->createItem($this->response)` (currently
   `home|about|contact|login|register|edit`). Anything not in that list
   falls through to `default: echo 'balen man'; return;`.
4. **Seed the database** tables `PageDataRepository` reads from:
   - `_page_title_description` — a row keyed by `page` with
     `page_title`/`page_description`.
   - `_nav_items` or `_hamburger_items` — if the page should appear in
     navigation.
5. **Custom content** beyond a plain article list means extending
   `ElementFactory::createItem()`'s switch and/or `ContentFactory` — see
   [[wiki-factory-pattern]].
6. Check whether `Validator` (`Controller/Validator.php`, constructed with
   the page name in `validateRequest()`) needs a rule for the new page.
7. Any page content that is not the header, nav menu, title, description or footer should be in a contentbox. If the page is an article, this is in articlecontentbox instead.

All four layers (routing, data collection, rendering, DB seed data) must
change together — missing one usually manifests as either `balen man` on
screen or a blank title/description.
