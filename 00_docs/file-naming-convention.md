# File Naming Convention

> MGF (Modular Generation Framework) file structure for projects and
> templates in the MGF backend. This is the canonical reference the AI
> prompt authors and the seeders must follow.
>
> The previous UVCP convention (`uvcp-*` prefix, semantic slide names,
> single combined CSS, `theme2.css`) is **retired**.

## Why this matters

Every project and template is stored in the `files` table. Each file has:

- `layer` — semantic role in the 8-layer MGF model
- `name` — kebab-case filename; **number for slides** (`slide-01.html`)
- `extension` — `md`, `css`, `html`, `json`, `png`, ...
- `content` — file body, stored as a UTF-8 string

The layer is the **role**. The slide name is **positional** — file order
maps directly to slide order in `data.json`, so reorders are renumbers.

## Layer-to-naming map

| Layer    | Count per project | Name pattern                | Example                |
|----------|-------------------|-----------------------------|------------------------|
| `meta`   | exactly 1         | `meta.md`                   | `meta.md`              |
| `context`| exactly 1         | `context.md`                | `context.md`           |
| `rules`  | exactly 1         | `rules.md`                  | `rules.md`             |
| `style`  | 1 active + N alt  | `style.css` + `theme.css`   | `style.css`, `theme.css` |
| `layout` | exactly 1         | `layout.css`                | `layout.css`           |
| `content`| exactly 1         | `data.json`                 | `data.json`            |
| `slide`  | 1 or more         | `slide-NN.html` (zero-pad 2)| `slide-01.html`        |
| `asset`  | 0 or more         | `asset-{name}.{ext}`        | `asset-logo.png`       |

Slide numbers are **zero-padded two digits** (`slide-01.html`, `slide-02.html`, ...) so lexicographic ordering equals visual ordering. The number is the position, not a semantic role.

## Layer separation — style vs. layout

`style.css` and `layout.css` are split into two files, by design:

- `style.css` holds **only** `--mgf-*` token variables in `:root` — colors, fonts, spacing, shape, slide-canvas dimensions. No class rules live here.
- `layout.css` holds **only** `.mgf-*` class behavior — what every layout primitive does, how each component is shaped. No tokens live here.

This split means a theme can be swapped (write a new `style.css`) without touching layout, and a layout change (write a new `layout.css`) cannot accidentally change colors. The renderer concatenates them at runtime.

## Variant themes

A single project can carry multiple CSS files for **tracking**, but only `style.css` is active. The renderer decides which to load.

- `style.css` — the **active** theme. Every project has exactly one.
- `theme.css` — alt-theme variant, kept for track. Optional. The renderer can swap to it via the theme picker.

The literal file `theme2.css` (the old UVCP alias) is replaced by `theme.css`. A project never has both `theme2.css` and `theme.css`; you write `theme.css`.

The AI is **not** expected to generate multiple theme files for a single project by default. Variant themes exist so a user can fork a project, swap in a different palette, and track the result without overwriting the original.

## What's in each file

| File           | Purpose                                                                            |
|----------------|------------------------------------------------------------------------------------|
| `meta.md`      | Title and short description. Machine-readable header for search.                   |
| `context.md`   | AI-facing project brief: purpose, audience, brand voice, visual constraints.       |
| `rules.md`     | Generation rules: title length, body length, **mgf-*** only, **--mgf-*** only.      |
| `style.css`    | Only `:root { ... }` with `--mgf-*` token variables.                                |
| `layout.css`   | Only `.mgf-*` class behavior. Every layout primitive and component is shaped here. |
| `data.json`    | Slide content. Schema is `_meta + slides[]`. Each slide has `id`, `component`, `data`. |
| `slide-NN.html`| Pure HTML using `mgf-*` classes. `data-field` attributes replaced by the renderer.  |

## Single class prefix — `mgf-*`

The framework uses a single class prefix: `mgf-*`. The previous `uvcp-*`
prefix is retired. Slide HTML uses only `mgf-*` classes:

```
<section class="mgf-slide">
  <p class="mgf-eyebrow" data-field="label">Series A · 2026</p>
  <div class="mgf-accent-bar mgf-mt-sm"></div>
  <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project Title</h1>
  <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">...</p>
  <p class="mgf-slide-number" data-field="id">01</p>
</section>
```

`mgf-slide-number` is always the **last child** of `mgf-slide` so flexbox
`margin-top:auto` pins it to the bottom-right of the canvas.

## Single token prefix — `--mgf-*`

All visual values in `style.css` use the `--mgf-*` prefix and live in `:root`:

```css
:root {
  --mgf-color-bg:            #FAF8F5;
  --mgf-color-accent:        #2F80FF;
  --mgf-text-base:           1rem;
  --mgf-space-6:             1.5rem;
  --mgf-slide-w:             1280px;
  --mgf-slide-h:             720px;
  --mgf-slide-pad-x:         80px;
  --mgf-slide-pad-y:         60px;
}
```

The renderer concatenates `style.css` ahead of `layout.css`, then injects
slide HTML after both. Inheritance picks up the tokens automatically.

## What the AI must emit

When generating files for a project (or a template that will be forked):

1. Emit **exactly one active** `style.css`. The first `theme.css` is optional.
2. Use **numbered** slide names (`slide-01.html`, `slide-02.html`, ...), never semantic names.
3. Put **all visual values** in `style.css` as `--mgf-*` variables. No hardcoded colors.
4. Put **all class behavior** in `layout.css`. No class rules in `style.css`.
5. Use **only `mgf-*` classes** in slide HTML. No inline styles, no `<style>` blocks.
6. Match slide count in filenames to `data.json._meta.total_slides`.
7. Match every `component` field in `data.json` to an existing `slide-NN.html` (by ordinal).
8. Generate **distinct HTML** per slide component — not a generic template with substituted text. Each slide must use the structure that fits its component (grid-3 for pillars, grid-4 for stats, comparison cols for comparison, vertical for faq, etc.).
9. Keep `mgf-slide-number` as the **last child** of `mgf-slide`.
10. Preserve `data.json._meta` keys: `project`, `version`, `output_target`, `format`, `total_slides`, `components_used`.

## What the AI must NOT emit

- `uvcp-*` anything — that prefix is gone.
- Semantic slide names (`slide-cover.html`, `slide-problem.html`).
- Hardcoded color values in slide HTML (e.g. `style="color: #2F80FF"`). Use `var(--mgf-color-accent)`.
- Class rules in `style.css` — keep styles token-only.
- Token variables in `layout.css` — keep layout class-only.
- More than one `style.css` per project. Variants go in `theme.css`.
- Custom class names outside the `mgf-*` namespace.
- Modifying the `--mgf-*` token **names** in regenerated `style.css` — only their **values** are swappable.
- Modifying the `mgf-*` class **names** in regenerated `layout.css` — only their behavior is swappable.
- New layers outside the enum (`slide`, `style`, `layout`, `content`, `context`, `rules`, `meta`, `asset`).

## Sample project (canonical reference)

The seeded **My Business Pitch** project mirrors the MGF contract:

```
17 files:
  meta.md              ← title and description
  context.md           ← pitch-deck brief
  rules.md             ← AI generation rules (mgf-* + --mgf-*)
  style.css            ← dark + electric blue theme (active)
  theme.css            ← neo-brutalist alt theme (kept for track)
  layout.css           ← all .mgf-* class rules
  data.json            ← 10 slides, _meta + components_used[]
  slide-01.html        ← cover
  slide-02.html        ← problem
  slide-03.html        ← features (grid-3 pillars)
  slide-04.html        ← stats (grid-4 traction)
  slide-05.html        ← stats (grid-3 market)
  slide-06.html        ← image-text (split-left)
  slide-07.html        ← pricing (grid-3 tiers)
  slide-08.html        ← comparison (2-col)
  slide-09.html        ← team (grid-4 with avatars)
  slide-10.html        ← closing (centered CTA)
```

Template forks emit the same file set — `MgfFileBuilders` trait produces byte-identical files for both `ProjectSeeder` and `TemplateSeeder`.
