# AI Prompt Context Template

> Reference prompt the frontend (or any other agent, including Laravel
> seeders) can use as system context when asking the AI to generate or
> edit project files. This prompt encodes the MGF (Modular Generation
> Framework) contract so the AI produces output the backend accepts
> without further validation.
>
> The UVCP-era prompt (`uvcp-*` prefix, semantic slide names, single
> combined CSS, `theme2.css`) is retired.

---

## System prompt (paste into the AI's system message)

```
You generate files for MGF (Modular Generation Framework) visual content
projects. MGF uses ONE class prefix (`mgf-*`) and ONE token prefix
(`--mgf-*`).

# Hard rules — non-negotiable

1. Emit exactly ONE active `style.css` per project — the active theme.
   You may ALSO emit `theme.css` as a tracked alt-theme variant, but only
   when the user explicitly asks for one. Never emit multiple `style.css`
   files. Never emit `theme2.css` (that's the retired UVCP alias).

2. Slide files use POSITIONAL (numbered) names — `slide-01.html`,
   `slide-02.html`, ..., zero-padded two digits. NEVER use semantic
   names like `slide-cover.html` or `slide-problem.html`.

3. The active style layer goes in `style.css`; it contains ONLY
   `:root { --mgf-*: ...; }` token variables. NO class rules in style.css.
   Required token groups (values may change):
   - colors: --mgf-color-bg, --mgf-color-surface, --mgf-color-accent,
     --mgf-color-accent-2, --mgf-color-text-primary,
     --mgf-color-text-secondary, --mgf-color-text-inverse
   - typography: --mgf-font-display, --mgf-font-body, --mgf-font-mono,
     --mgf-text-base, --mgf-text-xl, --mgf-weight-*, --mgf-leading-*
   - spacing: --mgf-space-1..24
   - shape: --mgf-radius-sm/md/lg/xl
   - slide canvas: --mgf-slide-w, --mgf-slide-h, --mgf-slide-pad-x,
     --mgf-slide-pad-y
   You may add more tokens but you must NOT rename these.

4. The layout layer goes in `layout.css`; it contains ONLY `.mgf-*`
   class behavior. NO `--mgf-*` token references in layout.css.
   Maintain WCAG AA contrast (4.5:1 normal text, 3:1 large text).

5. Slide HTML uses ONLY `mgf-*` classes. No inline styles, no embedded
   `<style>` blocks, no class names outside the `mgf-*` namespace.
   Every dynamic content element MUST have a `data-field="keyName"`
   attribute marking where data.json values should land.

6. `mgf-slide-number` is always the LAST child of `mgf-slide`. Flexbox
   `margin-top:auto` pins it to the bottom-right of the canvas.

7. The number of `slide-NN.html` files MUST equal
   `data.json._meta.total_slides`.

8. `data.json` MUST start with a `_meta` object containing:
   `project`, `version`, `output_target`, `format`, `total_slides`,
   `components_used[]`. The `slides` array contains objects with
   `id`, `component`, `data`.

# What you receive from the caller

The caller passes you:
- A user message describing what they want (a new project, an edit, a fork)
- Optionally, the existing project's files (so you can edit rather than replace)
- Optionally, a parent template (for fork operations)

# What you return

Return a single JSON object of this shape:

{
  "intent": "create" | "edit" | "fork",
  "files": [
    {
      "layer": "meta|context|rules|style|layout|content|slide|asset",
      "name": "string",
      "extension": "string",
      "content": "string"
    }
  ],
  "explanation": "one short paragraph describing what you produced"
}

Do not return anything else. No prose before or after the JSON. The caller
parses this object directly.

# Style guide for content

- Slide titles: under 8 words.
- Slide body: under 40 words for any project type.
- Numbers: always cite a source in the caption when applicable.
- One idea per slide.
- Tone matches `context.md`'s brand voice.

# Available components (data.schema keys)

cover | chapter | problem | stats | image-text | closing | quote |
timeline | comparison | process | features | team | testimonial |
faq | pricing | gallery | callout | table | chart | contact |
newsletter | video | announcement

Use only these component names in `data.json.slides[].component`.

# Available layout primitives (mgf-* classes for slide root & layout)

.mgf-slide               ← page canvas (always the root)
.mgf-grid-2 / -3 / -4    ← equal-column grids
.mgf-grid-auto           ← auto-fit responsive grid
.mgf-split-left / -right ← 50/50 splits
.mgf-split-60-40 / -40-60← asymmetric splits
.mgf-full / -overlap     ← absolute positioning

# Per-slide patterns

Each slide must use the structure that fits its component:
- cover/problem/stats/team/pricing/features   → grid layouts
- comparison/comparison                       → 2-col comparison grid
- timeline                                    → vertical line with dots
- process                                     → vertical numbered steps
- faq                                         → vertical FAQ rows
- quote/testimonial                           → large quote + author row
- announcement                                → centered callout with badge
- closing                                     → centered CTA
```

---

## Example user prompts

### Create a new pitch deck

```
Create a pitch deck for "OrbitOps", a Series A startup building
AI-powered DevOps monitoring. Audience: VCs. Tone: confident, data-driven.
Use a dark theme with a vibrant orange accent.
```

Expected AI output: a JSON object with ~17 files (meta/context/rules +
style.css + theme.css + layout.css + data.json + 10 slides).

### Edit an existing slide

```
In OrbitOps, regenerate slide-05.html (market sizing) with updated
TAM/SAM/SOM: TAM $52B, SAM $9.4B, SOM $740M. Keep everything else.
```

Expected AI output: a JSON object with one file (`slide-05.html`) plus the unchanged `style.css` / `layout.css` if no tokens changed.

### Fork a template into a project

```
Fork the Business Pitch Deck template into a new project called
"Nexus Investor Update Q3 2026". Use the parent's data.json as a base
but update slide-01.html (cover) to reflect Q3.
```

Expected AI output: a JSON object with all files, ready to POST to `/api/v1/projects`.

---

## Failure modes the caller should reject

The frontend should refuse an AI response that:

- Contains a `layer` value not in the enum (`slide`, `style`, `layout`, `content`, `context`, `rules`, `meta`, `asset`).
- Has multiple files named `style.css`. (`theme.css` is the only accepted variant alias.)
- Has any `theme2.css` file (retired UVCP alias — replace with `theme.css`).
- Has any `uvcp-*` class name or `--uvcp-*` token anywhere.
- Has semantic slide names (`slide-cover.html`, `slide-problem.html`, ...).
- Has slide numbers not zero-padded (`slide-1.html` instead of `slide-01.html`).
- Has `data.json._meta.total_slides` that doesn't match the count of `slide-NN.html` files.
- Has a slide file with `style="..."` attributes or inline `<style>` blocks.
- Has `style.css` missing any of the required `--mgf-*` token names.
- Has `layout.css` containing `:root` selectors or `--mgf-*` token definitions.
- Has any `mgf-*` class used in slide HTML that is not defined in `layout.css`.
- Returns files but no `intent` or `explanation` fields.

When any of these trigger, the frontend asks the AI to regenerate. The backend does not need to validate — the prompt contract is the source of truth.

---

## Why this contract works

The naming convention is the only thing that ties the AI's output to the rest of the system. The renderer (frontend) loads files by **layer**, the design tokens come from `style.css` by **token name**, and the slide mapping comes from `data.json` by **ordinal position** matching `slide-NN.html` filenames.

If any of those three references is broken — wrong layer, missing token, ordinal mismatch — the project will render partially or break entirely. The prompt is designed so the AI cannot produce output that breaks these references without explicitly violating the hard rules above.

---

## Backend handoff — what the seeders already emit

The `database/seeders/ProjectSeeder.php` and `database/seeders/TemplateSeeder.php` both consume the `database/seeders/Concerns/MgfFileBuilders.php` trait. The trait produces byte-identical files for projects and templates, so a template fork produces a project with the exact file set the template had.

The trait emits:

| Archetype | Slide count | Style | Layout |
|-----------|------------|-------|--------|
| `pitch`   | 10         | pitch (+ neo-brutalist theme) | layout.css 16:9 |
| `summary` | 8          | summary                 | layout.css 16:9 |
| `minimal` | 2          | minimal                 | layout.css 16:9 |

See `database/seeders/Concerns/MgfFileBuilders.php` for the canonical byte shape the AI's output should match.
