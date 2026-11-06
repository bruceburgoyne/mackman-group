# mackman-group

The rebuild of [mackmangroup.co.uk](https://mackmangroup.co.uk) as a classic WordPress theme driven by Advanced Custom Fields (ACF Pro), replacing the current block theme.

It follows the method proven in [wordpress-test](https://github.com/bruceburgoyne/wordpress-test):

- Every content area on every page is an ACF field. Pages have no block editor, so the design stays in the theme and stays consistent.
- The blog (News) is the one exception. Articles are written in a cut-down block editor with writing tools only.
- Field groups, templates and content live in this repo, and Claude Code builds and changes them.

## Status

Early build. Work happens in the Local site **MACKMANG - Mackman Group** (`mackmang-mackman-group.test`) and is not deployed.

| Done | Next |
|---|---|
| Design system and style guide | Theme scaffold, ACF field groups and page templates |

## Structure

```
docs/
  design-system.md        colour, type, components and the reasoning behind them
  style-guide.html        every token and component, rendered with the theme's CSS
theme/mackman-group-acf/
  assets/css/tokens.css   design tokens (the single source for colour, type, space)
  assets/css/main.css     base styles and shared components
  assets/images/          logos
```

## Viewing the style guide

The style guide links to the theme's stylesheets, so serve the repo rather than opening the file directly:

```bash
python3 -m http.server 8765 --bind 127.0.0.1
```

Then open http://localhost:8765/docs/style-guide.html.

## What never goes in this repo

The repo is public. Never commit the database, `wp-config.php`, the uploads folder, plugin zips, licence keys, or paid plugins (ACF Pro, Rank Math Pro, WP Rocket). The Local site is a copy of production and includes contact form submissions, which are personal data.
