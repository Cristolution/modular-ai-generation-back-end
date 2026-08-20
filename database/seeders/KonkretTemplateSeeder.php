<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Imports the 18 KONKRET launch-deck variants at
 * `C:/Users/Crist/Desktop/ttt/konkret-*` as canonical MGF templates.
 *
 * Each source folder ships a flat file set:
 *   data.json        — flat per-slide fields (no _meta, no nested `data:{}`)
 *   style.css        — :root tokens AND .mgf-* class rules (combined)
 *   layout.css       — a handful of layout-only rules
 *   slide-01..05.html — already canonical .mgf-* markup with data-field="..."
 *
 * The frontend renderer concatenates style.css + layout.css, walks
 * slides[i].data[fieldName] for each `data-field="fieldName"` in the
 * slide HTML, and renders. For that to work, every konkret project has
 * to be reshaped into the canonical MGF 8-layer envelope:
 *
 *   • Add the 3 missing layers: meta.md, context.md, rules.md
 *   • Restructure data.json — wrap each slide's flat fields under
 *     `data: {}`, attach a per-slide `component`, and add the top-level
 *     `_meta` block (project / version / output_target / format /
 *     total_slides / components_used)
 *   • Leave style.css and layout.css as-is — the renderer concatenates
 *     them, so the union of rules still applies. The original slide HTML
 *     is left untouched (its data-field references already match the
 *     keys present in the matching data.json, so substitution works).
 *
 * Owner distribution — 18 across the four non-team MGF studios, so the
 * template gallery reads like a community marketplace rather than a
 * one-author showcase:
 *
 *   projects@example.com (8)  — most "design-system-heavy" themes
 *   studio@example.com   (4)  — expressive / texture-led themes
 *   analyst@example.com  (3)  — informational / data-dense themes
 *   consumer@example.com (3)  — soft / approachable themes
 *
 * Re-runnable: updateOrCreate keyed on `templates.name` and
 * `files(template_id, name)` so a re-run picks up local source edits
 * without duplicating rows.
 */
class KonkretTemplateSeeder extends BaseSeeder
{
    /**
     * Absolute path on the host to the directory containing the 18
     * `konkret-*` source folders. Lives outside the project tree by
     * design — these are working files, not bundled assets.
     */
    private string $sourceRoot = 'C:/Users/Crist/Desktop/ttt';

    /**
     * Canonical 5-component skeleton shared by every konkret variant.
     * Slide N (1-indexed) gets component[N-1]; the renderer keys off
     * the component string for type-aware hooks even though every
     * konkret project reuses the same five slide shapes.
     */
    private array $componentSkeleton = ['cover', 'problem', 'features', 'stats', 'closing'];

    protected function seed(): void
    {
        $presentationType = Type::where('name', 'presentation')->first();
        if (! $presentationType) {
            throw new \RuntimeException(
                'No `presentation` Type row found. KonkretTemplateSeeder must '
                . 'run AFTER TypeSeeder.'
            );
        }

        // Find or create the four non-team owner accounts. Existing
        // TemplateSeeder creates these as factory users; firstOrCreate
        // means re-running this seeder in isolation is safe.
        $projects = $this->ownerUser('projects@example.com', 'Projects Studio');
        $studio   = $this->ownerUser('studio@example.com',   'Studio Member');
        $analyst  = $this->ownerUser('analyst@example.com',  'Data Analyst');
        $consumer = $this->ownerUser('consumer@example.com', 'Consumer Curator');

        // 18 konkret variants + their assigned owner. The order is
        // grouped by owner so the gallery distribution reads coherently.
        $specs = [
            // ── projects@example.com (8) ───────────────────────────
            ['folder' => 'konkret-deck',          'name' => 'Konkret — Brutalist Deck',    'desc' => 'A hard-edged brutalist deck — oversized display type, no rounded corners, anti-design posture.',                                     'tags' => ['brutalist', 'konkret', 'deck'],         'owner' => $projects],
            ['folder' => 'konkret-swiss',         'name' => 'Konkret — Swiss Grid',         'desc' => 'International typographic style — Helvetica-adjacent sans, asymmetric grid, single red accent on a neutral field.',                'tags' => ['swiss', 'konkret', 'editorial'],       'owner' => $projects],
            ['folder' => 'konkret-swiss-art',     'name' => 'Konkret — Swiss Bauhaus',      'desc' => 'Bauhaus-inspired geometric layout — primary colors, asymmetric grid, form-follows-function framing.',                            'tags' => ['bauhaus', 'konkret', 'geometric'],      'owner' => $projects],
            ['folder' => 'konkret-riso',          'name' => 'Konkret — Riso Pamphlet',      'desc' => 'Photocopied riso-print pamphlet — fluorescent pink + teal inks on kraft cream, halftone dotted, slightly out of register.',        'tags' => ['riso', 'konkret', 'print', 'pamphlet'], 'owner' => $projects],
            ['folder' => 'konkret-editorial',     'name' => 'Konkret — Editorial Magazine', 'desc' => 'Long-form editorial magazine layout — serif display, generous margins, drop-cap intro, columns of justified text.',              'tags' => ['editorial', 'konkret', 'magazine'],    'owner' => $projects],
            ['folder' => 'konkret-cyberpunk',     'name' => 'Konkret — Cyberpunk Launch',   'desc' => 'Neon-lit cyberpunk launch deck — magenta + cyan + acid green, scanlines, glitch transitions, monospace throughout.',              'tags' => ['cyberpunk', 'konkret', 'neon'],        'owner' => $projects],
            ['folder' => 'konkret-gothic',        'name' => 'Konkret — Gothic Blackletter', 'desc' => 'Dark gothic blackletter aesthetic — illuminated-drop-cap style, ornamental rules, candle-warm accent on near-black.',              'tags' => ['gothic', 'konkret', 'blackletter'],    'owner' => $projects],
            ['folder' => 'konkret-art-deco',      'name' => 'Konkret — Art Deco',           'desc' => 'Geometric art-deco treatment — stepped ziggurats, sunburst motifs, gold + onyx + ivory palette.',                                'tags' => ['art-deco', 'konkret', 'geometric'],    'owner' => $projects],

            // ── studio@example.com (4) ─────────────────────────────
            ['folder' => 'konkret-revolutionary-graffiti', 'name' => 'Konkret — Revolutionary Graffiti', 'desc' => 'Stencil-style protest poster — high-contrast red + black, halftone splatter, agitprop typography.',                          'tags' => ['graffiti', 'konkret', 'protest'],       'owner' => $studio],
            ['folder' => 'konkret-pixel-art',     'name' => 'Konkret — Pixel Art',          'desc' => '8-bit pixel-art aesthetic — chunky monospace, limited 16-color palette, arcade-cabinet framing.',                                 'tags' => ['pixel-art', 'konkret', 'retro'],       'owner' => $studio],
            ['folder' => 'konkret-retro-futuristic', 'name' => 'Konkret — Retro-Futuristic', 'desc' => '80s sci-fi retro-futuristic — chrome gradients, perspective grids, neon-on-black with sun-burst pink + teal.',                   'tags' => ['retro-futuristic', 'konkret', '80s'],  'owner' => $studio],
            ['folder' => 'konkret-retro',         'name' => 'Konkret — Retro Vintage',      'desc' => 'Vintage mid-century retro — faded warm palette, rounded sans, slight noise, sun-bleached.',                                         'tags' => ['retro', 'konkret', 'vintage'],         'owner' => $studio],

            // ── analyst@example.com (3) ────────────────────────────
            ['folder' => 'konkret-heavy-academia','name' => 'Konkret — Heavy Academia',     'desc' => 'Scholarly heavy-academia aesthetic — dense serif body, small caps, marginalia, footnotes-as-sidebars.',                              'tags' => ['academia', 'konkret', 'scholarly'],    'owner' => $analyst],
            ['folder' => 'konkret-epidemic',      'name' => 'Konkret — Epidemic Infographic','desc' => 'Public-health infographic treatment — urgent red+navy, charts on every slide, statistical callouts, source citations in caption.', 'tags' => ['infographic', 'konkret', 'data'],      'owner' => $analyst],
            ['folder' => 'konkret-glass',         'name' => 'Konkret — Glass UI',           'desc' => 'Frosted glass UI aesthetic — translucent panels over a soft gradient, blurred borders, subtle shadow.',                              'tags' => ['glass', 'konkret', 'ui'],              'owner' => $analyst],

            // ── consumer@example.com (3) ──────────────────────────
            ['folder' => 'konkret-maximalist',    'name' => 'Konkret — Maximalist',         'desc' => 'Dense maximalist layout — many textures, overlapping shapes, layered type, every surface occupied.',                                  'tags' => ['maximalist', 'konkret', 'dense'],      'owner' => $consumer],
            ['folder' => 'konkret-clay',          'name' => 'Konkret — Clay 3D',            'desc' => 'Soft 3D clay aesthetic — pillowy shapes, pastel clay palette, smooth rounded forms.',                                              'tags' => ['clay', 'konkret', '3d', 'soft'],       'owner' => $consumer],
            ['folder' => 'konkret-acid-funk',     'name' => 'Konkret — Acid Funk',          'desc' => 'Psychedelic acid-funk aesthetic — warped geometry, high-contrast lime + magenta, oscillating patterns.',                              'tags' => ['acid-funk', 'konkret', 'psychedelic'], 'owner' => $consumer],
        ];

        if (count($specs) !== 18) {
            throw new \RuntimeException(
                'KonkretTemplateSeeder spec list drifted: expected 18, got '
                . count($specs) . '. Update the gallery math.'
            );
        }

        foreach ($specs as $index => $spec) {
            $this->importOne($spec, $presentationType, $index);
        }
    }

    /**
     * Import a single konkret source folder as a Template + 10 files.
     */
    private function importOne(array $spec, Type $presentationType, int $seed): void
    {
        $folder = $this->sourceRoot . DIRECTORY_SEPARATOR . $spec['folder'];

        if (! is_dir($folder)) {
            throw new \RuntimeException(
                "Konkret source folder missing: {$folder}. The KONKRET "
                . "lab directory is expected at C:/Users/Crist/Desktop/ttt/"
            );
        }

        // Read the 7 source files (skip `index.html` + `*-launch.json` —
        // those are konkret-launcher assets, not MGF layers).
        $raw = [
            'data.json'        => $this->read($folder, 'data.json'),
            'style.css'        => $this->read($folder, 'style.css'),
            'layout.css'       => $this->read($folder, 'layout.css'),
            'slide-01.html'    => $this->read($folder, 'slide-01.html'),
            'slide-02.html'    => $this->read($folder, 'slide-02.html'),
            'slide-03.html'    => $this->read($folder, 'slide-03.html'),
            'slide-04.html'    => $this->read($folder, 'slide-04.html'),
            'slide-05.html'    => $this->read($folder, 'slide-05.html'),
        ];

        $original = json_decode($raw['data.json'], true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($original) || ! isset($original['slides']) || ! is_array($original['slides'])) {
            throw new \RuntimeException(
                "{$spec['folder']}/data.json is malformed — expected {project, version, slides[]}"
            );
        }

        $transformedData = $this->transformDataJson($original, $spec, $raw);

        // Refine every slide HTML so its classes conform to the canonical
        // MGF vocabulary — non-canonical tokens used by the source
        // (mgf-card-ghost, mgf-ink-stamp*, mgf-slide-cover, etc.) are
        // remapped to canonical equivalents, so the frontend renderer
        // can resolve every class through the design system.
        foreach (['slide-01.html', 'slide-02.html', 'slide-03.html', 'slide-04.html', 'slide-05.html'] as $slideFile) {
            $raw[$slideFile] = $this->refineHtmlClasses($raw[$slideFile]);
        }

        $template = Template::updateOrCreate(
            ['name' => $spec['name']],
            [
                'description'   => $spec['desc'],
                'thumbnail_url' => 'https://picsum.photos/seed/' . $spec['folder'] . '/400/300',
                'visibility'    => 'public',
                'tags'          => $spec['tags'],
                'locale'        => 'en',
                'direction'     => 'ltr',
                'user_id'       => $spec['owner']->id,
                'type_id'       => $presentationType->id,
                // Deterministic counters so a re-run doesn't churn
                // fork/upvote counts in the gallery.
                'fork_count'    => ($seed * 3) % 23,
                'upvote_count'  => ($seed * 7) % 89,
            ]
        );

        // Build the 10-file MGF envelope. Order matches the canonical
        // layer order the rest of the codebase uses.
        $files = [
            ['layer' => 'meta',    'name' => 'meta.md',    'content' => $this->buildMeta($spec)],
            ['layer' => 'context', 'name' => 'context.md', 'content' => $this->buildContext($spec)],
            ['layer' => 'rules',   'name' => 'rules.md',   'content' => $this->buildRules($spec)],
            ['layer' => 'style',   'name' => 'style.css',  'content' => $raw['style.css']],
            ['layer' => 'layout',  'name' => 'layout.css', 'content' => $raw['layout.css']],
            ['layer' => 'content', 'name' => 'data.json',  'content' => json_encode($transformedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)],
            ['layer' => 'slide',   'name' => 'slide-01.html', 'content' => $raw['slide-01.html']],
            ['layer' => 'slide',   'name' => 'slide-02.html', 'content' => $raw['slide-02.html']],
            ['layer' => 'slide',   'name' => 'slide-03.html', 'content' => $raw['slide-03.html']],
            ['layer' => 'slide',   'name' => 'slide-04.html', 'content' => $raw['slide-04.html']],
            ['layer' => 'slide',   'name' => 'slide-05.html', 'content' => $raw['slide-05.html']],
        ];

        foreach ($files as $order => $fileData) {
            File::updateOrCreate(
                ['template_id' => $template->id, 'name' => $fileData['name']],
                [
                    'user_id'    => $spec['owner']->id,
                    'layer'      => $fileData['layer'],
                    'extension'  => pathinfo($fileData['name'], PATHINFO_EXTENSION),
                    'sort_order' => $order,
                    'content'    => $fileData['content'],
                    'size_bytes' => strlen($fileData['content']),
                ]
            );
        }
    }

    /**
     * Reshape the flat konkret data.json into the canonical MGF envelope:
     *   {
     *     _meta: { project, version, output_target, format, total_slides, components_used },
     *     slides: [ { id:int, component:string, data:{ ...originalFlatFields } } ]
     *   }
     *
     * Slide field names are preserved verbatim — every `data-field="X"` in
     * the matching slide HTML references a key we keep under `data.X`, so
     * substitution still works without renaming.
     *
     * Then sweeps each slide HTML for `data-field="X"` references and
     * back-fills any missing keys with the element's default text content
     * (or an empty string if no default is present). This is the
     * "100% perfect frontend vocabulary" guarantee — a re-run picks up
     * source-data edits without ever producing a slide that fails to
     * render because of a missing key.
     */
    private function transformDataJson(array $original, array $spec, array $raw): array
    {
        $slides = [];
        $components = $this->componentSkeleton;

        foreach ($original['slides'] as $i => $slide) {
            if (! is_array($slide)) {
                continue;
            }

            // The konkret source uses string IDs ("01"–"05"). Canonical
            // MGF uses ints; coerce so the renderer's `.id` substitution
            // (and any future type-aware hooks) see the canonical shape.
            $rawId = $slide['id'] ?? ($i + 1);
            $intId = is_numeric($rawId) ? (int) $rawId : ($i + 1);
            unset($slide['id']);

            $slides[] = [
                'id'        => $intId,
                'component' => $components[$i] ?? 'custom',
                'data'      => $slide,
            ];
        }

        // Back-fill any data-field references the slide HTML makes that
        // aren't present in the source data. The frontend renderer
        // substitutes `data-field="X"` against `slides[i].data.X`, so a
        // missing key would render as an empty element — invisible to
        // the user but a broken contract. We use the HTML's inner text
        // as the fallback so the slide still shows meaningful content.
        foreach ($slides as $i => $slideEntry) {
            $slideKey = sprintf('slide-%02d.html', $i + 1);
            $html = $raw[$slideKey] ?? null;
            if (! $html) {
                continue;
            }

            preg_match_all('/data-field="([^"]+)"/', $html, $matches);
            $referenced = array_unique($matches[1] ?? []);

            foreach ($referenced as $field) {
                // `id` is satisfied by `slide.id` (not `slide.data.id`),
                // so a missing data.id is not a real mismatch.
                if ($field === 'id' || array_key_exists($field, $slides[$i]['data'])) {
                    continue;
                }

                $slides[$i]['data'][$field] = $this->extractDefaultText($html, $field);
            }
        }

        return [
            '_meta' => [
                'project'         => $spec['name'] ?? '',
                'version'         => $original['version'] ?? '1.0',
                'output_target'   => 'presentation',
                'format'          => '16:9',
                'total_slides'    => count($slides),
                'components_used' => $components,
            ],
            'slides' => $slides,
        ];
    }

    /**
     * Extract the inner text of the first element with `data-field="$field"`
     * in the slide HTML. Falls back to an empty string when no default
     * text is present (e.g. self-closing tags or empty elements).
     */
    private function extractDefaultText(string $html, string $field): string
    {
        $pattern = '/data-field="' . preg_quote($field, '/') . '"[^>]*>([^<]*)/';
        if (preg_match($pattern, $html, $m)) {
            return trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }
        return '';
    }

    /**
     * Map every non-canonical `mgf-*` token the konkret source uses
     * down to a canonical vocabulary token, then strip any token that
     * has no canonical equivalent. The frontend renderer resolves
     * classes through `baseCss.ts` — a class outside the vocabulary
     * renders as no rules at all, so this refinement is the contract
     * that every slide HTML must honor.
     *
     * The mapping is exhaustive — every non-canonical class observed
     * in the 18 source folders has a row in `$swaps` or `$strips`.
     * If a new non-canonical class appears in a future konkret
     * variant, add it here.
     */
    private function refineHtmlClasses(string $html): string
    {
        // 1:1 remap to a canonical token. Same key preserves the role,
        // different value picks the closest canonical primitive.
        $swaps = [
            'mgf-card-ghost'         => 'mgf-card-solid',   // recessed / no border
            'mgf-ink-stamp'          => 'mgf-tag',          // small uppercase tag
            'mgf-ink-stamp-label'    => 'mgf-eyebrow',      // uppercase eyebrow
            'mgf-ink-stamp-edition'  => 'mgf-tag',          // tag variant for the edition number
        ];

        // Tokens that have no canonical equivalent — drop entirely.
        // The underlying HTML element (e.g. `<header>`, `<footer>`,
        // `<div>`) is preserved so structural semantics survive.
        $strips = [
            'mgf-slide-cover',
            'mgf-slide-features',
            'mgf-slide-stats',
            'mgf-slide-indictment',
            'mgf-slide-cta',
            'mgf-slide-content',
            'mgf-slide-header',
            'mgf-slide-footer',
            'mgf-slide-header-brand',
            'mgf-slide-header-meta',
        ];

        return preg_replace_callback(
            '/class="([^"]+)"/',
            function (array $m) use ($swaps, $strips): string {
                $tokens = preg_split('/\s+/', trim($m[1]));
                $kept   = [];
                foreach ($tokens as $token) {
                    if (in_array($token, $strips, true)) {
                        continue;
                    }
                    $kept[] = $swaps[$token] ?? $token;
                }

                // If the only class on the element was a stripped one
                // (e.g. `<div class="mgf-slide-content">`), drop the
                // empty class attribute entirely.
                if ($kept === []) {
                    return '';
                }

                return 'class="' . implode(' ', $kept) . '"';
            },
            $html
        );
    }

    private function buildMeta(array $spec): string
    {
        return "# {$spec['name']}\n\n{$spec['desc']}\n";
    }

    private function buildContext(array $spec): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        The KONKRET launch narrative re-skinned in the *{$spec['name']}* aesthetic.
        Demonstrates how the canonical MGF 8-layer model handles a radically
        different visual vocabulary — every slide uses only .mgf-* classes,
        every visual value lives in style.css as a --mgf-* token, and every
        `data-field="X"` in the HTML resolves against `slides[i].data.X` in
        data.json.

        ## Audience
        Designers and frontend engineers exploring how the same product
        narrative (plain text / local-first / keyboard-sovereign / pay once)
        reads under 18 divergent presentation treatments.

        ## Brand voice
        Terse, declarative, slightly combative. Uses a `> ` monospace
        prefix in eyebrows for a terminal feel. CTAs use ASCII-arrow
        language (`>> JACK IN`, `> subscribe`).

        ## Visual constraints
        - Palette: defined in style.css as --mgf-color-* tokens
        - Typography: theme-appropriate display + body fonts
        - 16:9 slide ratio (1280×720)
        - All color values live in :root tokens; layout.css carries .mgf-* rules

        MD;
    }

    private function buildRules(array $spec): string
    {
        return <<<MD
        # Generation Rules

        - Components use only .mgf-* classes — no inline styles, no hardcoded colors
        - All visual values live in style.css as --mgf-* tokens
        - All .mgf-* class behavior lives in layout.css
        - data.json must preserve the exact _meta + slides[] schema
        - Slide numbers are zero-padded two digits: slide-01.html, slide-02.html, ...
        - The five-slide skeleton (cover → problem → features → stats → closing) is fixed
        - Field names in `slides[].data` are preserved verbatim from the source — do not rename,
          because the slide HTML's `data-field="..."` attributes reference them by name
        MD;
    }

    /**
     * Get or create one of the four non-team owner accounts. firstOrCreate
     * keeps this seeder safe to run in isolation or after TemplateSeeder
     * has already populated these users.
     */
    private function ownerUser(string $email, string $displayName): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name'          => $displayName,
                'password_hash' => Hash::make('password'),
            ]
        );
    }

    private function read(string $folder, string $filename): string
    {
        $path = $folder . DIRECTORY_SEPARATOR . $filename;
        if (! is_file($path)) {
            throw new \RuntimeException("Missing source file: {$path}");
        }
        return file_get_contents($path);
    }
}