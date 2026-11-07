# Mackman Group website rebuild

Rebuild of mackmangroup.co.uk as a classic WordPress theme driven by ACF Pro, replacing the old block theme. Same method as the wordpress-test proof of concept (`~/Documents/GitHub/wordpress-test`): every content area on a page is an ACF field; only blog posts (News) use a cut-down block editor.

## House rules

- UK English. No em dashes. "We", not "I". Plain, professional language.
- The repo is **public**. Never commit the database, `wp-config.php`, uploads, plugin zips, licence keys, or paid plugins. The Local site is a copy of production and holds personal data (Flamingo form submissions): never export or quote it.
- Flag marketing claims that cannot be evidenced (CIM, CIPR, MRS codes) and testimonials that need approval. Do not invent facts, figures or quotes; mark new copy as placeholder.
- New AI tools need checking against the Mackman AI Tool Register before use.
- Commit or push only when asked. Work on a feature branch; when asked to "push and update main", push the branch and fast-forward `main` to it (no merge commits).

## Environment

| Thing | Value |
|---|---|
| Local site | **MACKMANG - Mackman Group**, id `pT1uusCH_` |
| Site folder | `~/Local Sites/mackmang-mackman-group/app/public` |
| URL | https://mackmang-mackman-group.test (Local's certificate is trusted, so open it directly in the browser pane) |
| Theme | `theme/mackman-group-acf`, symlinked into the site's `wp-content/themes` |
| Shell setup | `source ~/"Local Sites/mackmang-mackman-group/app/.envrc"` puts Local's PHP 8.5, MySQL and WP-CLI on the PATH. Run `wp` from `app/public` |
| PHP error log | `~/Local Sites/mackmang-mackman-group/logs/php/error.log` |
| Database backups | `~/Local Sites/mackmang-mackman-group/backups/` (outside the repo) |
| Node | Broken on this machine (missing ICU library). Test JavaScript in the browser instead |

ACF Pro is active (the free ACF plugin is installed but inactive). The live site is on WP Engine (Local is linked to the production environment).

## Repo layout

```
CLAUDE.md
content/pages/*.json        page content: path, template, ACF fields (images are attachment IDs)
content/site-settings.json  Site Settings values (contact, logos, side panel, footer)
docs/design-system.md       colour, type, components, rules (read before design work)
docs/style-guide.html       every token and component, rendered with the theme CSS
scripts/seed.php            pushes content/ into WordPress (never creates pages, never touches post_content)
scripts/check.sh            lint, JSON, render key pages, report new PHP errors
scripts/preview.sh          fallback only: snapshot pages to localhost:8766 if the certificate stops being trusted
theme/mackman-group-acf/
  acf-json/                 field groups (ACF local JSON, synced both ways)
  assets/css/tokens.css     design tokens
  assets/css/main.css       base styles and all components
  assets/css/article.css    article content (site and block editor)
  assets/js/                menu.js (mobile panel), nav.js (drop-downs), video.js (hero video dialog)
  inc/                      acf, editor, forms, helpers, nav (walker), news, tracking (GTM)
  page-templates/division.php
  front-page.php, home.php (News), archive.php, single.php, page.php, index.php, 404.php
  template-parts/sections/  intro, hero, features, quote, closing, text-split, standards, news, cta
  template-parts/news/      card, lead, topics, related
```

## Common tasks

**Seed content** (from `app/public`, after sourcing `.envrc`):
```bash
wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php                 # all pages and Site Settings
wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php mackman-research
wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php site-settings
```

**Add a page template**
1. `page-templates/<name>.php` (with `Template Name:`), built from `template-parts/sections/`.
2. `acf-json/group_mg_<name>.json` with location `page_template == page-templates/<name>.php`.
3. Add `page_template != page-templates/<name>.php` to `group_mg_default.json`'s location.
4. `content/pages/<slug>.json` with the original content (extract it from the page's blocks with `wp eval` and `parse_blocks`), then seed.

**Check work**: `scripts/check.sh` after every change. For visual checks, open the page on https://mackmang-mackman-group.test in the browser pane. For small changes, a desktop check is enough; keep phone checks for layout work. Screenshots are expensive: prefer measuring with JavaScript.

**Menus and Site Settings live in the database**, not the repo. Menus: Main navigation (primary), Our brands (brands), Footer links (footer). Site Settings values are mirrored in `content/site-settings.json`.

## Design rules (summary; full detail in docs/design-system.md)

- Brand colours: green `#1a382e`, gold `#bca35d`, base `#f4f4f4`, white, mint `#e4eeed`. Gold is never text on light backgrounds: use gold deep `#826e36`.
- Type: EB Garamond headings, Lato body. Square corners. Motion on hover and focus only.
- Components read `--surface-*` roles; add `.surface-green`, `.surface-white` or `.surface-mint` to a section.
- Cards use the **hairline grid** (`.card-grid > .grid`), with 88px icons.
- Layout: side panel rail from 64rem (88px wide), top bar below. Full menu from 85rem, burger below. Articles use a 42rem reading column.
- Accessibility target WCAG 2.2 AA: visible focus, 44px+ targets, one H1 per page, disclosure-pattern navigation.

## Status and open items

Done: design system, theme scaffold, Division template (Mackman Research seeded), Home, drop-down navigation, footer and logos, side panel, card grid, News archive and articles.

Open:
- Division template for Mackman, Red Kite and Training and Coaching; About, Values, Accreditations, Contact, case studies, team members.
- Copy review: unevidenced claims and testimonial approvals flagged in earlier work.
- Clean-up of the 56 posts (old spacers, columns, inline styles are only hidden by CSS).
- Remove LearnDash and Oxygen data (back up the database first).
- Self-host fonts (Google Fonts sends visitor IPs to Google).
- Homepage concept features (panels over an image, image call to action, hero emblem) mocked up but not built; waiting on the user's direction.
