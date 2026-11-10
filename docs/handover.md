# Handover: how the Mackman Group website is built

Written on 10 November 2026. This document is for a WordPress developer, or anyone at Mackman Group, who needs to understand, run or change the site **without any AI tools**. It assumes general WordPress knowledge and nothing else.

The site was built with the help of Claude Code (an AI coding assistant). Nothing in the site depends on it: everything is ordinary WordPress code, kept in this repository. If the AI tools were unavailable, a developer could carry on from here.

## 1. The site in brief

- **What it is:** a rebuild of [mackmangroup.co.uk](https://mackmangroup.co.uk) as a classic WordPress theme called **Mackman Group ACF** (`theme/mackman-group-acf`).
- **How content is edited:** every content area on a page is a field from **Advanced Custom Fields Pro** (ACF Pro), a paid plugin. Pages have no block editor and no page builder, so the design stays in the theme. News articles (blog posts) are the exception: they use the normal WordPress block editor, cut down to writing tools only.
- **What it is made of:** about 3,800 lines of PHP, 3,600 lines of CSS and 300 lines of JavaScript. No framework, no build step, no npm packages. You can edit any file and reload the page.
- **Status:** built and checked on a local copy of the site. It is **not yet live**. The live site still runs the old theme (`mackman-group`).

## 2. Where everything lives

| What | Where | Notes |
|---|---|---|
| Theme code, field definitions, content files, scripts, documentation | This repository on GitHub: `github.com/bruceburgoyne/mackman-group` | Public repository. Full history of every change in git. |
| Working copy of the site | **Local** (by WP Engine) on Bruce Burgoyne's Mac, site name *MACKMANG - Mackman Group*, at `https://mackmang-mackman-group.test` | A copy of the production database. The theme folder is symlinked into its `wp-content/themes`. |
| Live site | WP Engine (production environment) | Local is linked to it for pulling and pushing. |
| Pages, posts, media, menus, settings | The WordPress database and uploads folder | Not in the repository. See section 5 for what that means. |
| ACF Pro, Rank Math Pro | Licensed plugins | Licences held by Mackman Group. Never committed to the repository. |
| Database backups | `~/Local Sites/mackmang-mackman-group/backups/` on the same Mac, plus WP Engine's own backups | Kept out of the repository because the database holds personal data. |

**Never commit** the database, `wp-config.php`, the uploads folder, plugin zips, licence keys or paid plugins. The repository is public, and the database includes contact form submissions (personal data).

## 3. How a page is put together

Everything follows the standard WordPress template hierarchy. A request is handled like this:

1. WordPress picks the template file in the usual way: `front-page.php` for Home, `home.php` for the News page, `single.php` for an article, `single-case-study.php` for a case study, `single-team_members.php` for a team profile, or the page template chosen on the page.
2. Most pages use one of two **page templates** in `page-templates/`:
   - `division.php` for the four brand pages (Mackman Research, Mackman, Red Kite HR Services, Training and Coaching).
   - `content.php` for everything else (About, Trust, Values, B Corp, Accreditations, Contact, Newsletter and so on). It shows a green introduction, then a flexible list of sections chosen in WP Admin.
3. The template calls **sections** from `template-parts/sections/` (hero, intro, features, quote, text and image, team, form, call to action, closing and so on). Each section is a small PHP file that reads its ACF fields and prints HTML.
4. Fields are read through small helper functions in `inc/helpers.php`. They fail quietly when a field is empty, so a missing value hides that element rather than breaking the page.
5. Styles come from `assets/css/tokens.css` (colours, type, spacing as CSS variables) and `assets/css/main.css` (all components). Articles also load `assets/css/article.css`.

To find what builds a particular part of a page, open the template for that page and follow the `get_template_part()` calls.

## 4. The repository, folder by folder

```
CLAUDE.md                   working notes for the AI assistant; also a useful summary of status and open items
README.md                   short introduction (the status table there is out of date; trust this document)
content/                    the content that was moved from the old site, as JSON (see section 6)
docs/design-system.md       colours, typography, components and the reasons behind them
docs/style-guide.html       every colour and component, rendered with the theme's own CSS
docs/handover.md            this document
scripts/seed.php            pushes content/ into WordPress (see section 6)
scripts/check.sh            health check: PHP syntax, JSON, loads key pages, reports new PHP errors
scripts/preview.sh          fallback for viewing pages if Local's certificate stops working
theme/mackman-group-acf/
  functions.php             loads the files in inc/, registers menus, enqueues CSS and JavaScript
  inc/acf.php               tells ACF to keep field groups in acf-json/; adds the Site Settings page
  inc/editor.php            removes the editor from pages; cuts down the block editor for posts
  inc/nav.php               accessible drop-down menus (custom menu walker)
  inc/news.php              News archive paging and helpers
  inc/case-studies.php      helpers for the case study template
  inc/team.php              helpers for team profiles
  inc/forms.php             Contact Form 7 and Cloudflare Turnstile loading
  inc/tracking.php          Google Tag Manager
  inc/helpers.php           field and image helpers used by every template
  acf-json/                 ACF field groups (group_mg_*), post types (post_type_*) and taxonomies (taxonomy_*)
  page-templates/           Division and Content page templates
  template-parts/           sections, case study parts, news parts, forms
  assets/css, assets/js     styles and three small scripts (menu, drop-downs, film dialog)
  theme.json                switches off design controls in the block editor
```

**ACF field groups, post types and taxonomies** live in `acf-json/` as ACF's standard "local JSON" files. ACF reads them from there, and any change made in WP Admin (Custom Fields) is written back to the same files, so it can be committed. If the files and the database ever disagree, ACF shows a "Sync available" notice under Custom Fields.

## 5. What is held only in the database

This is the most important section for recovery. These things are **not** in the repository. If the database were lost and only the repository survived, they would have to be recreated by hand.

| Item | Where to find it in WP Admin | Risk if lost |
|---|---|---|
| Menus: *Main navigation* (primary), *Our brands* (brands), *Footer links* (footer) | Appearance > Menus | Medium: rebuild by hand |
| Reading settings: Home as front page, News as posts page | Settings > Reading | Low |
| Site Settings values (logos, contact details, side panel, footer) | Site Settings | Low: mirrored in `content/site-settings.json` |
| All pages, posts, case studies, team members and their field values | Pages, Posts and so on | Covered by database backups |
| The media library | Media | Covered by uploads backups |

Two old field groups from the previous site also sit only in the database: *Team Members* (job title and division, both empty) and *Page Enhancements*. Neither is used by the new theme. Two unused menus (*Header Menu*, *Primary Navigation*) are also left over.

**Post types and taxonomies are in the repository.** The Case Studies (`case-study`) and Team Members (`team_members`) post types, and the Sectors (`sector`, on case studies and posts) and Locations (`location`, on case studies) taxonomies, were created in WP Admin under ACF > Post Types and ACF > Taxonomies. Since 10 November 2026 their definitions are also saved as local JSON in `acf-json/` (the `post_type_*.json` and `taxonomy_*.json` files), so a fresh install with ACF Pro and this theme registers them from the files. Edits made in WP Admin are written back to the same files, like field groups. The file names are ACF's own keys: keep them, because ACF looks for a file by that name when it saves.

## 6. How content was moved, and what happens at go-live

The wording from the old site was extracted into JSON files in `content/` (pages, case studies, team members, Site Settings). `scripts/seed.php` reads those files and writes the values into the matching ACF fields.

- It runs with WP-CLI: `wp eval-file /path/to/repo/scripts/seed.php` (all content), or with a file name to push one page.
- It never creates posts and never touches a post's original block content. The page must already exist at the path given in the file.
- Images are given as **attachment IDs** from the media library. The files only work against this site's media library, not a fresh install.
- `{{page:path}}` in text becomes that page's link, and `{{file:ID}}` the URL of a media file.

**At go-live, editing moves to WP Admin and the JSON files stop being the source of truth.** Running the seed script after people have edited a page in WP Admin would overwrite their changes. Once the site is live, treat `content/` as a historical record, or delete it.

## 7. Plugins and outside services

| Plugin or service | Used by the new theme? | Notes |
|---|---|---|
| ACF Pro | **Required** | Paid licence. The theme warns in WP Admin if it is missing. |
| Contact Form 7, with Flamingo | Yes | Forms on Contact and elsewhere. Flamingo stores submissions in the database (personal data). |
| Cloudflare Turnstile | Yes | Spam protection on forms, loaded only when someone starts filling in a form. |
| Google Tag Manager | Yes | Container loaded through Cloudflare's tag gateway. See `inc/tracking.php`. |
| Mailchimp | Yes | The newsletter form posts straight to Mailchimp. The *Mailchimp for WP* plugin is active but the theme does not use it. |
| Google Fonts | Yes | EB Garamond and Lato. Should be self-hosted before go-live (visitor IP addresses are sent to Google). |
| YouTube | Yes | Films on the home page and case studies, opened in a dialog. |
| Rank Math SEO and Rank Math Pro | Not referenced by the theme | Works independently of the theme (titles, meta, sitemaps). |
| WP Mail SMTP, Imagify | Not referenced by the theme | Email sending and image compression. |
| StoryChief, ACF Views, Video Lightbox Block | Not referenced by the theme | Carried over from the old site. Review whether they are still needed before go-live. |

Hosting is WP Engine. Code is on GitHub. All of these are mainstream and replaceable.

## 8. Running the site locally, by hand

1. Install [Local](https://localwp.com), connect it to the WP Engine account, and pull the production site.
2. Clone this repository and symlink (or copy) `theme/mackman-group-acf` into the site's `wp-content/themes/`.
3. Activate **Mackman Group ACF** under Appearance > Themes. Make sure ACF Pro is active.
4. Open the site. If Custom Fields shows "Sync available", sync the field groups from the JSON files.
5. Run `scripts/check.sh` to confirm PHP syntax, JSON, key pages and the error log are all clean. It expects the Local site at the paths in the script; adjust them for another machine.

PHP 8.1 or later and WordPress 6.5 or later are required.

## 9. Deploying

**A deployment method has not been decided yet.** Nothing from this rebuild has gone to the live site. Before go-live we need to agree:

- how the theme reaches WP Engine (Local's push, WP Engine's git push, or SFTP of the theme folder);
- how the database changes reach production: the page template and field values on each page, menus and Site Settings. Pushing the whole Local database would overwrite anything that changed on the live site since the copy was taken, including form submissions;
- a rollback plan (WP Engine backup point taken immediately before).

This section should be filled in once that is agreed.

## 10. Common changes without AI

- **Change wording or images on a page:** edit the page in WP Admin. The fields are grouped as they appear on the page.
- **Change a colour or font size site-wide:** edit the variable in `assets/css/tokens.css`. Read `docs/design-system.md` first (for example, gold is never used as text on light backgrounds, for contrast).
- **Change how a section looks:** edit its file in `template-parts/sections/` and its styles in `main.css` (search for the section's class name).
- **Add a field:** add it in WP Admin under Custom Fields, then commit the changed file in `acf-json/`. Then read it in the template with the helpers in `inc/helpers.php`.
- **Add a page template:** create `page-templates/<name>.php` with a `Template Name:` header, create a field group with the location rule *Page Template is <name>*, and exclude that template from the *Default page* field group's location rules.
- **Add a menu item:** Appearance > Menus. Drop-downs are built automatically from child items.

After any code change, run `scripts/check.sh` and look at the affected pages on desktop and phone widths.

## 11. Rebuilding from nothing

If both the live site and the Local copy were lost but the repository and a database or uploads backup survived, restore the backup and follow section 8.

If only the repository survived, a rebuild is possible but slower:

1. Install WordPress, ACF Pro and the plugins in section 7.
2. Install and activate the theme.
3. Check under ACF > Post Types and ACF > Taxonomies that Case Studies, Team Members, Sectors and Locations have loaded from `acf-json/`, along with the field groups. Visit Settings > Permalinks once so their addresses work.
4. Recreate the pages at the paths given in each `content/` file, upload the images again, and update the attachment IDs in the JSON files to match the new media library.
5. Run the seed script, rebuild the menus, and set the Reading settings.

News articles and their images would need restoring from a backup or the old site; they are not in the repository.

## 12. Known gaps

The current list of open items (missing alt text, unapproved testimonials, case studies still to move, clean-up of old posts, self-hosting fonts) is kept under *Status and open items* in `CLAUDE.md`. The gaps that matter for long-term maintenance are:

- menus and Reading settings held only in the database (section 5);
- no agreed deployment method (section 9);
- no one at Mackman Group has yet walked through the code without AI help. We recommend a short walkthrough of each template, and an occasional review by an independent WordPress developer, to confirm someone could pick the site up from this document alone.

## 13. How AI was used

Code, field groups and content files were written by Claude Code (Anthropic) working in this repository, directed and reviewed by Bruce Burgoyne. Every change is a normal git commit with a plain-English message, so the history shows what changed and why. The AI's working instructions are in `CLAUDE.md`, which is plain Markdown that any person or other tool can read. This use should be recorded under Mackman Group's AI governance framework (ISO 42001).
