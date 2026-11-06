# Design system

Drafted on 6 November 2026 for the new Mackman Group ACF theme. It keeps the current brand colours and builds the rest from the [ui-ux-pro-max](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) catalogue (MIT licence).

All values live as CSS custom properties in `theme/mackman-group-acf/assets/css/tokens.css`. Shared components are in `assets/css/main.css`. Open `docs/style-guide.html` to see everything rendered with the theme's own stylesheets.

## Where it comes from

We ran the catalogue's design-system search for a B2B professional services consultancy, with the dials set to variance 4 (balanced), motion 2 (subtle) and density 3 (spacious).

| Catalogue result | What we took | What we set aside |
|---|---|---|
| **Pattern:** Trust & Authority + Conversion | The page order: credibility first, then proof (credentials, figures), then what we offer, then a clear call to action | Logo carousels: credentials will be a static row |
| **Style:** Accessible & Ethical | High contrast, 16px minimum text, visible focus, keyboard support, 44px+ tap targets, reduced motion | |
| **Typography:** "Legal Professional" pairing | EB Garamond headings, Lato body | |
| **Colours:** navy and blue | | Replaced entirely by the brand colours below |
| **Motion:** subtle scroll reveal | Hover and focus changes only | Scroll reveals, following the wordpress-test decision to keep page content still |

Two other searches ("management consultancy premium", "marketing agency brand") returned Liquid Glass and Brutalism. Neither suits a consultancy that sells trust, so we did not use them.

## Colour

The five brand colours are unchanged. Three shades are added, all derived from the brand colours.

| Token | Value | Role |
|---|---|---|
| `--mg-green` | `#1a382e` | Text, headings, dark sections, main button |
| `--mg-gold` | `#bca35d` | Accent: rules, underlines, quote marks; text and buttons on green only |
| `--mg-base` | `#f4f4f4` | Page background |
| `--mg-white` | `#ffffff` | Cards, form fields, alternate sections |
| `--mg-mint` | `#e4eeed` | Soft sections, image placeholders |
| `--mg-gold-deep` | `#826e36` | **Added.** Small gold text (kickers) on base or white |
| `--mg-green-soft` | `#465e56` | **Added.** Secondary text on light |
| `--mg-mint-soft` | `#bccac7` | **Added.** Secondary text on green |

Supporting values: `--mg-green-hover`, `--mg-gold-hover`, `--mg-line`, `--mg-line-on-dark`, `--mg-border-input` and `--mg-error`. See `tokens.css`.

### Contrast

Checked against WCAG 2.2 AA: 4.5:1 for normal text, 3:1 for large text and interface parts.

| Pair | Ratio | Result |
|---|---|---|
| Green on base | 11.6:1 | Pass |
| White on green | 12.7:1 | Pass |
| Gold on green | 5.2:1 | Pass |
| Green on gold | 5.2:1 | Pass |
| Gold deep on base / white | 4.5:1 / 5.0:1 | Pass |
| Gold deep on mint | 4.2:1 | Fail: kickers on mint use green instead |
| Green soft on base / mint | 6.4:1 / 5.9:1 | Pass |
| Mint soft on green | 7.5:1 | Pass |
| Field border on base | 3.6:1 | Pass (interface) |
| Error on base | 6.0:1 | Pass |
| **Gold on base** | **2.2:1** | **Fail: never used for text** |
| **Base on gold** | **2.2:1** | **Fail: the current site's button, replaced** |

### Rules

1. Gold is never text on a light background. On light, small gold text uses gold deep.
2. On green, gold is fine for kickers, figures, rules and the gold button.
3. Buttons: green with white text on light sections; gold with green text on green sections.
4. Components use surface roles (`--surface-text`, `--surface-kicker` and so on), not raw colours. Adding `.surface-white`, `.surface-mint` or `.surface-green` to a section restyles everything inside it.

## Type

- **Headings:** EB Garamond 500 (H4 at 700), sitting well with the serif in the logo.
- **Body and labels:** Lato 400 and 700, kept from the current site. The current theme sets body text at Lato Light (300); we use Regular (400) for readability.
- **Kickers:** Lato 700, capitals, 14px, wide letter spacing, as on the current site.

| Token | Size (phone to desktop) | Use |
|---|---|---|
| `--step-5` | 40 to 68px | Hero headline |
| `--step-4` | 36 to 56px | H1, call to action heading, figures |
| `--step-3` | 28 to 40px | H2 |
| `--step-2` | 22 to 28px | H3, card titles, quotes |
| `--step-1` | 18 to 22px | H4, lead paragraphs |
| `--step-0` | 16 to 18px | Body |
| `--step--1` | 14px | Kickers, hints, captions |

Body line height is 1.65, headings 1.15. Running text is limited to 65 characters a line. Headings use balanced line breaks.

One page, one H1. The current home page uses the small "Full service business consultancy" label as its H1 and the large headline as an H2. The new templates will make the headline the H1.

## Space and layout

- **Container:** 1280px content width (as now), with side gutters from 16px to 48px.
- **Sections:** 48px to 112px of space above and below, scaling with the screen.
- **Grids:** cards fill columns of at least 288px. Split sections use a 5:7 ratio on wide screens and stack on phones.
- **Corners:** square, as on the current site.

## Components

All are shown in the style guide.

- **Kicker:** small capitals label above a heading. The ruled version adds a short gold line before it, echoing the logo.
- **Links:** always underlined, with a gold underline that turns solid on hover. Never colour alone.
- **Text link:** bold with an arrow, for "Find out more".
- **Buttons:** primary (green), gold (on green sections), outline (secondary). Minimum height 48px.
- **Card:** white panel with a thin border and a 3px gold top rule. One link (the title) makes the whole card clickable, so screen readers hear one link per card.
- **Quote:** large italic Garamond with a gold rule on the left.
- **Figures and credentials:** rule-divided columns, not boxes.
- **Call to action band:** green section with a large heading and the gold button.
- **Forms:** visible labels, hints under the label, errors beside the field, 48px tall fields.
- **Header and footer:** static versions only for now. The mobile menu will follow the wordpress-test pattern.

## Motion

Hover and focus only: colour, underline thickness and a 3px arrow nudge, over 150 to 200ms. No scroll reveals or entrance animation. Durations drop to zero for visitors who prefer reduced motion.

## Accessibility

- WCAG 2.2 AA as the target.
- Visible 3px focus ring, green on light and gold on green.
- Skip link to the main content.
- Tap targets at least 48px.
- Form errors linked to their fields with `aria-describedby` and `aria-invalid`.

## Still to decide

- **Fonts:** the style guide loads EB Garamond and Lato from Google Fonts. The theme will self-host them as WOFF2 files, because Google Fonts sends visitor IP addresses to Google (a UK GDPR consideration).
- **Icons:** the catalogue recommends SVG icons (Lucide or Heroicons) and no emoji. To confirm when the first icons are needed.
- **Logos:** `logo-lockup.svg` (green, for the header) and `logo-stacked-reversed.svg` (white, for the footer) are copied from the current theme. Division logos are still to collect.
