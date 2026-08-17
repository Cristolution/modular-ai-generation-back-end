<?php

namespace Database\Seeders\Concerns;

use App\Models\File;
use App\Models\User;

/**
 * Shared MGF file builders used by both ProjectSeeder and
 * TemplateSeeder. The file shapes are byte-identical so a template
 * fork produces a project with the same files the template had.
 *
 * Emits the MGF (Modular Generation Framework) 8-layer set:
 *   meta.md, context.md, rules.md, style.css, layout.css, data.json,
 *   plus archetype-specific slide-NN.html files with distinct
 *   mgf-* component structures.
 *
 * The files table layer enum maps:
 *   style.css   → style
 *   layout.css  → layout
 *   data.json   → content
 *   slide-NN    → slide
 *   meta.md     → meta
 *   context.md  → context
 *   rules.md    → rules
 */
trait MgfFileBuilders
{
    /** Pitch archetype — 10 slides. */
    protected function pitchFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->pitchContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('pitch')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->pitchStyleCss()],
            ['layer' => 'style',   'name' => 'theme.css',   'extension' => 'css',  'content' => $this->neoBrutalistStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->pitchDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideProblem()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideFeatures()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideStatsThreeUp()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideImageText()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slidePricing()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideComparison()],
            ['layer' => 'slide', 'name' => 'slide-09.html', 'extension' => 'html', 'content' => $this->slideTeam()],
            ['layer' => 'slide', 'name' => 'slide-10.html', 'extension' => 'html', 'content' => $this->slideClosing()],
        ];
    }

    /** Summary archetype — 8 slides. */
    protected function summaryFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->summaryContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('summary')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->summaryStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->summaryDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideStatsFourUp()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideTimeline()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideQuote()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideTestimonial()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideProcess()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slideFaq()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideClosing()],
        ];
    }

    /** Minimal archetype — 2 slides. */
    protected function minimalFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => "Purpose: {$owner->name}.\nAudience: Internal stakeholders.\nBrand: Clear and minimal."],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => "Keep slides simple. One idea per slide."],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->minimalStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->minimalDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideAnnouncement()],
        ];
    }

    /**
     * Website archetype — a long-scroll, single-page website where each
     * "slide" is a section in the page rather than a viewport in a
     * deck. The renderer concatenates every slide-NN.html into one
     * continuous scroll wrapped in `layout.html`.
     *
     * Differences vs. the deck archetypes:
     * - Uses `layout.html` (HTML wrapper) instead of `layout.css`.
     *   The editor's `useAssemblePreview.ts` substitutes `{{slides}}`
     *   with the concatenation of all slide bodies.
     * - No slide counter / no `mgf-slide-number` — scrollable pages
     *   don't have a 1-of-N footer.
     * - Sections are designed to stack: each one fills the viewport
     *   width, not a fixed slide canvas.
     */
    protected function websiteFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->websiteContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('website')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->websiteStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.html', 'extension' => 'html', 'content' => $this->websiteLayoutHtml()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->websiteDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideWebsiteHero()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideWebsiteFeatures()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideWebsiteStats()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideWebsiteTestimonial()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideWebsitePricing()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideWebsiteFaq()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slideWebsiteCta()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideWebsiteContact()],
        ];
    }

    /**
     * Read a pre-extracted MGF seed bundle from disk and emit one file
     * row per layer. The bundles live under
     * `mgf_test_lab/to be seeded/output to be seeded sturcted as the
     * porject wants of spereated layers/<bundleName>/` — one
     * subdirectory per FileLayer value.
     *
     * Bundle shape (every value below is optional):
     *   content/data.json      → project meta + slides[] (decks) or site{} (websites)
     *   context/context.md     → human brief
     *   layout/layout.css      → token block (decks)
     *   layout/layout.html     → website body wrapper (websites only)
     *   meta/meta.md           → informational table (not rendered)
     *   rules/rules.md         → house rules stub
     *   slide/slide-NN-*.html  → individual slide HTML (decks only)
     *   style/style.css        → full extracted <style> block
     *   style/theme.css        → all `:root { … }` token blocks
     *
     * Iteration order: meta → context → rules → style → layout →
     * content → slide(s), with slides re-sorted by their NN prefix.
     *
     * The bundle content is emitted AS-IS — token prefixes (`--c-bg`,
     * `--fs-base`, …) are not normalized to the `--mgf-*` family the
     * hand-written archetypes use, because the bundles are the
     * canonical reference HTML extracted from the design system.
     */
    protected function bundleFiles(string $bundleName): array
    {
        $root = realpath(__DIR__ . '/../../../../mgf_test_lab/to be seeded/output to be seeded sturcted as the porject wants of spereated layers');

        if ($root === false) {
            throw new \RuntimeException(
                'Seed bundle root not found. Expected at '
                . '<project>/mgf_test_lab/to be seeded/output to be seeded '
                . 'sturcted as the porject wants of spereated layers/'
            );
        }

        $bundleDir = $root . DIRECTORY_SEPARATOR . $bundleName;

        if (! is_dir($bundleDir)) {
            throw new \RuntimeException("Bundle directory not found: {$bundleDir}");
        }

        $files = [];

        foreach (['meta', 'context', 'rules', 'style', 'layout', 'content', 'slide'] as $layer) {
            $layerDir = $bundleDir . DIRECTORY_SEPARATOR . $layer;

            if (! is_dir($layerDir)) {
                continue;
            }

            $entries = scandir($layerDir) ?: [];
            sort($entries, SORT_NATURAL);
            foreach ($entries as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                $path = $layerDir . DIRECTORY_SEPARATOR . $entry;
                if (! is_file($path)) {
                    continue;
                }

                $files[] = [
                    'layer'     => $layer,
                    'name'      => $entry,
                    'extension' => pathinfo($entry, PATHINFO_EXTENSION),
                    'content'   => file_get_contents($path),
                ];
            }
        }

        // Keep slides ordered by their NN prefix; everything else keeps
        // the layer-walk order above.
        $nonSlides = array_values(array_filter(
            $files,
            fn (array $f): bool => $f['layer'] !== 'slide'
        ));
        $slides    = array_values(array_filter(
            $files,
            fn (array $f): bool => $f['layer'] === 'slide'
        ));
        usort($slides, function (array $a, array $b): int {
            preg_match('/^slide-(\d+)-/', $a['name'], $ma);
            preg_match('/^slide-(\d+)-/', $b['name'], $mb);

            return ((int) ($ma[1] ?? 0)) <=> ((int) ($mb[1] ?? 0));
        });

        return array_merge($nonSlides, $slides);
    }

    /** Persist the file array against a project. */
    protected function persistFilesOnProject(\App\Models\Project $project, User $user, array $files): void
    {
        foreach ($files as $index => $fileData) {
            File::factory()->create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'layer' => $fileData['layer'],
                'name' => $fileData['name'],
                'extension' => $fileData['extension'],
                'sort_order' => $index,
                'content' => $fileData['content'],
                'size_bytes' => strlen($fileData['content']),
            ]);
        }
    }

    /** Persist the file array against a template. */
    protected function persistFilesOnTemplate(\App\Models\Template $template, User $user, array $files): void
    {
        foreach ($files as $index => $fileData) {
            File::factory()->create([
                'template_id' => $template->id,
                'user_id' => $user->id,
                'layer' => $fileData['layer'],
                'name' => $fileData['name'],
                'extension' => $fileData['extension'],
                'sort_order' => $index,
                'content' => $fileData['content'],
                'size_bytes' => strlen($fileData['content']),
            ]);
        }
    }

    // ── Narrative markdown ──────────────────────────────────────────────

    private function pitchContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Investor pitch deck for a B2B SaaS startup.
        Designed to be presented live to investors in a 10-minute meeting.

        ## Audience
        Early-stage VCs and angel investors. Financially literate, technically aware.
        They see 100 decks a month — cut to the point fast.

        ## Brand voice
        Bold, confident, data-driven. No buzzwords. Specific over vague.

        ## Visual constraints
        - Palette: deep black + electric blue accent + white text
        - Max 40 words per slide body
        - Every slide should have one single takeaway

        ## Theme variants
        - style.css — primary dark theme (active)
        - theme.css — neo-brutalist alt theme, kept for track
        MD;
    }

    private function summaryContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Internal launch summary for the product team.
        Walks through timeline, features, team, and budget.

        ## Audience
        Cross-functional stakeholders: product, engineering, marketing, sales.
        They need clarity on who owns what and when.

        ## Brand voice
        Direct, organized, action-oriented. Bullet-friendly.

        ## Visual constraints
        - Palette: warm white + indigo accent + charcoal text
        - Tables and timelines preferred over dense prose
        - Slides double as artifacts for follow-up email
        MD;
    }

    private function rulesFor(string $archetype): string
    {
        $prefix = match ($archetype) {
            'pitch'        => "Slide titles under 8 words.\nMax 40 words per slide body.",
            'arabic-pitch' => "Slide titles under 7 words in Arabic.\nMax 40 words per slide body.\nDirection is RTL — keep grid/text alignment intuitive for right-to-left readers.\nUse Arabic-Indic digits (٠١٢٣...) for slide numbers when shown.",
            'website'      => "Section titles under 10 words.\nSections are full-width bands of a single scrollable page — no fixed viewport.\nNo mgf-slide-number footer (scrollable pages do not have a 1-of-N counter).",
            'infographic'  => "One core idea per slide.\nTables and bullet lists preferred over dense prose.\nAlways cite a source in the caption when a stat is shown.",
            'academic-math'    => "Math is rendered with KaTeX — use <span class=\"math-inline\">…</span> and <div class=\"math-block\">…</div> with data-tex=\"…\" carrying the LaTeX source.\nDouble-escape every backslash in data-tex (e.g. \\\\frac, \\\\hbar, \\\\partial) so the browser sees a single \\ when KaTeX parses it.\nDisplay fonts are serif (Source Serif 4); body fonts are sans (Inter); inline math should visually sit on the body baseline.\nNever inline plain text substitutions for math symbols — always use the math tags.",
            'earth-organic'    => "Tone is hopeful, evidence-led, slow-paced. No greenwashing.\nPage numbers, dates, and stats must be sourced in the caption when the value is decisive.\nDisplay fonts are serif (Source Serif 4); body fonts are sans (Inter). Larger margin than the pitch archetype.",
            'neon-cyber'       => "Tone is terse, technical, zero hype. Every claim backed by a CVE, a paper, or a measured number.\nUse bullet-style monospace eyebrows (e.g. &gt; threat) to anchor each slide's mode.\nDisplay fonts are JetBrains Mono; body fonts are Inter. Eyebrow text is the same monospace.",
            'sunset-warm'      => "Tone is warm, optimistic, conversational. Lead with the lifestyle, close with the unit economics.\nHeadlines may be slightly playful; body prose stays plain.\nDisplay and body fonts are both Inter. Generous spacing and rounded radii.",
            'monochrome-editorial' => "Less is more. Max 12 words per slide body. Every word earns its place.\nOnly one accent color is used (yellow); everything else is grayscale.\nDisplay and body fonts are both Inter. Use the haiku + quote + closing components for this 4-slide archetype.",
            'vibrant-festival' => "Energy is high. Active verbs, short paragraphs, no quiet slides.\nUse the three accents (fuchsia / lime / orange) sparingly so each one has impact.\nDisplay and body fonts are both Inter. Generous radii, no fine borders.",
            'dashboard-analytics' => "Numbers first, story second. Every chart has a one-line caption that states the takeaway.\nUse the mgf-chart-* family — no inline SVG hacks for charts.\nThe page is full-bleed (no mgf-slide-number footer) — it's a dashboard wall, not a deck.\nKPI values use mgf-stat-value-lg; KPI labels use mgf-kpi-label.",
            'bento-features' => "One feature per bento item. Use the --span modifier on the bigger ones.\nCode must be runnable — no pseudocode.\nMarquee is decorative; logos / partner names only — no testimonials.",
            'editorial-poster' => "Each swatch is a single structural variant. Don't combine modifiers on one cell.\nUse the chapter-num + accent-bar pattern for section openers.\nDisplay fonts are serif (Source Serif 4); body fonts are serif (Source Serif 4).",
            'code-tutorial' => "One pattern per slide. Code is paired with a one-line caption that explains what just happened.\nNever inline plain-text substitutions for code — preserve the syntax color classes.\nTables are small and don't scroll horizontally.",
            'marketing-deck' => "One idea per slide. The deck-progress dots track the active slide.\nUse the deck-feature / deck-team / deck-price / deck-faq / deck-cta family — not the slide-page variants.\nAll slides are 16:9 (mgf-slide-size-16x9).",
            'showcase-carousel' => "Show, don't tell. Each card uses one variant — hover, glass, or neo — never a hybrid.\nCard content is identical across the three variants so the visual difference is the only variable.\nMarquee is decorative; the carousel is interactive.",
            default        => "Use bullets and tables.\nKeep prose under 40 words per slide.",
        };

        return <<<MD
        # Generation Rules

        {$prefix}
        - Components use only mgf-* classes — no inline styles, no hardcoded colors
        - All visual values live in style.css as --mgf-* tokens
        - All mgf-* class behavior lives in layout.css (decks) or layout.html (websites)
        - data.json must preserve the exact _meta + slides[] schema
        - Slide numbers are zero-padded two digits: slide-01.html, slide-02.html, ...
        MD;
    }

    // ── Style layer (--mgf-* tokens only) ──────────────────────────────

    private function pitchStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #080A0F;
          --mgf-color-surface:       #0F1218;
          --mgf-color-surface-2:     #181D27;
          --mgf-color-border:        #1E2535;
          --mgf-color-border-strong: #2E3A50;
          --mgf-color-text-primary:  #F4F6FA;
          --mgf-color-text-secondary:#6B7A99;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #2F80FF;
          --mgf-color-accent-soft:   #0D1F3C;
          --mgf-color-accent-2:      #00D4AA;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;
          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5rem;
          --mgf-weight-normal: 400;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.15;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.03em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.08em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 8px;
          --mgf-radius-lg: 16px;
          --mgf-radius-xl: 24px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function neoBrutalistStyleCss(): string
    {
        return <<<'CSS'
        /*
          Neo-brutalist alt theme — acid yellow + hard black borders.
          This file is a TRACKED VARIANT. style.css is the active theme.
          The renderer can swap to this file via the theme picker.
        */
        :root {
          --mgf-color-bg:            #F5F0E8;
          --mgf-color-surface:       #FFFFFF;
          --mgf-color-surface-2:     #E8E2D6;
          --mgf-color-border:        #000000;
          --mgf-color-border-strong: #000000;
          --mgf-color-text-primary:  #000000;
          --mgf-color-text-secondary:#333333;
          --mgf-color-text-inverse:  #000000;
          --mgf-color-accent:        #FFEE00;
          --mgf-color-accent-soft:   #FFF7B0;
          --mgf-color-accent-2:      #FF3C00;

          --mgf-font-display: 'Arial Black', system-ui, sans-serif;
          --mgf-font-body:    'Arial', sans-serif;
          --mgf-font-mono:    'Courier New', monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5rem;
          --mgf-weight-normal: 400;
          --mgf-weight-medium: 700;
          --mgf-weight-bold:   900;
          --mgf-leading-tight:  1.15;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.03em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.12em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 0;
          --mgf-radius-md: 0;
          --mgf-radius-lg: 0;
          --mgf-radius-xl: 0;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 6px solid var(--mgf-color-text-primary);
          --mgf-divider:     4px solid var(--mgf-color-text-primary);
        }
        CSS;
    }

    private function summaryStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #FAF8F5;
          --mgf-color-surface:       #FFFFFF;
          --mgf-color-surface-2:     #F4F1EB;
          --mgf-color-border:        #E5E0D8;
          --mgf-color-border-strong: #C9C0B2;
          --mgf-color-text-primary:  #1F2937;
          --mgf-color-text-secondary:#6B7280;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #4F46E5;
          --mgf-color-accent-soft:   #EEF2FF;
          --mgf-color-accent-2:      #D97706;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;
          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5rem;
          --mgf-weight-normal: 400;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.15;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.03em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.08em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 8px;
          --mgf-radius-lg: 12px;
          --mgf-radius-xl: 20px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function minimalStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #FFFFFF;
          --mgf-color-surface:       #F5F5F5;
          --mgf-color-surface-2:     #EBEBEB;
          --mgf-color-border:        #E5E5E5;
          --mgf-color-border-strong: #C7C7C7;
          --mgf-color-text-primary:  #111827;
          --mgf-color-text-secondary:#6B7280;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #111827;
          --mgf-color-accent-soft:   #F1F1F1;
          --mgf-color-accent-2:      #2563EB;

          --mgf-font-display: system-ui, sans-serif;
          --mgf-font-body:    system-ui, sans-serif;
          --mgf-font-mono:    ui-monospace, monospace;
          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5rem;
          --mgf-weight-normal: 400;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.15;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.02em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.04em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 6px;
          --mgf-radius-lg: 8px;
          --mgf-radius-xl: 12px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 2px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    // ── Layout layer (mgf-* class behavior) ─────────────────────────────

    private function layoutCss16x9(): string
    {
        return <<<'CSS'
        /* =========================================================
           MGF Layout — 16:9 (1280 × 720)
           This file declares the behavior of every mgf-* class
           listed in standards/classes.md. Tokens come from style.css.
           ========================================================= */

        .mgf-slide {
          width: var(--mgf-slide-w);
          height: var(--mgf-slide-h);
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          box-sizing: border-box;
          overflow: hidden;
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
          font-size: var(--mgf-text-base);
          line-height: var(--mgf-leading-normal);
          display: flex;
          flex-direction: column;
          position: relative;
        }
        .mgf-deck { display: flex; flex-direction: column; gap: var(--mgf-space-8); }

        /* Grids */
        .mgf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: var(--mgf-space-8); }
        .mgf-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--mgf-space-6); }
        .mgf-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--mgf-space-6); }
        .mgf-grid-auto { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--mgf-space-6); }
        .mgf-split-left, .mgf-split-right { display: grid; grid-template-columns: 1fr 1fr; gap: var(--mgf-space-8); align-items: center; }
        .mgf-split-right > :first-child { order: 2; }
        .mgf-split-60-40 { display: grid; grid-template-columns: 3fr 2fr; gap: var(--mgf-space-8); }
        .mgf-split-40-60 { display: grid; grid-template-columns: 2fr 3fr; gap: var(--mgf-space-8); }
        .mgf-full { position: absolute; inset: 0; }
        .mgf-overlap { position: relative; }
        .mgf-overlap-main { position: relative; z-index: 2; }
        .mgf-overlap-secondary { position: absolute; inset: 0; z-index: 1; }

        /* Typography */
        .mgf-label, .mgf-eyebrow { font-size: var(--mgf-text-xs); font-weight: var(--mgf-weight-medium); letter-spacing: var(--mgf-tracking-wide); text-transform: uppercase; }
        .mgf-label-lg { font-size: 13px; font-weight: var(--mgf-weight-medium); letter-spacing: var(--mgf-tracking-wide); text-transform: uppercase; }
        .mgf-eyebrow { color: var(--mgf-color-accent); font-weight: var(--mgf-weight-bold); }
        .mgf-title { font-family: var(--mgf-font-display); font-size: 28px; font-weight: var(--mgf-weight-bold); line-height: var(--mgf-leading-tight); margin: 0; }
        .mgf-title-lg { font-family: var(--mgf-font-display); font-size: 36px; font-weight: var(--mgf-weight-bold); line-height: var(--mgf-leading-tight); margin: 0; }
        .mgf-title-xl { font-family: var(--mgf-font-display); font-size: 48px; font-weight: var(--mgf-weight-bold); line-height: var(--mgf-leading-tight); margin: 0; }
        .mgf-subtitle { font-size: 18px; font-weight: var(--mgf-weight-normal); line-height: var(--mgf-leading-normal); color: var(--mgf-color-text-secondary); margin: 0; }
        .mgf-body { font-size: 15px; line-height: var(--mgf-leading-normal); margin: 0; }
        .mgf-body-sm { font-size: 13px; line-height: var(--mgf-leading-normal); margin: 0; }
        .mgf-caption { font-size: 11px; color: var(--mgf-color-text-secondary); margin: 0; }
        .mgf-text-accent { color: var(--mgf-color-accent); }
        .mgf-text-muted { color: var(--mgf-color-text-secondary); }
        .mgf-text-inverse { color: var(--mgf-color-text-inverse); }
        .mgf-text-bold { font-weight: var(--mgf-weight-bold); }
        .mgf-text-mono { font-family: var(--mgf-font-mono); }
        .mgf-text-center { text-align: center; }
        .mgf-text-left { text-align: left; }
        .mgf-text-right { text-align: right; }

        /* Components */
        .mgf-card { background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-md); padding: var(--mgf-space-6); display: flex; flex-direction: column; gap: var(--mgf-space-2); }
        .mgf-card-hover { transition: transform 150ms ease; }
        .mgf-card-hover:hover { transform: translateY(-2px); }
        .mgf-card-accent { background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border); border-left: 4px solid var(--mgf-color-accent); border-radius: var(--mgf-radius-md); padding: var(--mgf-space-6); display: flex; flex-direction: column; gap: var(--mgf-space-2); }
        .mgf-card-solid { background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); border-radius: var(--mgf-radius-md); padding: var(--mgf-space-6); display: flex; flex-direction: column; gap: var(--mgf-space-2); }
        .mgf-card-label { font-size: 11px; font-weight: var(--mgf-weight-medium); letter-spacing: var(--mgf-tracking-wide); text-transform: uppercase; color: var(--mgf-color-text-secondary); margin: 0; }
        .mgf-card-value { font-family: var(--mgf-font-display); font-size: 30px; font-weight: var(--mgf-weight-bold); line-height: var(--mgf-leading-tight); margin: 0; color: var(--mgf-color-text-primary); }
        .mgf-accent-bar { width: 64px; height: 4px; background: var(--mgf-color-accent); border-radius: 2px; }
        .mgf-accent-bar-lg { width: 96px; height: 6px; background: var(--mgf-color-accent); border-radius: 3px; }
        .mgf-divider { width: 100%; height: 1px; background: var(--mgf-color-border); border: none; margin: var(--mgf-space-4) 0; }
        .mgf-divider-short { width: 64px; height: 1px; background: var(--mgf-color-border); border: none; margin: var(--mgf-space-2) 0; }

        .mgf-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: var(--mgf-space-3); }
        .mgf-list li { position: relative; padding-left: var(--mgf-space-6); font-size: 15px; }
        .mgf-list li::before { content: "•"; position: absolute; left: 0; color: var(--mgf-color-accent); font-weight: var(--mgf-weight-bold); }
        .mgf-list-check li::before { content: "✓"; }
        .mgf-list-number { list-style: decimal inside; padding: 0; margin: 0; display: flex; flex-direction: column; gap: var(--mgf-space-3); font-size: 15px; }

        .mgf-media { aspect-ratio: 16/9; width: 100%; background: var(--mgf-color-surface-2); border-radius: var(--mgf-radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .mgf-media-contained img, .mgf-media img { width: 100%; height: 100%; object-fit: cover; }
        .mgf-media-rounded { aspect-ratio: 1/1; border-radius: 50%; }
        .mgf-media-placeholder { color: var(--mgf-color-text-secondary); font-size: 13px; }

        .mgf-stat-group { display: grid; gap: var(--mgf-space-6); grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        .mgf-stat-value { font-family: var(--mgf-font-display); font-size: 48px; font-weight: var(--mgf-weight-bold); line-height: var(--mgf-leading-tight); margin: 0; color: var(--mgf-color-accent); }
        .mgf-stat-value-lg { font-family: var(--mgf-font-display); font-size: 72px; font-weight: var(--mgf-weight-bold); line-height: var(--mgf-leading-tight); margin: 0; color: var(--mgf-color-accent); }
        .mgf-stat-label { font-size: 13px; color: var(--mgf-color-text-secondary); margin: var(--mgf-space-1) 0 0; }

        .mgf-chapter-number { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--mgf-radius-sm); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); font-size: 13px; font-weight: var(--mgf-weight-bold); }
        .mgf-chapter-number-lg { display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: var(--mgf-radius-md); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); font-size: 20px; font-weight: var(--mgf-weight-bold); }

        .mgf-cta { display: inline-flex; align-items: center; gap: var(--mgf-space-2); color: var(--mgf-color-accent); text-decoration: underline; font-weight: var(--mgf-weight-medium); }
        .mgf-cta-solid { display: inline-flex; align-items: center; gap: var(--mgf-space-2); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); padding: var(--mgf-space-3) var(--mgf-space-6); border-radius: var(--mgf-radius-md); text-decoration: none; font-weight: var(--mgf-weight-bold); }

        .mgf-slide-number { position: absolute; right: var(--mgf-slide-pad-x); bottom: var(--mgf-space-6); font-size: 11px; color: var(--mgf-color-text-secondary); font-family: var(--mgf-font-mono); margin-top: auto; }

        .mgf-avatar { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: var(--mgf-color-surface-2); }
        .mgf-avatar-lg { width: 64px; height: 64px; border-radius: 50%; overflow: hidden; background: var(--mgf-color-surface-2); }
        .mgf-avatar-xl { width: 96px; height: 96px; border-radius: 50%; overflow: hidden; background: var(--mgf-color-surface-2); }
        .mgf-avatar img, .mgf-avatar-lg img, .mgf-avatar-xl img { width: 100%; height: 100%; object-fit: cover; }

        .mgf-quote-mark { font-family: var(--mgf-font-display); font-size: 64px; line-height: 1; color: var(--mgf-color-accent); }
        .mgf-quote-text { font-size: 20px; font-style: italic; line-height: var(--mgf-leading-normal); margin: 0; }
        .mgf-quote-author { display: flex; align-items: center; gap: var(--mgf-space-3); margin-top: var(--mgf-space-4); }
        .mgf-quote-name { font-weight: var(--mgf-weight-bold); margin: 0; }
        .mgf-quote-title { font-size: 13px; color: var(--mgf-color-text-secondary); margin: 0; }

        .mgf-timeline { display: flex; flex-direction: column; gap: var(--mgf-space-6); position: relative; padding-left: var(--mgf-space-6); border-left: 2px solid var(--mgf-color-border); }
        .mgf-timeline-item { position: relative; }
        .mgf-timeline-dot { position: absolute; left: calc(-1 * var(--mgf-space-6) - 5px); top: 6px; width: 10px; height: 10px; border-radius: 50%; background: var(--mgf-color-accent); }

        .mgf-steps { display: flex; flex-direction: column; gap: var(--mgf-space-4); }
        .mgf-step { display: flex; gap: var(--mgf-space-4); align-items: flex-start; }
        .mgf-step-number { flex-shrink: 0; width: 32px; height: 32px; border-radius: var(--mgf-radius-sm); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); display: flex; align-items: center; justify-content: center; font-weight: var(--mgf-weight-bold); }
        .mgf-step-connector { width: 2px; height: var(--mgf-space-4); background: var(--mgf-color-border); margin: var(--mgf-space-1) 0 var(--mgf-space-1) 15px; }

        .mgf-comparison { display: grid; grid-template-columns: 1fr 1fr; gap: var(--mgf-space-6); }
        .mgf-comparison-col { background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-md); padding: var(--mgf-space-6); }
        .mgf-comparison-header { font-size: 13px; font-weight: var(--mgf-weight-bold); letter-spacing: var(--mgf-tracking-wide); text-transform: uppercase; margin: 0 0 var(--mgf-space-4); color: var(--mgf-color-text-secondary); }

        .mgf-feature-icon { font-size: 32px; margin-bottom: var(--mgf-space-2); }
        .mgf-feature-title { font-size: 18px; font-weight: var(--mgf-weight-bold); margin: 0 0 var(--mgf-space-2); }
        .mgf-feature-desc { font-size: 13px; color: var(--mgf-color-text-secondary); margin: 0; }

        .mgf-team-grid { display: grid; gap: var(--mgf-space-6); grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .mgf-team-member { display: flex; flex-direction: column; gap: var(--mgf-space-3); }
        .mgf-team-name { font-size: 16px; font-weight: var(--mgf-weight-bold); margin: 0; }
        .mgf-team-role { font-size: 13px; color: var(--mgf-color-accent); margin: 0; }
        .mgf-team-bio { font-size: 13px; color: var(--mgf-color-text-secondary); margin: 0; }

        .mgf-badge { display: inline-flex; align-items: center; padding: var(--mgf-space-1) var(--mgf-space-3); border-radius: 999px; background: var(--mgf-color-surface-2); font-size: 11px; font-weight: var(--mgf-weight-medium); }
        .mgf-badge-accent { background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); }
        .mgf-badge-success { background: #16A34A; color: var(--mgf-color-text-inverse); }
        .mgf-badge-warning { background: #F59E0B; color: var(--mgf-color-text-inverse); }
        .mgf-badge-muted { background: var(--mgf-color-surface-2); color: var(--mgf-color-text-secondary); }

        .mgf-video-container { aspect-ratio: 16/9; width: 100%; background: var(--mgf-color-text-primary); border-radius: var(--mgf-radius-md); display: flex; align-items: center; justify-content: center; }
        .mgf-video-placeholder { color: var(--mgf-color-bg); font-size: 13px; }

        .mgf-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .mgf-table th, .mgf-table td { padding: var(--mgf-space-3); text-align: left; border-bottom: 1px solid var(--mgf-color-border); }
        .mgf-table th { font-weight: var(--mgf-weight-bold); color: var(--mgf-color-text-secondary); }

        .mgf-faq-item { padding: var(--mgf-space-4) 0; border-bottom: 1px solid var(--mgf-color-border); }
        .mgf-faq-q { font-weight: var(--mgf-weight-bold); margin: 0 0 var(--mgf-space-2); }
        .mgf-faq-a { margin: 0; color: var(--mgf-color-text-secondary); font-size: 14px; }

        .mgf-callout { display: flex; gap: var(--mgf-space-3); padding: var(--mgf-space-4); border-left: 4px solid var(--mgf-color-accent); background: var(--mgf-color-accent-soft); border-radius: var(--mgf-radius-sm); }
        .mgf-callout-info { border-left-color: var(--mgf-color-accent); }
        .mgf-callout-success { border-left-color: #16A34A; background: #DCFCE7; }
        .mgf-callout-warning { border-left-color: #F59E0B; background: #FEF3C7; }
        .mgf-callout-icon { font-size: 20px; flex-shrink: 0; }
        .mgf-callout-text { font-size: 14px; margin: 0; }

        .mgf-price { font-family: var(--mgf-font-display); font-size: 48px; font-weight: var(--mgf-weight-bold); margin: 0; color: var(--mgf-color-text-primary); }
        .mgf-price-period { font-size: 14px; color: var(--mgf-color-text-secondary); margin-left: var(--mgf-space-2); }

        .mgf-form { display: flex; flex-direction: column; gap: var(--mgf-space-4); }
        .mgf-input { width: 100%; padding: var(--mgf-space-3); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-sm); font-family: var(--mgf-font-body); font-size: 14px; background: var(--mgf-color-surface); color: var(--mgf-color-text-primary); }

        .mgf-map-container { width: 100%; aspect-ratio: 16/9; background: var(--mgf-color-surface-2); border-radius: var(--mgf-radius-md); }

        .mgf-chart { display: flex; flex-direction: column; gap: var(--mgf-space-3); }
        .mgf-chart-bar { display: flex; align-items: center; gap: var(--mgf-space-3); }
        .mgf-chart-label { width: 120px; font-size: 13px; color: var(--mgf-color-text-secondary); }

        .mgf-icon { width: 20px; height: 20px; }
        .mgf-icon-lg { width: 32px; height: 32px; }

        /* Backgrounds */
        .mgf-bg-surface { background: var(--mgf-color-surface); }
        .mgf-bg-gradient { background: linear-gradient(135deg, var(--mgf-color-surface) 0%, var(--mgf-color-accent-soft) 100%); }
        .mgf-bg-accent { background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); }
        .mgf-bg-accent-soft { background: var(--mgf-color-accent-soft); }

        /* Utilities */
        .mgf-pad-sm { padding: var(--mgf-space-3); }
        .mgf-pad-md { padding: var(--mgf-space-6); }
        .mgf-pad-lg { padding: var(--mgf-space-8); }
        .mgf-mt-sm { margin-top: var(--mgf-space-3); }
        .mgf-mt-md { margin-top: var(--mgf-space-6); }
        .mgf-mt-lg { margin-top: var(--mgf-space-8); }
        .mgf-mb-sm { margin-bottom: var(--mgf-space-3); }
        .mgf-mb-md { margin-bottom: var(--mgf-space-6); }
        .mgf-mb-lg { margin-bottom: var(--mgf-space-8); }
        .mgf-gap-sm { gap: var(--mgf-space-3); }
        .mgf-gap-md { gap: var(--mgf-space-6); }
        .mgf-gap-lg { gap: var(--mgf-space-8); }
        .mgf-flex { display: flex; }
        .mgf-flex-col { display: flex; flex-direction: column; }
        .mgf-flex-center { display: flex; align-items: center; justify-content: center; }
        .mgf-flex-between { display: flex; justify-content: space-between; align-items: center; }
        .mgf-flex-start { display: flex; align-items: flex-start; }
        .mgf-flex-wrap { flex-wrap: wrap; }
        CSS;
    }

    // ── data.json payloads ───────────────────────────────────────────────

    private function pitchDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation',
                'format' => '16:9',
                'total_slides' => 10,
                'components_used' => [
                    'cover', 'problem', 'features', 'stats',
                    'stats', 'image-text', 'pricing',
                    'comparison', 'team', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'label' => 'Series A · 2026',
                    'author' => 'Acme',
                    'date' => '2026-08-11',
                ]],
                ['id' => 2, 'component' => 'problem', 'data' => [
                    'title' => 'Teams Are Drowning in Disconnection',
                    'body' => 'Knowledge workers switch apps 1,200 times a day. 32% of time is lost to context switching. We measured this across 12 enterprise teams in Q1.',
                    'points' => [
                        '9.4 hours per week lost per employee',
                        '67% of projects miss deadlines',
                        '16 disconnected SaaS tools on average',
                    ],
                ]],
                ['id' => 3, 'component' => 'features', 'data' => [
                    'title' => 'One Layer. Every Tool. Zero Friction.',
                    'subtitle' => 'A unified intelligence layer on top of your existing stack.',
                    'features' => [
                        ['icon' => '⚡', 'title' => 'Instant context', 'desc' => 'AI surfaces what you need, when you need it.'],
                        ['icon' => '🔗', 'title' => 'Unified routing', 'desc' => 'Tasks flow between tools without copy-paste.'],
                        ['icon' => '📊', 'title' => 'Visibility',      'desc' => 'Real-time team intelligence for leaders.'],
                    ],
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'title' => 'Growing 40% Month over Month',
                    'stats' => [
                        ['value' => '$1.2M', 'label' => 'ARR'],
                        ['value' => '340',   'label' => 'Teams'],
                        ['value' => '40%',   'label' => 'MoM growth'],
                        ['value' => '94%',   'label' => 'Retention'],
                    ],
                    'caption' => 'As of Q4 2024',
                ]],
                ['id' => 5, 'component' => 'stats', 'data' => [
                    'title' => '$47B Market, Largely Uncaptured',
                    'stats' => [
                        ['value' => '$47B',  'label' => 'TAM'],
                        ['value' => '$8.2B', 'label' => 'SAM'],
                        ['value' => '$680M', 'label' => 'SOM'],
                    ],
                    'caption' => 'Source: Gartner, IDC 2024',
                ]],
                ['id' => 6, 'component' => 'image-text', 'data' => [
                    'title' => 'Built for How Work Actually Happens',
                    'body' => 'Learns your team workflows in 48 hours. No migration, no new vendor lock-in.',
                    'image_placeholder' => 'assets/product-screenshot.png',
                    'image_alt' => 'Product interface screenshot',
                    'layout' => 'text-left',
                ]],
                ['id' => 7, 'component' => 'pricing', 'data' => [
                    'title' => 'Per-Seat SaaS with Strong Expansion',
                    'plans' => [
                        ['name' => 'Team',       'price' => '$12',    'period' => 'seat / mo', 'features' => ['Up to 25 seats', 'Core integrations'], 'cta' => 'Start trial'],
                        ['name' => 'Business',   'price' => '$28',    'period' => 'seat / mo', 'features' => ['AI features', 'Analytics', 'Priority support'], 'cta' => 'Start trial'],
                        ['name' => 'Enterprise', 'price' => 'Custom', 'period' => '',          'features' => ['SSO', 'SLA', 'Dedicated CSM'], 'cta' => 'Contact sales'],
                    ],
                ]],
                ['id' => 8, 'component' => 'comparison', 'data' => [
                    'title' => 'We Unify, We Do Not Replace',
                    'left_header' => 'The alternative',
                    'right_header' => 'Our approach',
                    'left_items' => [
                        'Document-first (Notion, Confluence)',
                        'Messaging-first (Slack, Teams)',
                        'Task-first (Monday, Asana)',
                        'Siloed, no cross-tool context',
                    ],
                    'right_items' => [
                        'Sits above your existing tools',
                        'Routes tasks between them automatically',
                        'Learns your team workflow',
                        'Real-time team intelligence',
                    ],
                ]],
                ['id' => 9, 'component' => 'team', 'data' => [
                    'title' => 'Built by People Who Lived This Problem',
                    'members' => [
                        ['name' => 'Sara Chen',    'role' => 'CEO',           'bio' => 'Ex-Atlassian VP Product. 12 years building tools teams actually use.', 'avatar' => ''],
                        ['name' => 'James Okafor', 'role' => 'CTO',           'bio' => 'Ex-Stripe infra lead. Scaled systems to billions of events per day.', 'avatar' => ''],
                        ['name' => 'Lena Mueller', 'role' => 'CPO',           'bio' => 'Ex-Notion design lead. Believes great UX is the only moat that lasts.', 'avatar' => ''],
                        ['name' => 'David Park',   'role' => 'Head of Sales', 'bio' => 'Two exits, 0 → $8M ARR each. Knows how to land a first 100.', 'avatar' => ''],
                    ],
                ]],
                ['id' => 10, 'component' => 'closing', 'data' => [
                    'title' => 'Raising $6M to Own the Integration Layer',
                    'body' => '18-month runway, target $6M ARR, Series B ready. Deck, data room, and references on request.',
                    'cta' => 'hello@acme.io',
                    'cta_url' => 'mailto:hello@acme.io',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function summaryDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation',
                'format' => '16:9',
                'total_slides' => 8,
                'components_used' => [
                    'cover', 'stats', 'timeline', 'quote',
                    'testimonial', 'process', 'faq', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'label' => 'Q3 2026',
                    'author' => 'Internal Team',
                    'date' => '2026-08-11',
                ]],
                ['id' => 2, 'component' => 'stats', 'data' => [
                    'title' => 'What This Is',
                    'stats' => [
                        ['value' => '8',   'label' => 'Slides'],
                        ['value' => '6',   'label' => 'Weeks'],
                        ['value' => '12',  'label' => 'Owners'],
                        ['value' => '$2M', 'label' => 'Budget'],
                    ],
                    'caption' => 'All figures as of plan date.',
                ]],
                ['id' => 3, 'component' => 'timeline', 'data' => [
                    'title' => 'Timeline',
                    'label' => 'From kickoff to GA',
                    'items' => [
                        ['date' => 'W1',  'headline' => 'Kickoff',           'desc' => 'Cross-functional alignment, scope freeze.'],
                        ['date' => 'W2',  'headline' => 'Design lock',       'desc' => 'Final design review with engineering and brand.'],
                        ['date' => 'W3-4','headline' => 'Build',             'desc' => 'Feature complete behind feature flag.'],
                        ['date' => 'W5',  'headline' => 'Beta',              'desc' => 'Internal dogfood + 20 friendly customers.'],
                        ['date' => 'W6',  'headline' => 'GA',                'desc' => 'Public launch, comms live, sales enablement sent.'],
                    ],
                ]],
                ['id' => 4, 'component' => 'quote', 'data' => [
                    'quote' => 'The right move was never in doubt once we saw the data.',
                    'author' => 'Director of Product',
                    'title' => 'Acme',
                    'avatar' => '',
                ]],
                ['id' => 5, 'component' => 'testimonial', 'data' => [
                    'quote' => 'We tried three alternatives — none came close.',
                    'author' => 'Priya Nair',
                    'role' => 'Sales Engineering',
                    'company' => 'Beta Co.',
                    'avatar' => '',
                ]],
                ['id' => 6, 'component' => 'process', 'data' => [
                    'title' => 'How We Will Get There',
                    'steps' => [
                        ['num' => '01', 'title' => 'Scope',  'desc' => 'Lock features, owners, deadlines. No scope creep.'],
                        ['num' => '02', 'title' => 'Build',  'desc' => 'Daily standups, weekly demos, blocker escalation.'],
                        ['num' => '03', 'title' => 'Beta',   'desc' => '20 friendly customers, weekly feedback loops.'],
                        ['num' => '04', 'title' => 'Ship',   'desc' => 'Public launch, comms + sales enablement aligned.'],
                    ],
                ]],
                ['id' => 7, 'component' => 'faq', 'data' => [
                    'title' => 'What People Always Ask',
                    'items' => [
                        ['q' => 'Who owns what?',         'a' => 'Each step has a DRI named in the timeline doc.'],
                        ['q' => 'What if we slip a week?', 'a' => 'Trade scope, not date. Re-baseline at W3 review.'],
                        ['q' => 'How is success measured?','a' => 'Activation rate at D7 and D30. Targets in rules.md.'],
                        ['q' => 'What is not in scope?',  'a' => 'No new pricing tiers. No new geographies.'],
                    ],
                ]],
                ['id' => 8, 'component' => 'closing', 'data' => [
                    'title' => 'Onward',
                    'body' => 'Kickoff Monday at 10:00. Sign-off by Friday EOD.',
                    'cta' => 'Reply with sign-off',
                    'cta_url' => 'mailto:team@example.com',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function minimalDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '0.1',
                'output_target' => 'presentation',
                'format' => '16:9',
                'total_slides' => 2,
                'components_used' => ['cover', 'announcement'],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'label' => 'Draft',
                    'author' => '',
                    'date' => '2026-08-11',
                ]],
                ['id' => 2, 'component' => 'announcement', 'data' => [
                    'badge' => 'Coming soon',
                    'title' => 'Initial Outline',
                    'body' => 'This project is in scaffolded state. Slides will be added as content is developed.',
                    'cta' => 'Get notified',
                    'cta_url' => '#',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    // ── Slide HTML — distinct MGF component structures ──────────────────
    //
    // Each slide uses only mgf-* classes. data-field attributes mark
    // where the renderer injects content from data.json. No inline
    // styles, no hardcoded colors. mgf-slide-number is always the LAST
    // child so flexbox margin-top:auto pins it to the bottom.

    private function slideCover(): string
    {
        return <<<'HTML'
        <!--
          Component: cover
          Fields: title, subtitle, label, author, date
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="label">Series A · 2026</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project Title</h1>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">One-line project description.</p>
          <div class="mgf-flex mgf-gap-md mgf-mt-lg">
            <p class="mgf-label" data-field="author">Acme</p>
            <p class="mgf-caption" data-field="date">2026-08-11</p>
          </div>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    private function slideProblem(): string
    {
        return <<<'HTML'
        <!--
          Component: problem
          Fields: title, body, points[]
          Layout: vertical stack — eyebrow + title + body + bulleted list
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">The Problem</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Problem title</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph describing the pain point. Keep under 40 words.</p>
          <ul class="mgf-list mgf-mt-lg" data-field="points">
            <li>First supporting point</li>
            <li>Second supporting point</li>
            <li>Third supporting point</li>
          </ul>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideFeatures(): string
    {
        return <<<'HTML'
        <!--
          Component: features
          Fields: title, subtitle, features[]{icon, title, desc}
          Layout: top header + 3-column feature grid
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">The Solution</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Solution title</h2>
          <p class="mgf-subtitle mgf-mt-sm" data-field="subtitle">One-line framing of the solution.</p>

          <div class="mgf-grid-3 mgf-mt-lg" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">⚡</div>
              <p class="mgf-feature-title" data-field="title">Pillar 1</p>
              <p class="mgf-feature-desc" data-field="desc">Pillar 1 description.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🔗</div>
              <p class="mgf-feature-title" data-field="title">Pillar 2</p>
              <p class="mgf-feature-desc" data-field="desc">Pillar 2 description.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">📊</div>
              <p class="mgf-feature-title" data-field="title">Pillar 3</p>
              <p class="mgf-feature-desc" data-field="desc">Pillar 3 description.</p>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideStats(): string
    {
        return <<<'HTML'
        <!--
          Component: stats (4-up)
          Fields: title, stats[]{value, label}, caption
          Layout: 4-card stat grid with caption
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Traction</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Proof points</h2>

          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">$1.2M</p>
              <p class="mgf-stat-label" data-field="label">ARR</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">340</p>
              <p class="mgf-stat-label" data-field="label">Paying teams</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">40%</p>
              <p class="mgf-stat-label" data-field="label">MoM growth</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">94%</p>
              <p class="mgf-stat-label" data-field="label">Retention</p>
            </div>
          </div>

          <p class="mgf-caption mgf-mt-md" data-field="caption">As of date</p>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    private function slideStatsThreeUp(): string
    {
        return <<<'HTML'
        <!--
          Component: stats (3-up market sizing)
          Fields: title, stats[]{value, label}, caption
          Layout: 3-column grid with first card highlighted as solid accent
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Market</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Market opportunity</h2>

          <div class="mgf-grid-3 mgf-mt-lg" data-field="stats">
            <div class="mgf-card-solid">
              <p class="mgf-stat-value" data-field="value">$47B</p>
              <p class="mgf-stat-label" data-field="label">TAM</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">$8.2B</p>
              <p class="mgf-stat-label" data-field="label">SAM</p>
            </div>
            <div class="mgf-card">
              <p class="mgf-stat-value" data-field="value">$680M</p>
              <p class="mgf-stat-label" data-field="label">SOM</p>
            </div>
          </div>

          <p class="mgf-caption mgf-mt-md" data-field="caption">Source: Gartner, IDC 2024</p>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    private function slideImageText(): string
    {
        return <<<'HTML'
        <!--
          Component: image-text
          Fields: title, body, image_placeholder, image_alt, layout (text-left|text-right)
          Layout: 50/50 split — text on one side, media placeholder on the other
        -->
        <section class="mgf-slide">
          <div class="mgf-split-left">
            <div>
              <p class="mgf-eyebrow">Product</p>
              <div class="mgf-accent-bar mgf-mt-sm"></div>
              <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Product title</h2>
              <p class="mgf-body mgf-mt-md" data-field="body">One paragraph describing the product at a high level.</p>
            </div>
            <div class="mgf-media" data-field="image_placeholder" aria-label="Product screenshot">
              <div class="mgf-media-placeholder">📷 Product screenshot</div>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    private function slidePricing(): string
    {
        return <<<'HTML'
        <!--
          Component: pricing
          Fields: title, plans[]{name, price, period, features[], cta}
          Layout: 3-column pricing tier grid with middle tier as solid accent
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Business Model</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Pricing tiers</h2>

          <div class="mgf-grid-3 mgf-mt-lg" data-field="plans">
            <div class="mgf-card">
              <p class="mgf-card-label" data-field="name">Tier 1</p>
              <div class="mgf-flex mgf-gap-sm">
                <p class="mgf-price" data-field="price">$12</p>
                <p class="mgf-price-period" data-field="period">seat / mo</p>
              </div>
              <hr class="mgf-divider" />
              <ul class="mgf-list-check mgf-list" data-field="features">
                <li>Up to 25 seats</li>
                <li>Core integrations</li>
              </ul>
              <a class="mgf-cta" href="#" data-field="cta_url" data-label-field="cta">Start trial</a>
            </div>
            <div class="mgf-card-solid">
              <p class="mgf-card-label" data-field="name">Tier 2</p>
              <div class="mgf-flex mgf-gap-sm">
                <p class="mgf-price" data-field="price">$28</p>
                <p class="mgf-price-period" data-field="period">seat / mo</p>
              </div>
              <hr class="mgf-divider" />
              <ul class="mgf-list-check mgf-list" data-field="features">
                <li>AI features</li>
                <li>Analytics</li>
                <li>Priority support</li>
              </ul>
              <a class="mgf-cta-solid" href="#" data-field="cta_url" data-label-field="cta">Start trial</a>
            </div>
            <div class="mgf-card">
              <p class="mgf-card-label" data-field="name">Tier 3</p>
              <div class="mgf-flex mgf-gap-sm">
                <p class="mgf-price" data-field="price">Custom</p>
              </div>
              <hr class="mgf-divider" />
              <ul class="mgf-list-check mgf-list" data-field="features">
                <li>SSO</li>
                <li>SLA</li>
                <li>Dedicated CSM</li>
              </ul>
              <a class="mgf-cta" href="#" data-field="cta_url" data-label-field="cta">Contact sales</a>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">07</p>
        </section>
        HTML;
    }

    private function slideComparison(): string
    {
        return <<<'HTML'
        <!--
          Component: comparison
          Fields: title, left_header, right_header, left_items[], right_items[]
          Layout: 2-column comparison grid
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Competition</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">How we compare</h2>

          <div class="mgf-comparison mgf-mt-lg">
            <div class="mgf-comparison-col">
              <p class="mgf-comparison-header" data-field="left_header">The alternative</p>
              <ul class="mgf-list" data-field="left_items">
                <li>Document-first (Notion, Confluence)</li>
                <li>Messaging-first (Slack, Teams)</li>
                <li>Task-first (Monday, Asana)</li>
                <li>Siloed, no cross-tool context</li>
              </ul>
            </div>
            <div class="mgf-comparison-col">
              <p class="mgf-comparison-header" data-field="right_header">Our approach</p>
              <ul class="mgf-list" data-field="right_items">
                <li>Sits above your existing tools</li>
                <li>Routes tasks between them automatically</li>
                <li>Learns your team workflow</li>
                <li>Real-time team intelligence</li>
              </ul>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">08</p>
        </section>
        HTML;
    }

    private function slideTeam(): string
    {
        return <<<'HTML'
        <!--
          Component: team
          Fields: title, members[]{name, role, bio, avatar}
          Layout: 4-column team grid with avatars
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Team</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Founding team</h2>

          <div class="mgf-team-grid mgf-mt-lg" data-field="members">
            <div class="mgf-team-member">
              <div class="mgf-avatar-lg">
                <img data-field="avatar" src="" alt="Member photo" />
              </div>
              <p class="mgf-team-name" data-field="name">Member 1</p>
              <p class="mgf-team-role" data-field="role">Role</p>
              <p class="mgf-team-bio" data-field="bio">Short bio.</p>
            </div>
            <div class="mgf-team-member">
              <div class="mgf-avatar-lg">
                <img data-field="avatar" src="" alt="Member photo" />
              </div>
              <p class="mgf-team-name" data-field="name">Member 2</p>
              <p class="mgf-team-role" data-field="role">Role</p>
              <p class="mgf-team-bio" data-field="bio">Short bio.</p>
            </div>
            <div class="mgf-team-member">
              <div class="mgf-avatar-lg">
                <img data-field="avatar" src="" alt="Member photo" />
              </div>
              <p class="mgf-team-name" data-field="name">Member 3</p>
              <p class="mgf-team-role" data-field="role">Role</p>
              <p class="mgf-team-bio" data-field="bio">Short bio.</p>
            </div>
            <div class="mgf-team-member">
              <div class="mgf-avatar-lg">
                <img data-field="avatar" src="" alt="Member photo" />
              </div>
              <p class="mgf-team-name" data-field="name">Member 4</p>
              <p class="mgf-team-role" data-field="role">Role</p>
              <p class="mgf-team-bio" data-field="bio">Short bio.</p>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">09</p>
        </section>
        HTML;
    }

    private function slideClosing(): string
    {
        return <<<'HTML'
        <!--
          Component: closing
          Fields: title, body, cta, cta_url
          Layout: centered closing message with CTA
        -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow">The Ask</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-left:auto; margin-right:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Raising $XM</h2>
            <p class="mgf-subtitle mgf-mt-md" data-field="body">One closing paragraph wrapping up the pitch.</p>
            <div class="mgf-mt-lg">
              <a class="mgf-cta-solid" href="#" data-field="cta_url" data-label-field="cta">hello@acme.io</a>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">10</p>
        </section>
        HTML;
    }

    // Summary archetype slides

    private function slideStatsFourUp(): string
    {
        return <<<'HTML'
        <!--
          Component: stats (about, 4-up)
          Fields: title, stats[]{value, label}, caption
          Layout: 4-column grid with caption
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">About</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">What This Is</h2>

          <div class="mgf-grid-4 mgf-mt-lg" data-field="stats">
            <div class="mgf-card">
              <p class="mgf-stat-value" data-field="value">8</p>
              <p class="mgf-stat-label" data-field="label">Slides</p>
            </div>
            <div class="mgf-card">
              <p class="mgf-stat-value" data-field="value">6</p>
              <p class="mgf-stat-label" data-field="label">Weeks</p>
            </div>
            <div class="mgf-card">
              <p class="mgf-stat-value" data-field="value">12</p>
              <p class="mgf-stat-label" data-field="label">Owners</p>
            </div>
            <div class="mgf-card">
              <p class="mgf-stat-value" data-field="value">$2M</p>
              <p class="mgf-stat-label" data-field="label">Budget</p>
            </div>
          </div>

          <p class="mgf-caption mgf-mt-md" data-field="caption">All figures as of plan date.</p>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideTimeline(): string
    {
        return <<<'HTML'
        <!--
          Component: timeline
          Fields: title, label, items[]{date, headline, desc}
          Layout: vertical timeline with accent dots
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="label">From kickoff to GA</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Timeline</h2>

          <div class="mgf-timeline mgf-mt-lg" data-field="items">
            <div class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-label" data-field="date">W1</p>
              <p class="mgf-text-bold" data-field="headline">Kickoff</p>
              <p class="mgf-body-sm" data-field="desc">Cross-functional alignment, scope freeze.</p>
            </div>
            <div class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-label" data-field="date">W2</p>
              <p class="mgf-text-bold" data-field="headline">Design lock</p>
              <p class="mgf-body-sm" data-field="desc">Final design review with engineering and brand.</p>
            </div>
            <div class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-label" data-field="date">W3-4</p>
              <p class="mgf-text-bold" data-field="headline">Build</p>
              <p class="mgf-body-sm" data-field="desc">Feature complete behind feature flag.</p>
            </div>
            <div class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-label" data-field="date">W5</p>
              <p class="mgf-text-bold" data-field="headline">Beta</p>
              <p class="mgf-body-sm" data-field="desc">Internal dogfood + 20 friendly customers.</p>
            </div>
            <div class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-label" data-field="date">W6</p>
              <p class="mgf-text-bold" data-field="headline">GA</p>
              <p class="mgf-body-sm" data-field="desc">Public launch, comms live, sales enablement sent.</p>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideQuote(): string
    {
        return <<<'HTML'
        <!--
          Component: quote
          Fields: quote, author, title, avatar
          Layout: large quotation mark + italic quote + author row
        -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div style="max-width: 880px">
            <span class="mgf-quote-mark">&ldquo;</span>
            <p class="mgf-title-lg mgf-mt-sm" data-field="quote" style="font-weight: var(--mgf-weight-normal); font-style: italic">
              A memorable quote.
            </p>
            <div class="mgf-quote-author mgf-mt-lg">
              <div class="mgf-avatar">
                <img data-field="avatar" src="" alt="Author photo" />
              </div>
              <div>
                <p class="mgf-quote-name" data-field="author">Author Name</p>
                <p class="mgf-quote-title" data-field="title">Title, Company</p>
              </div>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    private function slideTestimonial(): string
    {
        return <<<'HTML'
        <!--
          Component: testimonial
          Fields: quote, author, role, company, avatar
          Layout: testimonial card with attribution
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Testimonial</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>

          <div class="mgf-card-accent mgf-mt-lg" style="max-width: 880px">
            <p class="mgf-quote-text" data-field="quote">&ldquo;A short, impactful quote from a customer.&rdquo;</p>
            <div class="mgf-quote-author">
              <div class="mgf-avatar-lg">
                <img data-field="avatar" src="" alt="Customer photo" />
              </div>
              <div>
                <p class="mgf-quote-name" data-field="author">Customer Name</p>
                <p class="mgf-quote-title">
                  <span data-field="role">Role</span>, <span data-field="company">Company</span>
                </p>
              </div>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    private function slideProcess(): string
    {
        return <<<'HTML'
        <!--
          Component: process
          Fields: title, steps[]{num, title, desc}
          Layout: vertical numbered steps with accent badges
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">Process</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">How we will get there</h2>

          <div class="mgf-steps mgf-mt-lg" data-field="steps">
            <div class="mgf-step">
              <p class="mgf-step-number" data-field="num">01</p>
              <div>
                <p class="mgf-text-bold" data-field="title">Scope</p>
                <p class="mgf-body-sm" data-field="desc">Lock features, owners, deadlines. No scope creep.</p>
              </div>
            </div>
            <div class="mgf-step">
              <p class="mgf-step-number" data-field="num">02</p>
              <div>
                <p class="mgf-text-bold" data-field="title">Build</p>
                <p class="mgf-body-sm" data-field="desc">Daily standups, weekly demos, blocker escalation.</p>
              </div>
            </div>
            <div class="mgf-step">
              <p class="mgf-step-number" data-field="num">03</p>
              <div>
                <p class="mgf-text-bold" data-field="title">Beta</p>
                <p class="mgf-body-sm" data-field="desc">20 friendly customers, weekly feedback loops.</p>
              </div>
            </div>
            <div class="mgf-step">
              <p class="mgf-step-number" data-field="num">04</p>
              <div>
                <p class="mgf-text-bold" data-field="title">Ship</p>
                <p class="mgf-body-sm" data-field="desc">Public launch, comms + sales enablement aligned.</p>
              </div>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    private function slideFaq(): string
    {
        return <<<'HTML'
        <!--
          Component: faq
          Fields: title, items[]{q, a}
          Layout: vertical FAQ rows separated by dividers
        -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow">FAQ</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">What people always ask</h2>

          <div class="mgf-mt-lg" data-field="items" style="max-width: 880px">
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question one?</p>
              <p class="mgf-faq-a" data-field="a">Answer one.</p>
            </div>
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question two?</p>
              <p class="mgf-faq-a" data-field="a">Answer two.</p>
            </div>
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question three?</p>
              <p class="mgf-faq-a" data-field="a">Answer three.</p>
            </div>
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question four?</p>
              <p class="mgf-faq-a" data-field="a">Answer four.</p>
            </div>
          </div>

          <p class="mgf-slide-number" data-field="id">07</p>
        </section>
        HTML;
    }

    private function slideAnnouncement(): string
    {
        return <<<'HTML'
        <!--
          Component: announcement
          Fields: badge, title, body, cta, cta_url
          Layout: centered callout-style announcement with badge + CTA
        -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <span class="mgf-badge mgf-badge-accent" data-field="badge">Coming soon</span>
            <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Initial outline</h2>
            <p class="mgf-subtitle mgf-mt-md" data-field="body">
              This project is in scaffolded state. Slides will be added as content is developed.
            </p>
            <div class="mgf-mt-lg">
              <a class="mgf-cta-solid" href="#" data-field="cta_url" data-label-field="cta">Get notified</a>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    // ── Website archetype — scrollable single-page site ─────────────────

    private function websiteContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Marketing website for a single product. One continuous scrollable
        page. Each slide-NN.html is a section in the page (hero, features,
        stats, testimonials, pricing, FAQ, CTA, contact), not a viewport
        in a deck.

        ## Audience
        Prospects and customers evaluating the product. The page must
        communicate value in 5 seconds and convert with a clear CTA.

        ## Brand voice
        Confident, specific, outcome-focused. Lead with benefits, support
        with numbers, close with a CTA.

        ## Visual constraints
        - Palette: near-black surface + bright accent + warm white text
        - Sections are full-width bands — never fixed slide canvases
        - One CTA per section, max
        - No `mgf-slide-number` (scrollable pages don't have a counter)

        ## Sections (in order)
        1. Hero — headline + sub + primary CTA + secondary link
        2. Features — 3-up or 4-up grid of value props
        3. Stats — numbers that build credibility
        4. Testimonial — single large quote with attribution
        5. Pricing — 3-tier comparison
        6. FAQ — 4-6 question/answer pairs
        7. Closing CTA — repeat the main CTA
        8. Contact — form / email / phone
        MD;
    }

    private function websiteStyleCss(): string
    {
        // Same token shape as deck archetypes, with slightly tighter
        // type and a wider spacing scale suited to full-width sections.
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #0A0E1A;
          --mgf-color-surface:       #111726;
          --mgf-color-surface-2:     #1A2238;
          --mgf-color-border:        #1F2940;
          --mgf-color-border-strong: #2E3A5A;
          --mgf-color-text-primary:  #F4F6FA;
          --mgf-color-text-secondary:#94A3B8;
          --mgf-color-text-inverse:  #0A0E1A;
          --mgf-color-accent:        #22D3EE;
          --mgf-color-accent-soft:   #0E2A3A;
          --mgf-color-accent-2:      #A78BFA;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;
          --mgf-text-xs:   0.8125rem;
          --mgf-text-sm:   0.9375rem;
          --mgf-text-base: 1.0625rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  4.5rem;
          --mgf-weight-normal: 400;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.15;
          --mgf-leading-normal: 1.6;
          --mgf-leading-loose:  1.8;
          --mgf-tracking-tight:  -0.03em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.08em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 5rem;
          --mgf-space-24: 8rem;

          --mgf-radius-sm: 6px;
          --mgf-radius-md: 10px;
          --mgf-radius-lg: 18px;
          --mgf-radius-xl: 28px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function websiteLayoutHtml(): string
    {
        // The editor's `useAssemblePreview.ts` substitutes `{{slides}}`
        // with the concatenated bodies of every slide-NN.html. This
        // wrapper provides the outer page chrome (top nav, footer) and
        // the sectioning container. Data placeholders like `{{title}}`
        // are resolved from data.json.
        return <<<'HTML'
        <!--
          Website layout wrapper. Concatenated slide bodies slot into
          {{slides}}. Page chrome (nav + footer) is part of the wrapper
          so each section can stay focused on its content.
        -->
        <div class="mgf-website">
          <header class="mgf-website-nav">
            <a class="mgf-website-brand" href="#top" data-field="brand">{{title}}</a>
            <nav class="mgf-website-links">
              <a href="#features">Features</a>
              <a href="#pricing">Pricing</a>
              <a href="#faq">FAQ</a>
              <a class="mgf-cta-solid" href="#cta" data-field="nav_cta">Get started</a>
            </nav>
          </header>
          <main id="top">
            {{slides}}
          </main>
          <footer class="mgf-website-footer">
            <p class="mgf-caption" data-field="footer">© 2026 {{title}} · Built with MGF</p>
          </footer>
        </div>
        HTML;
    }

    private function websiteDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'website',
                'format' => 'scrollable',
                'total_slides' => 8,
                'components_used' => [
                    'website-hero', 'features', 'stats',
                    'testimonial', 'pricing', 'faq',
                    'website-cta', 'website-contact',
                ],
            ],
            // Top-level scalars resolve `{{title}}`, `{{brand}}`, etc.
            // across layout.html. The renderer flattens these into
            // `--content-*` CSS variables and substitutes `{{tokens}}`.
            'title'   => $owner->name,
            'tagline' => $owner->description,
            'brand'   => $owner->name,
            'footer'  => '© 2026 '.$owner->name.' · Built with MGF',
            'nav_cta' => 'Get started',
            'slides'  => [
                ['id' => 1, 'component' => 'website-hero', 'data' => [
                    'eyebrow'  => 'Now in public beta',
                    'title'    => 'Ship Faster. Sleep More.',
                    'subtitle' => 'The single layer that connects your tools, your team, and your deadlines. One onboarding. Zero context switching.',
                    'primary_cta'     => 'Start free trial',
                    'primary_cta_url' => '#cta',
                    'secondary_cta'   => 'Watch the 2-min demo',
                    'secondary_cta_url' => '#features',
                ]],
                ['id' => 2, 'component' => 'features', 'data' => [
                    'title'    => 'Everything you need, nothing you do not',
                    'subtitle' => 'Built around the way teams already work — no migration, no training, no surprises.',
                    'features' => [
                        ['icon' => '⚡', 'title' => 'Instant setup',   'desc' => 'Connect your tools in 4 minutes. We surface the work immediately.'],
                        ['icon' => '🔗', 'title' => 'Unified routing', 'desc' => 'Tasks flow between apps without copy-paste or double entry.'],
                        ['icon' => '📊', 'title' => 'Live visibility', 'desc' => 'See blockers before they hit your sprint review.'],
                        ['icon' => '🛡️', 'title' => 'SOC2 ready',      'desc' => 'Audit trails, SSO, and role-based access from day one.'],
                    ],
                ]],
                ['id' => 3, 'component' => 'stats', 'data' => [
                    'title' => 'Teams already shipping with us',
                    'stats' => [
                        ['value' => '340',  'label' => 'Teams active'],
                        ['value' => '4.1M', 'label' => 'Tasks routed'],
                        ['value' => '92%',  'label' => 'Renewal rate'],
                        ['value' => '11h',  'label' => 'Saved per week'],
                    ],
                    'caption' => 'Aggregated across all customer accounts, Q2 2026.',
                ]],
                ['id' => 4, 'component' => 'testimonial', 'data' => [
                    'quote'   => 'We replaced four tools with this. The team adopted it in a week.',
                    'author'  => 'Priya Nair',
                    'role'    => 'Head of Engineering',
                    'company' => 'Helio',
                    'avatar'  => '',
                ]],
                ['id' => 5, 'component' => 'pricing', 'data' => [
                    'title' => 'Simple pricing. Scales with you.',
                    'plans' => [
                        ['name' => 'Starter',    'price' => '$0',  'period' => 'forever',  'features' => ['Up to 5 seats', 'Core integrations'], 'cta' => 'Start free'],
                        ['name' => 'Team',       'price' => '$12', 'period' => 'seat / mo', 'features' => ['Up to 50 seats', 'AI features', 'Analytics'], 'cta' => 'Start trial'],
                        ['name' => 'Enterprise', 'price' => 'Custom', 'period' => '',          'features' => ['Unlimited seats', 'SSO', 'SLA', 'Dedicated CSM'], 'cta' => 'Talk to sales'],
                    ],
                ]],
                ['id' => 6, 'component' => 'faq', 'data' => [
                    'title' => 'Frequently asked',
                    'items' => [
                        ['q' => 'How long does setup take?', 'a' => 'Most teams complete the integration in under ten minutes.'],
                        ['q' => 'Do you support SSO?',         'a' => 'Yes — SAML, OIDC, and Google Workspace out of the box.'],
                        ['q' => 'Can I export my data?',       'a' => 'Anytime, in CSV or JSON. No lock-in.'],
                        ['q' => 'What about compliance?',      'a' => 'SOC2 Type II, GDPR-ready, with full audit logging.'],
                        ['q' => 'Is there a free plan?',       'a' => 'Yes — Starter is free forever for up to 5 seats.'],
                        ['q' => 'How do I cancel?',            'a' => 'One click in settings. We will not ask why.'],
                    ],
                ]],
                ['id' => 7, 'component' => 'website-cta', 'data' => [
                    'eyebrow' => 'Ready when you are',
                    'title'   => 'Stop juggling tools. Start shipping.',
                    'body'    => 'Free 14-day trial. No credit card. Onboarding included for teams of 5+.',
                    'cta'     => 'Start your trial',
                    'cta_url' => '#cta',
                ]],
                ['id' => 8, 'component' => 'website-contact', 'data' => [
                    'title'   => 'Talk to us',
                    'subtitle' => 'Email, call, or book a 15-minute walkthrough. We reply within one business day.',
                    'email'   => 'hello@acme.io',
                    'phone'   => '+1 (415) 555-0142',
                    'address' => '548 Market St, San Francisco, CA',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function slideWebsiteHero(): string
    {
        return <<<'HTML'
        <!--
          Component: website-hero
          Fields: eyebrow, title, subtitle, primary_cta, primary_cta_url,
                  secondary_cta, secondary_cta_url
          Layout: full-width hero band — eyebrow + headline + sub + 2 CTAs
        -->
        <section class="mgf-website-hero" id="hero">
          <p class="mgf-eyebrow" data-field="eyebrow">Now in public beta</p>
          <h1 class="mgf-website-hero-title" data-field="title">Ship Faster. Sleep More.</h1>
          <p class="mgf-website-hero-sub" data-field="subtitle">One line of subhead context, max 24 words.</p>
          <div class="mgf-website-hero-ctas">
            <a class="mgf-cta-solid mgf-cta-lg" href="#" data-field="primary_cta_url" data-label-field="primary_cta">Primary CTA</a>
            <a class="mgf-cta" href="#" data-field="secondary_cta_url" data-label-field="secondary_cta">Secondary link →</a>
          </div>
        </section>
        HTML;
    }

    private function slideWebsiteFeatures(): string
    {
        return <<<'HTML'
        <!--
          Component: features (website variant — 4-up grid)
          Fields: title, subtitle, features[]{icon, title, desc}
          Layout: section header + auto-fit grid of feature cards
        -->
        <section class="mgf-website-section" id="features">
          <header class="mgf-website-section-header">
            <p class="mgf-eyebrow">Features</p>
            <h2 class="mgf-website-section-title" data-field="title">Everything you need</h2>
            <p class="mgf-website-section-sub" data-field="subtitle">One line of framing.</p>
          </header>
          <div class="mgf-grid-4" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">⚡</div>
              <p class="mgf-feature-title" data-field="title">Pillar 1</p>
              <p class="mgf-feature-desc" data-field="desc">Description.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🔗</div>
              <p class="mgf-feature-title" data-field="title">Pillar 2</p>
              <p class="mgf-feature-desc" data-field="desc">Description.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">📊</div>
              <p class="mgf-feature-title" data-field="title">Pillar 3</p>
              <p class="mgf-feature-desc" data-field="desc">Description.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🛡️</div>
              <p class="mgf-feature-title" data-field="title">Pillar 4</p>
              <p class="mgf-feature-desc" data-field="desc">Description.</p>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideWebsiteStats(): string
    {
        return <<<'HTML'
        <!--
          Component: stats (website variant — 4-up on a tinted band)
          Fields: title, stats[]{value, label}, caption
          Layout: full-width accent-soft band with 4 stat cards + caption
        -->
        <section class="mgf-website-section mgf-bg-accent-soft" id="stats">
          <header class="mgf-website-section-header">
            <p class="mgf-eyebrow">By the numbers</p>
            <h2 class="mgf-website-section-title" data-field="title">Proof points</h2>
          </header>
          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">$1.2M</p>
              <p class="mgf-stat-label" data-field="label">ARR</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">340</p>
              <p class="mgf-stat-label" data-field="label">Teams</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">94%</p>
              <p class="mgf-stat-label" data-field="label">Retention</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">11h</p>
              <p class="mgf-stat-label" data-field="label">Saved / week</p>
            </div>
          </div>
          <p class="mgf-caption mgf-text-center mgf-mt-md" data-field="caption">All figures as of Q2 2026.</p>
        </section>
        HTML;
    }

    private function slideWebsiteTestimonial(): string
    {
        return <<<'HTML'
        <!--
          Component: testimonial (website variant — large centered quote)
          Fields: quote, author, role, company, avatar
          Layout: large quote mark + italic body + avatar + attribution row
        -->
        <section class="mgf-website-section" id="testimonial">
          <div class="mgf-website-testimonial">
            <span class="mgf-quote-mark" aria-hidden="true">"</span>
            <p class="mgf-quote-text mgf-text-center" data-field="quote">Quote goes here. One short paragraph that captures the value in the customer's own words.</p>
            <div class="mgf-quote-author mgf-flex-center">
              <div class="mgf-avatar-lg"><img data-field="avatar" src="" alt=""/></div>
              <div>
                <p class="mgf-quote-name" data-field="author">Author name</p>
                <p class="mgf-quote-title" data-field="role">Role</p>
                <p class="mgf-quote-title" data-field="company">Company</p>
              </div>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideWebsitePricing(): string
    {
        return <<<'HTML'
        <!--
          Component: pricing (website variant — 3-tier, middle tier featured)
          Fields: title, plans[]{name, price, period, features[], cta}
          Layout: 3 columns; middle plan highlighted with accent border
        -->
        <section class="mgf-website-section" id="pricing">
          <header class="mgf-website-section-header">
            <p class="mgf-eyebrow">Pricing</p>
            <h2 class="mgf-website-section-title" data-field="title">Plans</h2>
          </header>
          <div class="mgf-grid-3 mgf-mt-lg" data-field="plans">
            <div class="mgf-card">
              <p class="mgf-card-label" data-field="name">Starter</p>
              <p class="mgf-price" data-field="price">$0</p>
              <p class="mgf-price-period" data-field="period">forever</p>
              <ul class="mgf-list mgf-mt-md">
                <li data-field="features">Feature 1</li>
                <li data-field="features">Feature 2</li>
              </ul>
              <a class="mgf-cta-solid mgf-mt-md" href="#" data-field="cta_url" data-label-field="cta">Start free</a>
            </div>
            <div class="mgf-card mgf-card-accent">
              <p class="mgf-card-label" data-field="name">Team</p>
              <p class="mgf-price" data-field="price">$12</p>
              <p class="mgf-price-period" data-field="period">seat / mo</p>
              <ul class="mgf-list mgf-mt-md">
                <li data-field="features">Feature 1</li>
                <li data-field="features">Feature 2</li>
                <li data-field="features">Feature 3</li>
              </ul>
              <a class="mgf-cta-solid mgf-mt-md" href="#" data-field="cta_url" data-label-field="cta">Start trial</a>
            </div>
            <div class="mgf-card">
              <p class="mgf-card-label" data-field="name">Enterprise</p>
              <p class="mgf-price" data-field="price">Custom</p>
              <p class="mgf-price-period" data-field="period"></p>
              <ul class="mgf-list mgf-mt-md">
                <li data-field="features">SSO</li>
                <li data-field="features">SLA</li>
                <li data-field="features">Dedicated CSM</li>
              </ul>
              <a class="mgf-cta-solid mgf-mt-md" href="#" data-field="cta_url" data-label-field="cta">Talk to sales</a>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideWebsiteFaq(): string
    {
        return <<<'HTML'
        <!--
          Component: faq (website variant — single-column list)
          Fields: title, items[]{q, a}
          Layout: narrow centered column with divider-separated rows
        -->
        <section class="mgf-website-section" id="faq">
          <header class="mgf-website-section-header">
            <p class="mgf-eyebrow">FAQ</p>
            <h2 class="mgf-website-section-title" data-field="title">Frequently asked</h2>
          </header>
          <div class="mgf-website-faq" data-field="items">
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question one?</p>
              <p class="mgf-faq-a" data-field="a">Answer one.</p>
            </div>
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question two?</p>
              <p class="mgf-faq-a" data-field="a">Answer two.</p>
            </div>
            <div class="mgf-faq-item">
              <p class="mgf-faq-q" data-field="q">Question three?</p>
              <p class="mgf-faq-a" data-field="a">Answer three.</p>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideWebsiteCta(): string
    {
        return <<<'HTML'
        <!--
          Component: website-cta (closing call-to-action band)
          Fields: eyebrow, title, body, cta, cta_url
          Layout: centered band on accent-soft background with primary CTA
        -->
        <section class="mgf-website-section mgf-bg-accent-soft" id="cta">
          <div class="mgf-website-cta">
            <p class="mgf-eyebrow" data-field="eyebrow">Ready when you are</p>
            <h2 class="mgf-website-cta-title" data-field="title">Stop juggling tools.</h2>
            <p class="mgf-website-cta-body" data-field="body">One short paragraph framing the offer — max 18 words.</p>
            <a class="mgf-cta-solid mgf-cta-lg mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">Primary CTA</a>
          </div>
        </section>
        HTML;
    }

    private function slideWebsiteContact(): string
    {
        return <<<'HTML'
        <!--
          Component: website-contact (final section — email, phone, address)
          Fields: title, subtitle, email, phone, address
          Layout: 3-up contact card row
        -->
        <section class="mgf-website-section" id="contact">
          <header class="mgf-website-section-header">
            <p class="mgf-eyebrow">Contact</p>
            <h2 class="mgf-website-section-title" data-field="title">Talk to us</h2>
            <p class="mgf-website-section-sub" data-field="subtitle">One short line, max 18 words.</p>
          </header>
          <div class="mgf-grid-3 mgf-mt-lg">
            <div class="mgf-card mgf-text-center">
              <p class="mgf-card-label">Email</p>
              <p class="mgf-body mgf-mt-sm" data-field="email">hello@acme.io</p>
            </div>
            <div class="mgf-card mgf-text-center">
              <p class="mgf-card-label">Phone</p>
              <p class="mgf-body mgf-mt-sm" data-field="phone">+1 (415) 555-0142</p>
            </div>
            <div class="mgf-card mgf-text-center">
              <p class="mgf-card-label">Address</p>
              <p class="mgf-body mgf-mt-sm" data-field="address">548 Market St, San Francisco, CA</p>
            </div>
          </div>
        </section>
        HTML;
    }

    // ====================================================================
    //  Arabic pitch archetype (RTL, 8 slides, Cairo + Noto Naskh Arabic)
    // ====================================================================

    /**
     * Arabic pitch archetype — investor deck for GCC/MENA fintech.
     * Locale: ar. Direction: rtl. Distinctive palette: dark navy +
     * cyan accent. Distinctive typography: Cairo + Noto Naskh Arabic.
     *
     * Mirrors the pitch archetype's structure but localizes everything:
     * 8 slides (vs. the LTR pitch's 10), RTL-aware markup (the
     *   `dir="rtl"` attribute is set on the project's row in
     *   ProjectSeeder, not on individual slides — slides are locale-
     *   agnostic and inherit direction from the project).
     *
     * The trait intentionally reuses the existing mgf-* class
     * contract. The frontend's RTL stylesheet (see frontend's
     * `src/styles/mgf.css` direction:rtl block) mirrors grid and
     * padding via `direction: rtl` inheritance — no separate `mgf-rtl-*`
     * class namespace exists.
     */
    protected function arabicPitchFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->arabicPitchContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('arabic-pitch')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->arabicPitchStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->arabicPitchDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideArabicCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideArabicProblem()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideArabicSolution()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideArabicStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideArabicFeatures()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideArabicTraction()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slideArabicAsk()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideArabicClosing()],
        ];
    }

    private function arabicPitchContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        جولة استثمارية (السلسلة أ) لشركة تقنية مالية في الخليج.

        ## الفئة المستهدفة
        مستثمرون مؤسسيون في قطاع التكنولوجيا المالية بمنطقة الشرق الأوسط وشمال أفريقيا.

        ## نبرة العلامة التجارية
        واثق، مختصر، يعتمد على الأرقام.

        ## القيود البصرية
        - لوحة الألوان: كحلي غامق + لمسة سيان + نص فاتح
        - الحد الأقصى لطول العنوان: 7 كلمات
        - الحد الأقصى لنص الشريحة: 40 كلمة
        - اتجاه القراءة: من اليمين إلى اليسار (RTL)
        - الخط: Cairo مع Noto Naskh Arabic كاحتياطي
        MD;
    }

    private function arabicPitchStyleCss(): string
    {
        return <<<'CSS'
        /* style.css — Arabic pitch (RTL)
           Dark navy palette + cyan accent. Cairo is the primary
           Arabic-friendly display family; Noto Naskh Arabic is
           its Arabic-script fallback. The frontend's RTL stylesheet
           applies `direction: rtl` at the deck root, so grid
           columns, padding, and slide numbers mirror automatically —
           no RTL-specific class namespace. */
        :root {
          --mgf-color-bg:            #0b0f17;
          --mgf-color-surface:       #0f1218;
          --mgf-color-surface-2:     #1a2238;
          --mgf-color-border:        rgba(255, 255, 255, 0.08);
          --mgf-color-border-strong: #1f2940;
          --mgf-color-text-primary:  #f4f6fa;
          --mgf-color-text-secondary:#94a3b8;
          --mgf-color-text-inverse:  #0a0e1a;
          --mgf-color-accent:        #22d3ee;
          --mgf-color-accent-soft:   rgba(34, 211, 238, 0.12);
          --mgf-color-accent-2:      #a78bfa;

          --mgf-font-display: 'Cairo', 'Noto Naskh Arabic', Tahoma, sans-serif;
          --mgf-font-body:    'Cairo', 'Noto Naskh Arabic', Tahoma, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.9375rem;
          --mgf-text-base: 1.0625rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5rem;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.15;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.02em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.08em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 10px;
          --mgf-radius-lg: 18px;
          --mgf-radius-xl: 28px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function arabicPitchDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'ar',
                'direction' => 'rtl',
                'total_slides' => 8,
                'components_used' => [
                    'cover', 'problem', 'solution', 'stats',
                    'features', 'traction', 'ask', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title'    => $owner->name,
                    'subtitle' => $owner->description,
                    'author'   => 'فريق '.$owner->name,
                    'date'     => 'السلسلة أ — 2026',
                ]],
                ['id' => 2, 'component' => 'problem', 'data' => [
                    'eyebrow' => 'المشكلة',
                    'title'   => 'شركات صغيرة محرومة من السيولة',
                    'body'    => '70٪ من الشركات الصغيرة والمتوسطة في المنطقة تُرفض طلبات التمويل التقليدية بسبب نقص الضمانات وبطء الإجراءات.',
                    'points'  => [
                        'متوسط وقت الموافقة 45 يوماً',
                        'فجوة تمويلية تتجاوز 220 مليار دولار',
                        'معدل رفض يصل إلى 78٪',
                    ],
                ]],
                ['id' => 3, 'component' => 'solution', 'data' => [
                    'eyebrow' => 'الحل',
                    'title'   => 'قرارات ائتمانية في 60 ثانية',
                    'body'    => 'منصة ذكاء اصطناعي تربط الشركات الصغيرة بمقرضين مؤسسيين، وتُصدر قراراً مبدئياً في دقيقة واحدة.',
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'eyebrow' => 'الأرقام',
                    'title'   => 'نمو قابل للقياس',
                    'stats'   => [
                        ['value' => '1.2 مليار', 'label' => 'ريال حجم التمويل المعالج'],
                        ['value' => '320',       'label' => 'عميل نشط من الشركات'],
                        ['value' => '60 ثانية',  'label' => 'متوسط زمن القرار'],
                        ['value' => '97٪',       'label' => 'نسبة السداد في الوقت'],
                    ],
                    'caption' => 'حتى الربع الرابع 2025',
                ]],
                ['id' => 5, 'component' => 'features', 'data' => [
                    'eyebrow'  => 'الميزات',
                    'title'    => 'كل ما تحتاجه الشركة في مكان واحد',
                    'features' => [
                        ['icon' => '⚡', 'title' => 'قرار سريع',    'desc' => 'ذكاء اصطناعي يقرأ الفاتورة ويُصدر قراراً مبدئياً في 60 ثانية.'],
                        ['icon' => '🔒', 'title' => 'تقييم دقيق',    'desc' => 'نماذج مدربة على 4 ملايين نقطة بيانات إقليمية.'],
                        ['icon' => '🤝', 'title' => 'شركاء موثوقون', 'desc' => 'شبكة من 18 ممولاً مؤسسياً معتمداً.'],
                    ],
                ]],
                ['id' => 6, 'component' => 'traction', 'data' => [
                    'eyebrow' => 'الزخم',
                    'title'   => 'نمو 8 أضعاف خلال 12 شهراً',
                    'body'    => 'من 4 ملايين ريال في الربع الأول إلى 32 مليون ريال في الربع الأخير، مع توسع في 6 أسواق جديدة.',
                ]],
                ['id' => 7, 'component' => 'ask', 'data' => [
                    'eyebrow' => 'الطلب',
                    'title'   => 'نجمع 40 مليون ريال',
                    'body'    => 'لتوسيع المنتجات في الإمارات والمملكة، وبناء فريق الهندسة في القاهرة.',
                ]],
                ['id' => 8, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'تواصل',
                    'title'   => 'لنبنِ معاً مستقبل التمويل',
                    'cta'     => 'hello@nimla.sa',
                    'footer'  => $owner->name.' — السلسلة أ — 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function slideArabicCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (RTL). Fields: title, subtitle, author, date. -->
        <section class="mgf-slide" dir="rtl">
          <p class="mgf-eyebrow" data-field="author">فريق المشروع</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-title-xl mgf-mt-md" data-field="title">اسم المشروع</h1>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">وصف المشروع في سطر واحد.</p>
          <div class="mgf-flex mgf-gap-md mgf-mt-lg">
            <p class="mgf-label" data-field="date">السلسلة أ — 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">٠١</p>
        </section>
        HTML;
    }

    private function slideArabicProblem(): string
    {
        return <<<'HTML'
        <!-- Component: problem (RTL). Fields: eyebrow, title, body, points[]. -->
        <section class="mgf-slide" dir="rtl">
          <p class="mgf-eyebrow" data-field="eyebrow">المشكلة</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">عنوان المشكلة</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">فقرة واحدة تصف الألم. الحد الأقصى 40 كلمة.</p>
          <ul class="mgf-list mgf-mt-lg" data-field="points">
            <li>النقطة الأولى الداعمة</li>
            <li>النقطة الثانية الداعمة</li>
            <li>النقطة الثالثة الداعمة</li>
          </ul>
          <p class="mgf-slide-number" data-field="id">٠٢</p>
        </section>
        HTML;
    }

    private function slideArabicSolution(): string
    {
        return <<<'HTML'
        <!-- Component: solution (RTL, single-column variant).
             Fields: eyebrow, title, body. -->
        <section class="mgf-slide" dir="rtl">
          <p class="mgf-eyebrow" data-field="eyebrow">الحل</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">عنوان الحل</h2>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">سطر تأطير للحل.</p>
          <p class="mgf-body mgf-mt-lg" data-field="body">فقرة تشرح كيف يحل المنتج المشكلة. حتى 40 كلمة.</p>
          <p class="mgf-slide-number" data-field="id">٠٣</p>
        </section>
        HTML;
    }

    private function slideArabicStats(): string
    {
        return <<<'HTML'
        <!-- Component: stats (RTL, 4-up). Fields: eyebrow, title, stats[], caption. -->
        <section class="mgf-slide" dir="rtl">
          <p class="mgf-eyebrow" data-field="eyebrow">الأرقام</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">نقاط القوة</h2>
          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">1.2 مليار</p>
              <p class="mgf-stat-label" data-field="label">ريال حجم التمويل</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">320</p>
              <p class="mgf-stat-label" data-field="label">عميل نشط</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">60 ثانية</p>
              <p class="mgf-stat-label" data-field="label">زمن القرار</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">97٪</p>
              <p class="mgf-stat-label" data-field="label">نسبة السداد</p>
            </div>
          </div>
          <p class="mgf-caption mgf-mt-md" data-field="caption">حتى الربع الرابع 2025</p>
          <p class="mgf-slide-number" data-field="id">٠٤</p>
        </section>
        HTML;
    }

    private function slideArabicFeatures(): string
    {
        return <<<'HTML'
        <!-- Component: features (RTL, 3-up). Fields: eyebrow, title, features[]. -->
        <section class="mgf-slide" dir="rtl">
          <p class="mgf-eyebrow" data-field="eyebrow">الميزات</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">ثلاث ركائز</h2>
          <div class="mgf-grid-3 mgf-mt-lg" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">⚡</div>
              <p class="mgf-feature-title" data-field="title">قرار سريع</p>
              <p class="mgf-feature-desc" data-field="desc">ذكاء اصطناعي في 60 ثانية.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🔒</div>
              <p class="mgf-feature-title" data-field="title">تقييم دقيق</p>
              <p class="mgf-feature-desc" data-field="desc">نماذج مدربة على بيانات إقليمية.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🤝</div>
              <p class="mgf-feature-title" data-field="title">شركاء موثوقون</p>
              <p class="mgf-feature-desc" data-field="desc">18 ممولاً مؤسسياً.</p>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">٠٥</p>
        </section>
        HTML;
    }

    private function slideArabicTraction(): string
    {
        return <<<'HTML'
        <!-- Component: traction (RTL, single-column narrative).
             Fields: eyebrow, title, body. -->
        <section class="mgf-slide" dir="rtl">
          <p class="mgf-eyebrow" data-field="eyebrow">الزخم</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">نمو 8 أضعاف</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">
            من 4 ملايين ريال في الربع الأول إلى 32 مليون ريال في الربع الأخير،
            مع توسع في 6 أسواق جديدة ونمو 38٪ في معدل الاحتفاظ.
          </p>
          <p class="mgf-slide-number" data-field="id">٠٦</p>
        </section>
        HTML;
    }

    private function slideArabicAsk(): string
    {
        return <<<'HTML'
        <!-- Component: ask (RTL, centered).
             Fields: eyebrow, title, body. -->
        <section class="mgf-slide mgf-flex mgf-flex-center" dir="rtl">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="eyebrow">الطلب</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">نجمع 40 مليون ريال</h2>
            <p class="mgf-subtitle mgf-mt-md" data-field="body">فقرة توضح كيف سيُستخدم التمويل.</p>
            <p class="mgf-slide-number" data-field="id">٠٧</p>
          </div>
        </section>
        HTML;
    }

    private function slideArabicClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (RTL, centered CTA).
             Fields: eyebrow, title, cta, footer. -->
        <section class="mgf-slide mgf-flex mgf-flex-center" dir="rtl">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">تواصل</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">لنبنِ معاً</h2>
            <a class="mgf-cta-solid mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">hello@nimla.sa</a>
            <p class="mgf-caption mgf-mt-md" data-field="footer">السلسلة أ — 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">٠٨</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Infographic archetype (6 slides, editorial, cream + copper + serif)
    // ====================================================================

    /**
     * Infographic archetype — a 6-slide report-style deck.
     *
     * Distinct from the pitch archetype in three ways:
     * - Editorial typography (Playfair Display headlines, Source Serif
     *   body) instead of sans.
     * - Warm cream paper background + copper accent instead of dark
     *   navy + electric blue.
     * - Output target is `infographic-deck` (data-heavy, less marketing
     *   language) instead of `presentation-deck`.
     *
     * 6 slides: cover · by-the-numbers · where · outcomes · finance ·
     * thanks. Reuses the existing `cover`, `stats`, and `closing`
     * slide bodies from the LTR pitch/summary — that is a feature,
     * not an omission: the renderer maps data to the same component
     * shapes across archetypes.
     */
    protected function infographicFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->infographicContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('infographic')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->infographicStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->infographicDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideStatsFourUp()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideInfographicNarrative()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideInfographicFinance()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideClosing()],
        ];
    }

    private function infographicContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Annual / impact / explainer report. Editorial tone, evidence-led.

        ## Audience
        Readers who need the numbers and the story without reading a
        full report.

        ## Brand voice
        Measured, transparent, citation-friendly. Always cite sources
        in captions.

        ## Visual constraints
        - Palette: paper-cream + deep navy ink + copper accent
        - Typography: Playfair Display headlines, Source Serif body
        - Tables and bullet lists preferred over dense prose
        - One core idea per slide
        MD;
    }

    private function infographicStyleCss(): string
    {
        return <<<'CSS'
        /* style.css — Infographic archetype.
           Editorial / print-feel: paper-cream background, deep navy
           ink, single warm copper accent. Serif type everywhere.
           Inspired by high-quality annual reports. */
        :root {
          --mgf-color-bg:            #f6f1e7;
          --mgf-color-surface:       #ffffff;
          --mgf-color-surface-2:     #ede5d3;
          --mgf-color-border:        rgba(14, 26, 44, 0.10);
          --mgf-color-border-strong: #0e1a2c;
          --mgf-color-text-primary:  #0e1a2c;
          --mgf-color-text-secondary:#4a5567;
          --mgf-color-text-inverse:  #ffffff;
          --mgf-color-accent:        #b46a3a;
          --mgf-color-accent-soft:   rgba(180, 106, 58, 0.10);
          --mgf-color-accent-2:      #0e1a2c;

          --mgf-font-display: 'Playfair Display', Georgia, serif;
          --mgf-font-body:    'Source Serif 4', Georgia, serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.5rem;
          --mgf-text-2xl:  2rem;
          --mgf-text-3xl:  2.75rem;
          --mgf-text-4xl:  4rem;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.2;
          --mgf-leading-normal: 1.6;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.01em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.04em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 2px;
          --mgf-radius-md: 4px;
          --mgf-radius-lg: 8px;
          --mgf-radius-xl: 12px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 88px;
          --mgf-slide-pad-y: 64px;

          --mgf-accent-line: 4px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function infographicDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'infographic-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 6,
                'components_used' => [
                    'cover', 'stats', 'narrative', 'stats',
                    'infographic-finance', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title'    => $owner->name,
                    'subtitle' => $owner->description,
                    'author'   => $owner->name.' team',
                    'date'     => '2026 impact report',
                ]],
                ['id' => 2, 'component' => 'stats', 'data' => [
                    'eyebrow' => 'By the numbers',
                    'title'   => 'What 2025 looked like',
                    'stats'   => [
                        ['value' => '1,240', 'label' => 'scholarships funded'],
                        ['value' => '37',    'label' => 'partner schools'],
                        ['value' => '$4.8M', 'label' => 'disbursed in grants'],
                        ['value' => '92%',   'label' => 'students still enrolled'],
                    ],
                    'caption' => 'Aggregated across all 37 partner schools, 2025 cohort.',
                ]],
                ['id' => 3, 'component' => 'narrative', 'data' => [
                    'eyebrow' => 'Where we work',
                    'title'   => '37 schools across 4 regions',
                    'body'    => 'Atlas concentrates on the lowest-decile school districts in the Levant and North Africa, where one scholarship moves the needle for the entire cohort.',
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'eyebrow' => 'Outcomes',
                    'title'   => 'The numbers that matter most',
                    'stats'   => [
                        ['value' => '78%',  'label' => 'finish secondary school'],
                        ['value' => '31%',  'label' => 'go on to tertiary education'],
                        ['value' => '1.4x', 'label' => 'earnings uplift vs control'],
                    ],
                    'caption' => 'Source: 2025 longitudinal survey, n=812.',
                ]],
                ['id' => 5, 'component' => 'infographic-finance', 'data' => [
                    'eyebrow' => 'Finance',
                    'title'   => 'Every dollar accounted for',
                    'body'    => '86¢ of every donated dollar went directly to scholars. 9¢ to program support, 5¢ to fundraising and overhead.',
                ]],
                ['id' => 6, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'Thank you',
                    'title'   => 'To our 1,800 donors',
                    'body'    => 'The 2025 cohort exists because of you. Applications for 2026 open in May.',
                    'cta'     => 'Read the full report',
                    'footer'  => 'Published April 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Component: narrative — single-column editorial slide.
     *
     * Reused by the infographic archetype for "where we work" and
     * by the website archetype for long-band sections of prose.
     * Distinct from `slideInfographicFinance` only by caption
     * placement and the body-text fill.
     */
    private function slideInfographicNarrative(): string
    {
        return <<<'HTML'
        <!-- Component: narrative (editorial).
             Fields: eyebrow, title, body.
             Layout: centered eyebrow + title + 2-column body prose. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Where</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Where we work</h2>
          <p class="mgf-subtitle mgf-mt-md" data-field="body" style="max-width: 880px">
            One paragraph of editorial prose, max 60 words.
          </p>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    /**
     * Component: infographic-finance — a single-stat-block slide with
     * inline allocation bars. Distinct from stats because the layout
     * is editorial vertical, not a 4-up grid.
     */
    private function slideInfographicFinance(): string
    {
        return <<<'HTML'
        <!-- Component: infographic-finance (allocation breakdown).
             Fields: eyebrow, title, body.
             Layout: full-width editorial block, prose-only. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Finance</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Every dollar accounted for</h2>
          <p class="mgf-subtitle mgf-mt-md" data-field="body" style="max-width: 880px">
            One paragraph explaining the allocation breakdown.
          </p>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Academic-math archetype (6 slides, KaTeX-heavy)
    //  Primary purpose: verify the math.md contract end-to-end.
    //  Uses <span class="math-inline"> + <div class="math-block"> with
    //  double-escaped backslashes in data-tex (nowdoc heredoc keeps
    //  \\frac literal in the HTML output, which is what KaTeX expects).
    // ====================================================================

    protected function academicMathFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->academicMathContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('academic-math')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->academicMathStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->academicMathDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideAcademicCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideAcademicQuadratic()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideAcademicSchrodinger()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideAcademicEuler()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideAcademicCalculus()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideAcademicClosing()],
        ];
    }

    private function academicMathContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        A reference deck for verifying the KaTeX math integration.
        Every math-containing slide tests at least one inline span and
        one block-level formula so that the renderer's
        `hasMathContent(bodyHtml)` regex finds a match and the
        KaTeX asset injection fires.

        ## Audience
        Engineers reviewing the math render path; reviewers who want
        to see all common LaTeX primitives (fractions, roots, sums,
        integrals, matrices) without leaving the editor.

        ## Brand voice
        Quiet, scholarly, citation-friendly.

        ## Visual constraints
        - Palette: paper-cream + forest-green accent + charcoal ink
        - Typography: Source Serif 4 for prose, JetBrains Mono for
          inline math/equations identifiers
        - Every formula MUST be wrapped in either
          `<span class="math-inline" data-tex="..."></span>` or
          `<div class="math-block" data-tex="..."></div>`. Naked
          `\$...\$` delimiters are NOT parsed.
        - Backslashes must be doubled (`\\\\frac`) so the JSON/HTML
          round-trip leaves a single `\\` for KaTeX to parse.
        MD;
    }

    private function academicMathStyleCss(): string
    {
        return <<<'CSS'
        /* style.css — Academic / KaTeX reference archetype.
           Paper-cream background + forest-green accent + charcoal
           ink. Serif for prose, mono for code/identifiers. The
           assembler injects MATH_THEMED_CSS so .katex re-tints to
           var(--mgf-color-text-primary) automatically. */
        :root {
          --mgf-color-bg:            #f7f3e9;
          --mgf-color-surface:       #ffffff;
          --mgf-color-surface-2:     #ece4cf;
          --mgf-color-border:        rgba(26, 36, 33, 0.10);
          --mgf-color-border-strong: #1a2421;
          --mgf-color-text-primary:  #1a2421;
          --mgf-color-text-secondary:#5a665e;
          --mgf-color-text-inverse:  #ffffff;
          --mgf-color-accent:        #1f6f4a;
          --mgf-color-accent-soft:   rgba(31, 111, 74, 0.10);
          --mgf-color-accent-2:      #b67d3e;

          --mgf-font-display: 'Source Serif 4', Georgia, serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.125rem;
          --mgf-text-xl:   1.5rem;
          --mgf-text-2xl:  2rem;
          --mgf-text-3xl:  2.75rem;
          --mgf-text-4xl:  4rem;

          --mgf-weight-normal: 400;
          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;

          --mgf-leading-tight:  1.2;
          --mgf-leading-normal: 1.55;
          --mgf-leading-loose:  1.75;

          --mgf-tracking-tight:  -0.01em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.06em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 2px;
          --mgf-radius-md: 4px;
          --mgf-radius-lg: 8px;
          --mgf-radius-xl: 12px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 96px;
          --mgf-slide-pad-y: 72px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function academicMathDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 6,
                'components_used' => [
                    'cover', 'math-quadratic', 'math-schrodinger',
                    'math-euler', 'math-calculus', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'author' => 'Editorial math review',
                    'date' => 'v1.0 — August 2026',
                ]],
                ['id' => 2, 'component' => 'math-quadratic', 'data' => [
                    'eyebrow' => 'Algebra',
                    'title' => 'The quadratic formula',
                    'caption' => 'Roots of ax² + bx + c = 0',
                ]],
                ['id' => 3, 'component' => 'math-schrodinger', 'data' => [
                    'eyebrow' => 'Quantum mechanics',
                    'title' => 'Time-dependent Schrödinger equation',
                    'caption' => 'Evolution of a quantum state',
                ]],
                ['id' => 4, 'component' => 'math-euler', 'data' => [
                    'eyebrow' => 'Complex analysis',
                    'title' => "Euler's identity",
                    'caption' => 'Often called the most beautiful equation',
                ]],
                ['id' => 5, 'component' => 'math-calculus', 'data' => [
                    'eyebrow' => 'Calculus',
                    'title' => 'Derivative and integral',
                    'caption' => 'The fundamental theorem, side by side',
                ]],
                ['id' => 6, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'References',
                    'title' => 'KaTeX supported functions',
                    'body' => 'katex.org/docs/supported_functions',
                    'cta' => 'Open in editor',
                    'footer' => 'Reference deck for math render verification',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Cover slide for the academic-math archetype.
     * Includes one inline math as a teaser so the math-asset
     * injection kicks in even before the dedicated math slides.
     * Backslashes in data-tex are intentionally doubled per math.md.
     */
    private function slideAcademicCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (academic-math).
             Inline math teaser so the renderer detects math on slide 1.
             Fields: title, subtitle, author, date. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="author">Editorial math review</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h1 class="mgf-title-xl mgf-mt-md" data-field="title">KaTeX reference deck</h1>
            <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">
              Verifying inline and block math across the MGF renderer.
            </p>
            <p class="mgf-body mgf-mt-lg">
              A famous identity:
              <span class="math-inline" data-tex="e^{i\pi} + 1 = 0"></span>.
            </p>
            <p class="mgf-caption mgf-mt-md" data-field="date">v1.0 — August 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    /**
     * Quadratic formula slide — one math-block + one math-inline
     * in the caption. Tests fractions, square roots, plus/minus.
     * Backslashes in data-tex are doubled per math.md.
     */
    private function slideAcademicQuadratic(): string
    {
        return <<<'HTML'
        <!-- Component: math-quadratic.
             Tests: \\frac, \\sqrt, \\pm in a math-block + math-inline. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Algebra</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">The quadratic formula</h2>
          <div class="math-block mgf-mt-lg" data-tex="x = \\frac{-b \\pm \\sqrt{b^2 - 4ac}}{2a}"></div>
          <p class="mgf-caption mgf-text-center mgf-mt-md" data-field="caption">
            For the equation
            <span class="math-inline" data-tex="ax^2 + bx + c = 0"></span>
            with
            <span class="math-inline" data-tex="a \\neq 0"></span>.
          </p>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    /**
     * Schrödinger equation slide — large block + multiple inline
     * spans. Tests Greek letters, partial derivatives, hat operators,
     * bold vectors. Backslashes doubled per math.md.
     */
    private function slideAcademicSchrodinger(): string
    {
        return <<<'HTML'
        <!-- Component: math-schrodinger.
             Tests: Greek (\\Psi, \\hbar), \\partial, \\hat, \\nabla, \\mathbf. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Quantum mechanics</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Time-dependent Schrödinger equation</h2>
          <p class="mgf-body mgf-mt-md">
            The wave function
            <span class="math-inline" data-tex="\\Psi(\\mathbf{r}, t)"></span>
            evolves under the Hamiltonian operator
            <span class="math-inline" data-tex="\\hat{H}"></span>.
          </p>
          <div class="math-block mgf-mt-lg" data-tex="i\\hbar \\frac{\\partial}{\\partial t} \\Psi = \\hat{H} \\Psi"></div>
          <div class="math-block mgf-mt-sm" data-tex="\\hat{H} = -\\frac{\\hbar^2}{2m}\\nabla^2 + V(\\mathbf{r})"></div>
          <p class="mgf-caption mgf-mt-md" data-field="caption">Evolution of a quantum state in time.</p>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    /**
     * Euler's identity slide — single block + rich inline.
     * Tests: e^x, i, \pi, =, +, complex constant combination.
     * Backslashes doubled per math.md.
     */
    private function slideAcademicEuler(): string
    {
        return <<<'HTML'
        <!-- Component: math-euler.
             Tests: e^{...}, i, \\pi, summation of five constants. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 920px">
            <p class="mgf-eyebrow" data-field="eyebrow">Complex analysis</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Euler's identity</h2>
            <div class="math-block mgf-mt-lg" data-tex="e^{i\\pi} + 1 = 0"></div>
            <p class="mgf-body mgf-mt-md" data-field="caption">
              Five fundamental constants — additive identity
              <span class="math-inline" data-tex="0"></span>,
              multiplicative identity
              <span class="math-inline" data-tex="1"></span>,
              the base of natural log
              <span class="math-inline" data-tex="e"></span>,
              the imaginary unit
              <span class="math-inline" data-tex="i"></span>,
              and pi
              <span class="math-inline" data-tex="\\pi"></span> — in one equation.
            </p>
          </div>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    /**
     * Calculus slide — two side-by-side math-blocks (derivative +
     * integral) plus inline examples. Tests fractions, \mathrm
     * for upright roman, summation, integral signs.
     * Backslashes doubled per math.md.
     */
    private function slideAcademicCalculus(): string
    {
        return <<<'HTML'
        <!-- Component: math-calculus.
             Tests: \\frac, \\mathrm, \\sum, \\int with bounds, dx. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Calculus</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Derivative and integral</h2>
          <div class="mgf-grid-2 mgf-mt-lg">
            <div class="mgf-card">
              <p class="mgf-eyebrow">Derivative</p>
              <div class="math-block mgf-mt-md" data-tex="\\frac{\\mathrm{d}}{\\mathrm{d}x} f(x)"></div>
              <p class="mgf-caption mgf-mt-sm">
                The instantaneous rate of change of
                <span class="math-inline" data-tex="f"></span>
                with respect to
                <span class="math-inline" data-tex="x"></span>.
              </p>
            </div>
            <div class="mgf-card">
              <p class="mgf-eyebrow">Integral</p>
              <div class="math-block mgf-mt-md" data-tex="\\int_a^b f(x)\\, \\mathrm{d}x"></div>
              <p class="mgf-caption mgf-mt-sm">
                Accumulated change of
                <span class="math-inline" data-tex="f"></span>
                over
                <span class="math-inline" data-tex="[a, b]"></span>.
              </p>
            </div>
          </div>
          <div class="math-block mgf-mt-lg" data-tex="\\sum_{i=1}^{n} i = \\frac{n(n+1)}{2}"></div>
          <p class="mgf-caption mgf-text-center mgf-mt-md" data-field="caption">
            The fundamental theorem: differentiation and integration are inverses.
          </p>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    /**
     * Closing slide — matrix test in a math-block (tests \begin/\end,
     * & column separator, \\ row separator). All backslashes
     * doubled per the math.md contract.
     */
    private function slideAcademicClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (academic-math).
             Tests: \\begin{matrix} ... \\end{matrix}, & column sep,
             \\\\ row sep (two backslashes is one row break in
             LaTeX — see math.md). -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">References</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">KaTeX supported functions</h2>
          <div class="math-block mgf-mt-lg" data-tex="\\begin{matrix} a & b \\\\ c & d \\end{matrix}"></div>
          <p class="mgf-body mgf-mt-md" data-field="body">
            The render hook runs KaTeX with
            <span class="math-inline" data-tex="\\texttt{throwOnError: false}"></span>
            and
            <span class="math-inline" data-tex="\\texttt{strict: 'ignore'}"></span>.
            A broken formula shows the source text in red and adds
            <span class="math-inline" data-tex="\\texttt{data-math-error}"></span>.
          </p>
          <p class="mgf-caption mgf-mt-md" data-field="footer">
            Reference deck — katex.org/docs/supported_functions
          </p>
          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Earth-organic archetype (8 slides, climate/sustainability pitch)
    //  Sand + olive green + clay. Serif display + sans body. Distinct
    //  from the LTR pitch by its earthy palette and warm serif headings.
    // ====================================================================

    protected function earthOrganicFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->earthOrganicContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('earth-organic')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->earthOrganicStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->earthOrganicDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideEarthCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideEarthProblem()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideEarthSolution()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideEarthStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideEarthTimeline()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideEarthFeatures()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slideEarthAsk()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideEarthClosing()],
        ];
    }

    private function earthOrganicContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Climate-tech / sustainability investor deck. Warm and grounded.

        ## Audience
        Climate-focused VCs, family offices with sustainability mandates.

        ## Brand voice
        Hopeful, evidence-led, slow-paced. No greenwashing.

        ## Visual constraints
        - Palette: sand + olive + clay
        - Typography: Source Serif 4 display, Inter body
        - Numbers matter — always cite a source
        MD;
    }

    private function earthOrganicStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #f5efe1;
          --mgf-color-surface:       #ede5cf;
          --mgf-color-surface-2:     #d8cfb3;
          --mgf-color-border:        rgba(62, 53, 30, 0.12);
          --mgf-color-border-strong: #3e351e;
          --mgf-color-text-primary:  #2d2818;
          --mgf-color-text-secondary:#6b6347;
          --mgf-color-text-inverse:  #ffffff;
          --mgf-color-accent:        #5e7a3e;
          --mgf-color-accent-soft:   rgba(94, 122, 62, 0.14);
          --mgf-color-accent-2:      #b67d3e;

          --mgf-font-display: 'Source Serif 4', Georgia, serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.9375rem;
          --mgf-text-base: 1.0625rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.625rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3.25rem;
          --mgf-text-4xl:  4.5rem;

          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.18;
          --mgf-leading-normal: 1.55;
          --mgf-leading-loose:  1.75;
          --mgf-tracking-tight:  -0.015em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.06em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 10px;
          --mgf-radius-lg: 18px;
          --mgf-radius-xl: 26px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 64px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function earthOrganicDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 8,
                'components_used' => [
                    'cover', 'problem', 'solution', 'stats', 'timeline',
                    'features', 'ask', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'author' => $owner->name.' team',
                    'date' => '2026 climate round',
                ]],
                ['id' => 2, 'component' => 'problem', 'data' => [
                    'eyebrow' => 'The problem',
                    'title' => 'A gigaton a year is no longer a target',
                    'body' => 'Atmospheric CO2 crossed 425 ppm in 2024. Every additional gigaton we emit now costs more to remove than the gigaton before.',
                    'points' => [
                        '8 of the last 10 summers were the hottest on record',
                        'Direct-air-capture costs still 4× higher than they need to be',
                        'Land-based carbon sinks are saturating',
                    ],
                ]],
                ['id' => 3, 'component' => 'solution', 'data' => [
                    'eyebrow' => 'The approach',
                    'title' => 'Mineralize CO2 in the rocks it came from',
                    'body' => 'Our reactors accelerate the natural basalt-CO2 reaction by 10,000× — turning captured CO2 into stable carbonate rock in 18 months.',
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'eyebrow' => 'By the numbers',
                    'title' => 'Working at scale today',
                    'stats' => [
                        ['value' => '12,000', 'label' => 'tons / year capacity (Iceland)'],
                        ['value' => '$320',   'label' => 'cost per ton, current'],
                        ['value' => '94%',    'label' => 'verified removal'],
                        ['value' => '18 mo',  'label' => 'to permanent mineralization'],
                    ],
                    'caption' => 'Independent verification by Carbonfuture, Q2 2026.',
                ]],
                ['id' => 5, 'component' => 'timeline', 'data' => [
                    'eyebrow' => 'Roadmap',
                    'title' => 'From 12kt to 1Mt',
                    'steps' => [
                        ['date' => '2024', 'label' => 'Pilot plant, Iceland'],
                        ['date' => '2026', 'label' => 'First commercial site, Oman'],
                        ['date' => '2028', 'label' => '100kt capacity, three sites'],
                        ['date' => '2031', 'label' => '1Mt capacity, grid-scale'],
                    ],
                ]],
                ['id' => 6, 'component' => 'features', 'data' => [
                    'eyebrow' => 'Why us',
                    'title' => 'Three advantages',
                    'features' => [
                        ['icon' => '🌋', 'title' => 'Local feedstock', 'desc' => 'Each plant uses regional basalt — no global mineral logistics.'],
                        ['icon' => '⚡', 'title' => 'Renewable-only', 'desc' => 'Powered entirely by geothermal + wind, never the grid.'],
                        ['icon' => '♻️', 'title' => 'Permanent', 'desc' => 'Carbonate rock is geologically stable for 100,000+ years.'],
                    ],
                ]],
                ['id' => 7, 'component' => 'ask', 'data' => [
                    'eyebrow' => 'The round',
                    'title' => 'Raising $90M Series B',
                    'body' => 'Three sites in Oman, Kenya, and the Philippines. Operational by 2029.',
                ]],
                ['id' => 8, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'Get in touch',
                    'title' => 'The next gigaton starts with the next ton',
                    'cta' => 'hello@loamgrid.earth',
                    'footer' => $owner->name.' — Series B — 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function slideEarthCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (earth-organic). Fields: title, subtitle, author, date. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="author">Team</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project name</h1>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">One sentence about the work.</p>
          <p class="mgf-label mgf-mt-lg" data-field="date">2026 climate round</p>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    private function slideEarthProblem(): string
    {
        return <<<'HTML'
        <!-- Component: problem (earth-organic). Fields: eyebrow, title, body, points[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">The problem</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">A problem framed for climate investors</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">A paragraph that anchors urgency without catastrophizing.</p>
          <ul class="mgf-list mgf-mt-lg" data-field="points">
            <li>First supporting point</li>
            <li>Second supporting point</li>
            <li>Third supporting point</li>
          </ul>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideEarthSolution(): string
    {
        return <<<'HTML'
        <!-- Component: solution (earth-organic). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">The approach</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Approach headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph explaining the technique and why it works at scale.</p>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideEarthStats(): string
    {
        return <<<'HTML'
        <!-- Component: stats (earth-organic, 4-up). Fields: eyebrow, title, stats[], caption. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">By the numbers</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Working at scale today</h2>
          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">12,000</p>
              <p class="mgf-stat-label" data-field="label">tons / year</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">$320</p>
              <p class="mgf-stat-label" data-field="label">cost per ton</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">94%</p>
              <p class="mgf-stat-label" data-field="label">verified removal</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">18 mo</p>
              <p class="mgf-stat-label" data-field="label">to permanence</p>
            </div>
          </div>
          <p class="mgf-caption mgf-mt-md" data-field="caption">Source: independent verification, Q2 2026.</p>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    private function slideEarthTimeline(): string
    {
        return <<<'HTML'
        <!-- Component: timeline (earth-organic). Fields: eyebrow, title, steps[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Roadmap</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">From 12kt to 1Mt</h2>
          <ol class="mgf-timeline mgf-mt-lg" data-field="steps">
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">2024</p>
              <p class="mgf-body" data-field="label">Pilot plant, Iceland</p>
            </li>
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">2026</p>
              <p class="mgf-body" data-field="label">First commercial site</p>
            </li>
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">2028</p>
              <p class="mgf-body" data-field="label">100kt capacity</p>
            </li>
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">2031</p>
              <p class="mgf-body" data-field="label">1Mt capacity</p>
            </li>
          </ol>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    private function slideEarthFeatures(): string
    {
        return <<<'HTML'
        <!-- Component: features (earth-organic, 3-up). Fields: eyebrow, title, features[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Why us</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Three advantages</h2>
          <div class="mgf-grid-3 mgf-mt-lg" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🌋</div>
              <p class="mgf-feature-title" data-field="title">Local feedstock</p>
              <p class="mgf-feature-desc" data-field="desc">Regional basalt, no global mineral logistics.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">⚡</div>
              <p class="mgf-feature-title" data-field="title">Renewable-only</p>
              <p class="mgf-feature-desc" data-field="desc">Powered by geothermal + wind, never the grid.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">♻️</div>
              <p class="mgf-feature-title" data-field="title">Permanent</p>
              <p class="mgf-feature-desc" data-field="desc">Carbonate rock is geologically stable for 100,000+ years.</p>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    private function slideEarthAsk(): string
    {
        return <<<'HTML'
        <!-- Component: ask (earth-organic, centered). Fields: eyebrow, title, body. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="eyebrow">The round</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Raising $90M Series B</h2>
            <p class="mgf-subtitle mgf-mt-md" data-field="body">Use-of-funds paragraph.</p>
            <p class="mgf-slide-number" data-field="id">07</p>
          </div>
        </section>
        HTML;
    }

    private function slideEarthClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (earth-organic, centered CTA). Fields: eyebrow, title, cta, footer. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">Get in touch</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Closing line</h2>
            <a class="mgf-cta-solid mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">hello@project.earth</a>
            <p class="mgf-caption mgf-mt-md" data-field="footer">Series B — 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">08</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Neon-cyber archetype (8 slides, fintech / security pitch)
    //  Deep black + electric magenta + cyan. JetBrains Mono display +
    //  Inter body. Distinct from the LTR pitch by its cyber/terminal
    //  aesthetic and monospaced headings.
    // ====================================================================

    protected function neonCyberFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->neonCyberContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('neon-cyber')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->neonCyberStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->neonCyberDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideNeonCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideNeonProblem()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideNeonSolution()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideNeonStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideNeonFeatures()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideNeonSecurity()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slideNeonAsk()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideNeonClosing()],
        ];
    }

    private function neonCyberContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Cybersecurity / fintech-security pitch. Technical and direct.

        ## Audience
        CISOs, security-focused VCs, technical co-founders.

        ## Brand voice
        Confident, terse, zero hype. Every claim backed by a CVE or a paper.

        ## Visual constraints
        - Palette: deep black + electric magenta + cyan
        - Typography: JetBrains Mono for headings, Inter for body
        - Code blocks and terminal-style callouts welcome
        MD;
    }

    private function neonCyberStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #0a0a0f;
          --mgf-color-surface:       #11121a;
          --mgf-color-surface-2:     #1c1e2a;
          --mgf-color-border:        rgba(255, 255, 255, 0.10);
          --mgf-color-border-strong: #2a2d3e;
          --mgf-color-text-primary:  #e7eaf0;
          --mgf-color-text-secondary:#7a82a3;
          --mgf-color-text-inverse:  #0a0a0f;
          --mgf-color-accent:        #ec4899;
          --mgf-color-accent-soft:   rgba(236, 72, 153, 0.14);
          --mgf-color-accent-2:      #22d3ee;

          --mgf-font-display: 'JetBrains Mono', ui-monospace, monospace;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.625rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3rem;
          --mgf-text-4xl:  4.25rem;

          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.12;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.7;
          --mgf-tracking-tight:  -0.04em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.06em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 2px;
          --mgf-radius-md: 6px;
          --mgf-radius-lg: 12px;
          --mgf-radius-xl: 18px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 72px;
          --mgf-slide-pad-y: 56px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function neonCyberDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 8,
                'components_used' => [
                    'cover', 'problem', 'solution', 'stats',
                    'features', 'security', 'ask', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'author' => $owner->name.' security',
                    'date' => '2026 Series A',
                ]],
                ['id' => 2, 'component' => 'problem', 'data' => [
                    'eyebrow' => '> threat',
                    'title' => 'Credential-stuffing attacks scale linearly with breaches',
                    'body' => '24 billion leaked credentials are circulating on the dark web in 2026. Every SaaS login is one bot away from compromise.',
                ]],
                ['id' => 3, 'component' => 'solution', 'data' => [
                    'eyebrow' => '> solution',
                    'title' => 'Continuous identity assurance, no MFA fatigue',
                    'body' => 'Silent device-bound attestation replaces the password prompt. Users keep their flow; attackers lose the surface.',
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'eyebrow' => '> numbers',
                    'title' => 'Live deployment results',
                    'stats' => [
                        ['value' => '99.7%',  'label' => 'credential-stuffing blocked'],
                        ['value' => '0',      'label' => 'MFA prompts per session'],
                        ['value' => '<50ms',  'label' => 'avg. authentication latency'],
                        ['value' => '12 mo',  'label' => 'to SOC 2 Type II'],
                    ],
                    'caption' => 'Production data, 3.4M users across 14 enterprise customers.',
                ]],
                ['id' => 5, 'component' => 'features', 'data' => [
                    'eyebrow' => '> stack',
                    'title' => 'What we built',
                    'features' => [
                        ['icon' => '🔐', 'title' => 'Device-bound keys', 'desc' => 'FIDO2 / WebAuthn + secure enclave.'],
                        ['icon' => '🛰', 'title' => 'Risk telemetry', 'desc' => 'Per-session signals, on-device scoring.'],
                        ['icon' => '🔌', 'title' => 'Drop-in SDK',     'desc' => '5 lines of code, any web stack.'],
                    ],
                ]],
                ['id' => 6, 'component' => 'security', 'data' => [
                    'eyebrow' => '> posture',
                    'title' => 'Compliance + threat model',
                    'body' => 'SOC 2 Type II, ISO 27001, FIDO Alliance member. Threat model published; bug bounty since day one.',
                ]],
                ['id' => 7, 'component' => 'ask', 'data' => [
                    'eyebrow' => '> round',
                    'title' => 'Raising $25M Series A',
                    'body' => 'Grow enterprise security, expand to EU + APAC, double the engineering team.',
                ]],
                ['id' => 8, 'component' => 'closing', 'data' => [
                    'eyebrow' => '> contact',
                    'title' => 'Built by attackers, for defenders',
                    'cta' => 'hello@vitral.security',
                    'footer' => $owner->name.' — Series A — 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function slideNeonCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (neon-cyber). Fields: title, subtitle, author, date. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="author">Security team</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project name</h1>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">One line about the security problem solved.</p>
          <p class="mgf-label mgf-mt-lg" data-field="date">2026 Series A</p>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    private function slideNeonProblem(): string
    {
        return <<<'HTML'
        <!-- Component: problem (neon-cyber). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">&gt; threat</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Threat headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph describing the threat vector and its blast radius.</p>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideNeonSolution(): string
    {
        return <<<'HTML'
        <!-- Component: solution (neon-cyber). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">&gt; solution</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Solution headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph describing how the product mitigates the threat.</p>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideNeonStats(): string
    {
        return <<<'HTML'
        <!-- Component: stats (neon-cyber, 4-up). Fields: eyebrow, title, stats[], caption. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">&gt; numbers</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Production results</h2>
          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">99.7%</p>
              <p class="mgf-stat-label" data-field="label">attacks blocked</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">0</p>
              <p class="mgf-stat-label" data-field="label">MFA prompts</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">&lt;50ms</p>
              <p class="mgf-stat-label" data-field="label">auth latency</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">12 mo</p>
              <p class="mgf-stat-label" data-field="label">to SOC 2</p>
            </div>
          </div>
          <p class="mgf-caption mgf-mt-md" data-field="caption">Production data, 3.4M users.</p>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    private function slideNeonFeatures(): string
    {
        return <<<'HTML'
        <!-- Component: features (neon-cyber, 3-up). Fields: eyebrow, title, features[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">&gt; stack</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">What we built</h2>
          <div class="mgf-grid-3 mgf-mt-lg" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🔐</div>
              <p class="mgf-feature-title" data-field="title">Device-bound keys</p>
              <p class="mgf-feature-desc" data-field="desc">FIDO2 + secure enclave.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🛰</div>
              <p class="mgf-feature-title" data-field="title">Risk telemetry</p>
              <p class="mgf-feature-desc" data-field="desc">On-device scoring.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🔌</div>
              <p class="mgf-feature-title" data-field="title">Drop-in SDK</p>
              <p class="mgf-feature-desc" data-field="desc">5 lines of code.</p>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    private function slideNeonSecurity(): string
    {
        return <<<'HTML'
        <!-- Component: security (neon-cyber, callout block). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">&gt; posture</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Compliance + threat model</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">
            SOC 2 Type II, ISO 27001, FIDO Alliance member. Threat model published;
            bug bounty since day one.
          </p>
          <figure class="mgf-callout mgf-callout-info mgf-mt-lg">
            <p class="mgf-callout-text">
              Independent third-party audit refreshed every 90 days. Last audit
              completed March 2026 — zero open criticals.
            </p>
          </figure>
          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    private function slideNeonAsk(): string
    {
        return <<<'HTML'
        <!-- Component: ask (neon-cyber, centered). Fields: eyebrow, title, body. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="eyebrow">&gt; round</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Raising $25M Series A</h2>
            <p class="mgf-subtitle mgf-mt-md" data-field="body">Use-of-funds paragraph.</p>
            <p class="mgf-slide-number" data-field="id">07</p>
          </div>
        </section>
        HTML;
    }

    private function slideNeonClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (neon-cyber, centered CTA). Fields: eyebrow, title, cta, footer. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">&gt; contact</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Built by attackers, for defenders</h2>
            <a class="mgf-cta-solid mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">hello@project.security</a>
            <p class="mgf-caption mgf-mt-md" data-field="footer">Series A — 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">08</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Sunset-warm archetype (6 slides, consumer / lifestyle pitch)
    //  Warm peach + coral + soft indigo. Inter everywhere. Distinct
    //  from the LTR pitch by its warm gradient feel and shorter deck.
    // ====================================================================

    protected function sunsetWarmFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->sunsetWarmContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('sunset-warm')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->sunsetWarmStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->sunsetWarmDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideSunsetCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideSunsetProblem()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideSunsetSolution()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideSunsetStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideSunsetFeatures()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideSunsetClosing()],
        ];
    }

    private function sunsetWarmContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        Consumer / lifestyle product pitch. Warm, optimistic, lifestyle-led.

        ## Audience
        Pre-seed / seed-stage consumer investors. Brand-led, demographic-aware.

        ## Brand voice
        Warm, optimistic, conversational. Lead with the lifestyle, close with the unit economics.

        ## Visual constraints
        - Palette: warm peach + coral + soft indigo
        - Typography: Inter display + Inter body
        - Heavy use of personality; numbers are second
        MD;
    }

    private function sunsetWarmStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #fff7ed;
          --mgf-color-surface:       #ffffff;
          --mgf-color-surface-2:     #ffe7d4;
          --mgf-color-border:        rgba(58, 30, 12, 0.10);
          --mgf-color-border-strong: #3a1e0c;
          --mgf-color-text-primary:  #3a1e0c;
          --mgf-color-text-secondary:#7c5b48;
          --mgf-color-text-inverse:  #ffffff;
          --mgf-color-accent:        #ff6b6b;
          --mgf-color-accent-soft:   rgba(255, 107, 107, 0.12);
          --mgf-color-accent-2:      #818cf8;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.9375rem;
          --mgf-text-base: 1.0625rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5rem;

          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.12;
          --mgf-leading-normal: 1.5;
          --mgf-leading-loose:  1.7;
          --mgf-tracking-tight:  -0.025em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.06em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 8px;
          --mgf-radius-md: 14px;
          --mgf-radius-lg: 22px;
          --mgf-radius-xl: 32px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 64px;

          --mgf-accent-line: 4px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function sunsetWarmDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 6,
                'components_used' => [
                    'cover', 'problem', 'solution', 'stats', 'features', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'author' => $owner->name.' team',
                    'date' => 'Pre-seed — 2026',
                ]],
                ['id' => 2, 'component' => 'problem', 'data' => [
                    'eyebrow' => 'Today',
                    'title' => 'Hair care is one of the last un-personalized categories',
                    'body' => 'Women in the Gulf spend $480/year on hair products, and 78% say none of them quite fit their hair.',
                ]],
                ['id' => 3, 'component' => 'solution', 'data' => [
                    'eyebrow' => 'Our answer',
                    'title' => 'Personalized formulas, shipped every 60 days',
                    'body' => 'A 3-minute quiz + humidity data from your zip code = a shampoo blended for the next 8 weeks.',
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'eyebrow' => 'Numbers',
                    'title' => 'Eight months in market',
                    'stats' => [
                        ['value' => '4,200', 'label' => 'subscribers'],
                        ['value' => '$98',   'label' => 'average LTV'],
                        ['value' => '92%',   'label' => 'subscribe to month 4'],
                        ['value' => '2.6×',  'label' => 'D2C repeat rate vs category'],
                    ],
                    'caption' => 'UAE + KSA, December 2025 cohort.',
                ]],
                ['id' => 5, 'component' => 'features', 'data' => [
                    'eyebrow' => 'Why us',
                    'title' => 'Three things we do better',
                    'features' => [
                        ['icon' => '🧪', 'title' => 'Personalized blend', 'desc' => '12 base formulas × 9 additives = 108 unique SKUs.'],
                        ['icon' => '🌡', 'title' => 'Climate-aware', 'desc' => 'We factor local humidity into every shipment.'],
                        ['icon' => '📦', 'title' => 'Subscribe + forget', 'desc' => 'Refills auto-ship, pause anytime.'],
                    ],
                ]],
                ['id' => 6, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'Join us',
                    'title' => 'Hair, finally, fits',
                    'cta' => 'hello@marask.care',
                    'footer' => $owner->name.' — Pre-seed — 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function slideSunsetCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (sunset-warm). Fields: title, subtitle, author, date. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="author">Team</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project name</h1>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">One line about the product.</p>
          <p class="mgf-label mgf-mt-lg" data-field="date">Pre-seed — 2026</p>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    private function slideSunsetProblem(): string
    {
        return <<<'HTML'
        <!-- Component: problem (sunset-warm). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Today</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Problem headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph about the consumer pain point.</p>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideSunsetSolution(): string
    {
        return <<<'HTML'
        <!-- Component: solution (sunset-warm). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Our answer</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Solution headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph describing the product.</p>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideSunsetStats(): string
    {
        return <<<'HTML'
        <!-- Component: stats (sunset-warm, 4-up). Fields: eyebrow, title, stats[], caption. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Numbers</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Eight months in market</h2>
          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">4,200</p>
              <p class="mgf-stat-label" data-field="label">subscribers</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">$98</p>
              <p class="mgf-stat-label" data-field="label">avg LTV</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">92%</p>
              <p class="mgf-stat-label" data-field="label">retention</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">2.6×</p>
              <p class="mgf-stat-label" data-field="label">category repeat</p>
            </div>
          </div>
          <p class="mgf-caption mgf-mt-md" data-field="caption">UAE + KSA, December 2025 cohort.</p>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    private function slideSunsetFeatures(): string
    {
        return <<<'HTML'
        <!-- Component: features (sunset-warm, 3-up). Fields: eyebrow, title, features[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Why us</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Three things we do better</h2>
          <div class="mgf-grid-3 mgf-mt-lg" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🧪</div>
              <p class="mgf-feature-title" data-field="title">Personalized blend</p>
              <p class="mgf-feature-desc" data-field="desc">12 base formulas × 9 additives.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🌡</div>
              <p class="mgf-feature-title" data-field="title">Climate-aware</p>
              <p class="mgf-feature-desc" data-field="desc">Humidity factored into every shipment.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">📦</div>
              <p class="mgf-feature-title" data-field="title">Subscribe + forget</p>
              <p class="mgf-feature-desc" data-field="desc">Refills auto-ship, pause anytime.</p>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    private function slideSunsetClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (sunset-warm, centered CTA). Fields: eyebrow, title, cta, footer. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">Join us</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Hair, finally, fits</h2>
            <a class="mgf-cta-solid mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">hello@project.care</a>
            <p class="mgf-caption mgf-mt-md" data-field="footer">Pre-seed — 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Monochrome-editorial archetype (4 slides, haiku announcement)
    //  Pure white + near-black + single yellow accent. The shortest
    //  archetype in the suite — meant for product announcements and
    //  brand haikus, not investor decks.
    // ====================================================================

    protected function monochromeEditorialFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->monochromeEditorialContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('monochrome-editorial')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->monochromeEditorialStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->monochromeEditorialDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideMonochromeCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideMonochromeHaiku()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideMonochromeQuote()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideMonochromeClosing()],
        ];
    }

    private function monochromeEditorialContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        A four-slide announcement deck for product launches and brand
        haikus. The shortest archetype in the suite.

        ## Audience
        Internal stakeholders, customers, press.

        ## Brand voice
        Minimal. Every word earns its place.

        ## Visual constraints
        - Palette: pure white + near-black + one yellow accent
        - Typography: Inter display + Inter body
        - Max 12 words per slide body. Less is more.
        MD;
    }

    private function monochromeEditorialStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #ffffff;
          --mgf-color-surface:       #fafafa;
          --mgf-color-surface-2:     #f4f4f4;
          --mgf-color-border:        rgba(10, 10, 10, 0.10);
          --mgf-color-border-strong: #0a0a0a;
          --mgf-color-text-primary:  #0a0a0a;
          --mgf-color-text-secondary:#525252;
          --mgf-color-text-inverse:  #ffffff;
          --mgf-color-accent:        #fbbf24;
          --mgf-color-accent-soft:   rgba(251, 191, 36, 0.16);
          --mgf-color-accent-2:      #0a0a0a;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5.5rem;

          --mgf-weight-medium: 500;
          --mgf-weight-bold:   700;
          --mgf-leading-tight:  1.05;
          --mgf-leading-normal: 1.4;
          --mgf-leading-loose:  1.65;
          --mgf-tracking-tight:  -0.04em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.10em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 0px;
          --mgf-radius-md: 2px;
          --mgf-radius-lg: 4px;
          --mgf-radius-xl: 8px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 96px;
          --mgf-slide-pad-y: 80px;

          --mgf-accent-line: 6px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function monochromeEditorialDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 4,
                'components_used' => [
                    'cover', 'haiku', 'quote', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'author' => $owner->name.' studio',
                    'date' => '2026',
                ]],
                ['id' => 2, 'component' => 'haiku', 'data' => [
                    'eyebrow' => 'A haiku',
                    'title' => 'One word at a time',
                    'body' => 'Less, but better.',
                ]],
                ['id' => 3, 'component' => 'quote', 'data' => [
                    'eyebrow' => 'In the founder’s words',
                    'title' => 'A quote that anchors the launch',
                    'body' => 'We started this because the existing options were all noise. We wanted one signal.',
                ]],
                ['id' => 4, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'Launch',
                    'title' => 'Live today',
                    'cta' => 'project.studio',
                    'footer' => $owner->name.' — 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function slideMonochromeCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (monochrome-editorial). Fields: title, subtitle, author, date. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="author">Studio</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project name</h1>
            <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">One line.</p>
            <p class="mgf-label mgf-mt-lg" data-field="date">2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    private function slideMonochromeHaiku(): string
    {
        return <<<'HTML'
        <!-- Component: haiku (monochrome-editorial). Fields: eyebrow, title, body.
             A pure-typography slide: short line + medium line + short line. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">A haiku</p>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">One word at a time</h2>
            <p class="mgf-body mgf-mt-lg" data-field="body">Less, but better.</p>
          </div>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideMonochromeQuote(): string
    {
        return <<<'HTML'
        <!-- Component: quote (monochrome-editorial). Fields: eyebrow, title, body. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="eyebrow">In the founder&rsquo;s words</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-lg mgf-mt-md" data-field="title">A quote that anchors the launch</h2>
            <p class="mgf-quote-text mgf-mt-lg" data-field="body">
              &ldquo;We started this because the existing options were all
              noise. We wanted one signal.&rdquo;
            </p>
          </div>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideMonochromeClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (monochrome-editorial). Fields: eyebrow, title, cta, footer. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">Launch</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Live today</h2>
            <a class="mgf-cta-solid mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">project.studio</a>
            <p class="mgf-caption mgf-mt-md" data-field="footer">2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    // ====================================================================
    //  Vibrant-festival archetype (8 slides, event / consumer pitch)
    //  Cream + fuchsia + lime + orange — three accents plus tertiary.
    //  Bold, joyful, slightly chaotic. Distinct from the LTR pitch by
    //  its multi-accent palette and rounded generous spacing.
    // ====================================================================

    protected function vibrantFestivalFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->vibrantFestivalContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('vibrant-festival')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->vibrantFestivalStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->layoutCss16x9()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->vibrantFestivalDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideVibrantCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideVibrantProblem()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideVibrantSolution()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideVibrantStats()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideVibrantFeatures()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideVibrantTimeline()],
            ['layer' => 'slide', 'name' => 'slide-07.html', 'extension' => 'html', 'content' => $this->slideVibrantAsk()],
            ['layer' => 'slide', 'name' => 'slide-08.html', 'extension' => 'html', 'content' => $this->slideVibrantClosing()],
        ];
    }

    private function vibrantFestivalContext(): string
    {
        return <<<MD
        # Project Context

        ## Purpose
        An event / festival pitch. Loud, joyful, optimistic.

        ## Audience
        Event organizers, sponsors, city tourism boards.

        ## Brand voice
        High energy. Active verbs. Slightly chaotic on purpose.

        ## Visual constraints
        - Palette: cream + fuchsia + lime + orange (three accents)
        - Typography: Inter display + Inter body
        - Generous spacing, large radii, no quiet slides
        MD;
    }

    private function vibrantFestivalStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #fafaf9;
          --mgf-color-surface:       #ffffff;
          --mgf-color-surface-2:     #f4f4f4;
          --mgf-color-border:        rgba(20, 20, 20, 0.10);
          --mgf-color-border-strong: #18181b;
          --mgf-color-text-primary:  #18181b;
          --mgf-color-text-secondary:#52525b;
          --mgf-color-text-inverse:  #ffffff;
          --mgf-color-accent:        #e879f9;
          --mgf-color-accent-soft:   rgba(232, 121, 249, 0.14);
          --mgf-color-accent-2:      #a3e635;
          --mgf-color-accent-3:      #fb923c;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.9375rem;
          --mgf-text-base: 1.0625rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.5rem;
          --mgf-text-3xl:  3.5rem;
          --mgf-text-4xl:  5.5rem;

          --mgf-weight-medium: 500;
          --mgf-weight-bold:   800;
          --mgf-leading-tight:  1.05;
          --mgf-leading-normal: 1.4;
          --mgf-leading-loose:  1.6;
          --mgf-tracking-tight:  -0.04em;
          --mgf-tracking-normal: 0em;
          --mgf-tracking-wide:   0.06em;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 10px;
          --mgf-radius-md: 18px;
          --mgf-radius-lg: 28px;
          --mgf-radius-xl: 40px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 5px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function vibrantFestivalDataJson($owner): string
    {
        $payload = [
            '_meta' => [
                'project' => $owner->name,
                'version' => '1.0',
                'output_target' => 'presentation-deck',
                'format' => '16:9',
                'language' => 'en',
                'direction' => 'ltr',
                'total_slides' => 8,
                'components_used' => [
                    'cover', 'problem', 'solution', 'stats',
                    'features', 'timeline', 'ask', 'closing',
                ],
            ],
            'slides' => [
                ['id' => 1, 'component' => 'cover', 'data' => [
                    'title' => $owner->name,
                    'subtitle' => $owner->description,
                    'author' => $owner->name.' crew',
                    'date' => 'Summer 2026',
                ]],
                ['id' => 2, 'component' => 'problem', 'data' => [
                    'eyebrow' => 'Why now',
                    'title' => 'Cities need live moments more than ever',
                    'body' => 'After three years of small screens, audiences are willing to pay real money for one weekend of real life.',
                ]],
                ['id' => 3, 'component' => 'solution', 'data' => [
                    'eyebrow' => 'Our answer',
                    'title' => 'A three-day outdoor festival, programmed by the audience',
                    'body' => 'The lineup is crowdsourced. The schedule is shared. The merch is small-batch.',
                ]],
                ['id' => 4, 'component' => 'stats', 'data' => [
                    'eyebrow' => 'Year one',
                    'title' => 'Already oversubscribed',
                    'stats' => [
                        ['value' => '14,000', 'label' => 'tickets sold (48h)'],
                        ['value' => '92%',    'label' => 'early-bird conversion'],
                        ['value' => '180+',   'label' => 'acts programmed'],
                        ['value' => '$1.4M',  'label' => 'sponsor interest'],
                    ],
                    'caption' => 'Pre-launch numbers, July 2026.',
                ]],
                ['id' => 5, 'component' => 'features', 'data' => [
                    'eyebrow' => 'What makes it different',
                    'title' => 'Three things nobody else is doing',
                    'features' => [
                        ['icon' => '🎤', 'title' => 'Audience-curated', 'desc' => 'Voting opens 60 days out. Top acts get prime slots.'],
                        ['icon' => '🎁', 'title' => 'Small-batch merch',  'desc' => 'Eight artists, eight designs, every piece numbered.'],
                        ['icon' => '🌳', 'title' => 'Zero-waste site',    'desc' => 'No single-use cups. Compostable everything.'],
                    ],
                ]],
                ['id' => 6, 'component' => 'timeline', 'data' => [
                    'eyebrow' => 'Run of show',
                    'title' => 'The next 90 days',
                    'steps' => [
                        ['date' => 'Aug', 'label' => 'Lineup announcement'],
                        ['date' => 'Sep', 'label' => 'Early-bird tickets open'],
                        ['date' => 'Oct', 'label' => 'Site + permit signed off'],
                        ['date' => 'Nov', 'label' => 'Festival weekend'],
                    ],
                ]],
                ['id' => 7, 'component' => 'ask', 'data' => [
                    'eyebrow' => 'We need',
                    'title' => 'Closing the sponsor list',
                    'body' => 'Three remaining headline slots. Each gets a stage naming, 1,200 VIP passes, and three months of pre-roll.',
                ]],
                ['id' => 8, 'component' => 'closing', 'data' => [
                    'eyebrow' => 'See you in November',
                    'title' => 'Three days. One signal.',
                    'cta' => 'hello@summerglow.fest',
                    'footer' => $owner->name.' — Summer 2026',
                ]],
            ],
        ];

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function slideVibrantCover(): string
    {
        return <<<'HTML'
        <!-- Component: cover (vibrant-festival). Fields: title, subtitle, author, date. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="author">Crew</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-title-xl mgf-mt-md" data-field="title">Project name</h1>
          <p class="mgf-subtitle mgf-mt-md" data-field="subtitle">One line about the festival.</p>
          <p class="mgf-label mgf-mt-lg" data-field="date">Summer 2026</p>
          <p class="mgf-slide-number" data-field="id">01</p>
        </section>
        HTML;
    }

    private function slideVibrantProblem(): string
    {
        return <<<'HTML'
        <!-- Component: problem (vibrant-festival). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Why now</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Problem headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph framing the cultural moment.</p>
          <p class="mgf-slide-number" data-field="id">02</p>
        </section>
        HTML;
    }

    private function slideVibrantSolution(): string
    {
        return <<<'HTML'
        <!-- Component: solution (vibrant-festival). Fields: eyebrow, title, body. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Our answer</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Solution headline</h2>
          <p class="mgf-body mgf-mt-md" data-field="body">One paragraph describing the festival concept.</p>
          <p class="mgf-slide-number" data-field="id">03</p>
        </section>
        HTML;
    }

    private function slideVibrantStats(): string
    {
        return <<<'HTML'
        <!-- Component: stats (vibrant-festival, 4-up). Fields: eyebrow, title, stats[], caption. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Year one</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Already oversubscribed</h2>
          <div class="mgf-stat-group mgf-mt-lg" data-field="stats">
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">14,000</p>
              <p class="mgf-stat-label" data-field="label">tickets sold</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">92%</p>
              <p class="mgf-stat-label" data-field="label">conversion</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">180+</p>
              <p class="mgf-stat-label" data-field="label">acts</p>
            </div>
            <div class="mgf-card-accent">
              <p class="mgf-stat-value" data-field="value">$1.4M</p>
              <p class="mgf-stat-label" data-field="label">sponsors</p>
            </div>
          </div>
          <p class="mgf-caption mgf-mt-md" data-field="caption">Pre-launch, July 2026.</p>
          <p class="mgf-slide-number" data-field="id">04</p>
        </section>
        HTML;
    }

    private function slideVibrantFeatures(): string
    {
        return <<<'HTML'
        <!-- Component: features (vibrant-festival, 3-up). Fields: eyebrow, title, features[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">What makes it different</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">Three things nobody else is doing</h2>
          <div class="mgf-grid-3 mgf-mt-lg" data-field="features">
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🎤</div>
              <p class="mgf-feature-title" data-field="title">Audience-curated</p>
              <p class="mgf-feature-desc" data-field="desc">Voting opens 60 days out.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🎁</div>
              <p class="mgf-feature-title" data-field="title">Small-batch merch</p>
              <p class="mgf-feature-desc" data-field="desc">Eight artists, eight designs, every piece numbered.</p>
            </div>
            <div class="mgf-card">
              <div class="mgf-feature-icon" data-field="icon">🌳</div>
              <p class="mgf-feature-title" data-field="title">Zero-waste site</p>
              <p class="mgf-feature-desc" data-field="desc">No single-use cups. Compostable everything.</p>
            </div>
          </div>
          <p class="mgf-slide-number" data-field="id">05</p>
        </section>
        HTML;
    }

    private function slideVibrantTimeline(): string
    {
        return <<<'HTML'
        <!-- Component: timeline (vibrant-festival). Fields: eyebrow, title, steps[]. -->
        <section class="mgf-slide">
          <p class="mgf-eyebrow" data-field="eyebrow">Run of show</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-title-lg mgf-mt-md" data-field="title">The next 90 days</h2>
          <ol class="mgf-timeline mgf-mt-lg" data-field="steps">
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">Aug</p>
              <p class="mgf-body" data-field="label">Lineup announcement</p>
            </li>
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">Sep</p>
              <p class="mgf-body" data-field="label">Early-bird tickets open</p>
            </li>
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">Oct</p>
              <p class="mgf-body" data-field="label">Site + permit signed off</p>
            </li>
            <li class="mgf-timeline-item">
              <span class="mgf-timeline-dot"></span>
              <p class="mgf-step-number" data-field="date">Nov</p>
              <p class="mgf-body" data-field="label">Festival weekend</p>
            </li>
          </ol>
          <p class="mgf-slide-number" data-field="id">06</p>
        </section>
        HTML;
    }

    private function slideVibrantAsk(): string
    {
        return <<<'HTML'
        <!-- Component: ask (vibrant-festival, centered). Fields: eyebrow, title, body. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 880px">
            <p class="mgf-eyebrow" data-field="eyebrow">We need</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Closing the sponsor list</h2>
            <p class="mgf-subtitle mgf-mt-md" data-field="body">Use-of-funds paragraph.</p>
            <p class="mgf-slide-number" data-field="id">07</p>
          </div>
        </section>
        HTML;
    }

    private function slideVibrantClosing(): string
    {
        return <<<'HTML'
        <!-- Component: closing (vibrant-festival, centered CTA). Fields: eyebrow, title, cta, footer. -->
        <section class="mgf-slide mgf-flex mgf-flex-center">
          <div class="mgf-text-center" style="max-width: 720px">
            <p class="mgf-eyebrow" data-field="eyebrow">See you in November</p>
            <div class="mgf-accent-bar mgf-mt-sm" style="margin-right:auto; margin-left:auto"></div>
            <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Three days. One signal.</h2>
            <a class="mgf-cta-solid mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">hello@project.fest</a>
            <p class="mgf-caption mgf-mt-md" data-field="footer">Summer 2026</p>
          </div>
          <p class="mgf-slide-number" data-field="id">08</p>
        </section>
        HTML;
    }

    // ─────────────────────────────────────────────────────────────────────
    // dashboard-analytics — chart system + dashboard archetype
    //   Exercises the §6 chart system (bar, hbar, pie, donut, heatmap,
    //   sparkline, gauge, radar, legend) and the §4 dashboard archetype
    //   (mgf-dashboard, mgf-dash-grid, mgf-dash-cell, mgf-dash-card,
    //   mgf-widget, mgf-kpi, mgf-stat-value-lg).
    // ─────────────────────────────────────────────────────────────────────

    /** 6 slides exercising chart + dashboard. */
    protected function dashboardAnalyticsFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->dashboardContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('dashboard-analytics')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->dashboardStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->dashboardLayoutCss()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->dashboardDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideDashboardCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideDashboardKpiGrid()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideDashboardBarChart()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideDashboardHbarPie()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideDashboardHeatmap()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideDashboardRadarGauge()],
        ];
    }

    private function dashboardContext(): string
    {
        return <<<MD
        # Dashboard Context

        ## Purpose
        Single-page analytics overview for a SaaS product team.
        Shows the four operational KPIs, top-line growth, channel mix,
        and a feature heatmap.

        ## Audience
        Product, growth, and engineering leadership. They see this page
        every Monday morning.

        ## Brand voice
        Numbers first, story second. Every chart has a one-line caption
        that states the takeaway, not the topic.

        ## Visual constraints
        - Palette: deep slate + cyan + amber for "warning" series
        - Charts use the mgf-chart-* family — no inline SVG hacks
        - The page is full-bleed (no slide counter) — it's a dashboard
          wall, not a deck.
        MD;
    }

    private function dashboardStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #0B1020;
          --mgf-color-surface:       #131A2E;
          --mgf-color-surface-2:     #1B2440;
          --mgf-color-border:        #243156;
          --mgf-color-border-strong: #324571;
          --mgf-color-text-primary:  #F0F3FA;
          --mgf-color-text-secondary:#8693B8;
          --mgf-color-text-inverse:  #0B1020;
          --mgf-color-accent:        #00D4FF;
          --mgf-color-accent-soft:   #0E2A4A;
          --mgf-color-accent-2:      #FFB547;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.7rem;
          --mgf-text-sm:   0.8rem;
          --mgf-text-base: 0.95rem;
          --mgf-text-lg:   1.15rem;
          --mgf-text-xl:   1.5rem;
          --mgf-text-2xl:  2rem;
          --mgf-text-3xl:  2.75rem;
          --mgf-text-4xl:  4rem;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 8px;
          --mgf-radius-lg: 14px;
          --mgf-radius-xl: 22px;

          --mgf-slide-w:     1440px;
          --mgf-slide-h:     900px;
          --mgf-slide-pad-x: 56px;
          --mgf-slide-pad-y: 48px;

          --mgf-accent-line: 2px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function dashboardLayoutCss(): string
    {
        return <<<'CSS'
        /* dashboard layout — 12-col responsive grid, no fixed slide */
        .mgf-dashboard {
          display: grid;
          grid-template-columns: repeat(12, 1fr);
          gap: var(--mgf-space-6);
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          width: 100%;
          min-height: 100vh;
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
        }
        .mgf-dash-grid { display: grid; gap: var(--mgf-space-4); }
        .mgf-dash-cell, .mgf-dash-card {
          background: var(--mgf-color-surface);
          border: 1px solid var(--mgf-color-border);
          border-radius: var(--mgf-radius-lg);
          padding: var(--mgf-space-6);
        }
        .mgf-dash-card { border-left: 3px solid var(--mgf-color-accent); }
        .mgf-widget { display: flex; flex-direction: column; gap: var(--mgf-space-2); }
        .mgf-kpi { display: flex; flex-direction: column; gap: var(--mgf-space-1); }
        .mgf-kpi-label { font-size: var(--mgf-text-xs); color: var(--mgf-color-text-secondary); text-transform: uppercase; letter-spacing: 0.1em; }
        .mgf-kpi-value { font-size: var(--mgf-text-3xl); font-weight: var(--mgf-weight-bold); color: var(--mgf-color-text-primary); font-family: var(--mgf-font-display); }
        .mgf-stat-value-lg { font-size: var(--mgf-text-4xl); font-weight: var(--mgf-weight-bold); font-family: var(--mgf-font-display); }
        .mgf-stat-sub { font-size: var(--mgf-text-sm); color: var(--mgf-color-text-secondary); }
        .mgf-chart {
          display: flex; flex-direction: column; gap: var(--mgf-space-3);
          width: 100%; height: 100%;
        }
        .mgf-chart-title {
          font-size: var(--mgf-text-sm); color: var(--mgf-color-text-secondary);
          text-transform: uppercase; letter-spacing: 0.08em;
        }
        .mgf-chart-bar { display: flex; align-items: flex-end; gap: var(--mgf-space-3); height: 220px; padding-top: var(--mgf-space-2); }
        .mgf-bar { flex: 1; background: var(--mgf-color-accent); border-radius: var(--mgf-radius-sm) var(--mgf-radius-sm) 0 0; position: relative; min-height: 4px; display: flex; align-items: flex-end; justify-content: center; }
        .mgf-bar-value { position: absolute; top: -1.4rem; font-size: var(--mgf-text-sm); color: var(--mgf-color-text-primary); font-family: var(--mgf-font-mono); }
        .mgf-bar-label { display: block; margin-top: var(--mgf-space-2); font-size: var(--mgf-text-xs); color: var(--mgf-color-text-secondary); text-align: center; }
        .mgf-chart-hbar { display: flex; flex-direction: column; gap: var(--mgf-space-3); }
        .mgf-hbar { display: flex; align-items: center; gap: var(--mgf-space-3); }
        .mgf-hbar-label { width: 110px; font-size: var(--mgf-text-sm); color: var(--mgf-color-text-secondary); }
        .mgf-hbar-track { flex: 1; height: 14px; background: var(--mgf-color-surface-2); border-radius: 999px; overflow: hidden; }
        .mgf-hbar-fill { height: 100%; background: var(--mgf-color-accent); border-radius: inherit; }
        .mgf-chart-pie { width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(var(--mgf-color-accent) 0% 62%, var(--mgf-color-accent-2) 62% 88%, var(--mgf-color-border-strong) 88% 100%); position: relative; }
        .mgf-chart-donut { width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(var(--mgf-color-accent) 0% 45%, var(--mgf-color-accent-2) 45% 72%, var(--mgf-color-border-strong) 72% 100%); mask: radial-gradient(circle, transparent 38%, black 39%); -webkit-mask: radial-gradient(circle, transparent 38%, black 39%); position: relative; }
        .mgf-pie-center { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: var(--mgf-text-2xl); font-weight: var(--mgf-weight-bold); font-family: var(--mgf-font-display); }
        .mgf-heatmap { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
        .mgf-heat-cell { aspect-ratio: 1; border-radius: var(--mgf-radius-sm); background: var(--mgf-color-accent-soft); }
        .mgf-chart-legend { display: flex; gap: var(--mgf-space-4); flex-wrap: wrap; margin-top: var(--mgf-space-3); }
        .mgf-legend-item { display: flex; align-items: center; gap: var(--mgf-space-2); font-size: var(--mgf-text-sm); color: var(--mgf-color-text-secondary); }
        .mgf-legend-swatch { width: 12px; height: 12px; border-radius: 3px; background: var(--mgf-color-accent); }
        .mgf-sparkline { width: 100%; height: 56px; position: relative; }
        .mgf-line { fill: none; stroke: var(--mgf-color-accent); stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .mgf-area { fill: var(--mgf-color-accent-soft); }
        .mgf-area-fill { fill: var(--mgf-color-accent); opacity: 0.3; }
        .mgf-gauge { width: 200px; height: 110px; position: relative; overflow: hidden; }
        .mgf-gauge-needle { position: absolute; left: 50%; bottom: 0; width: 3px; height: 90px; background: var(--mgf-color-accent-2); transform-origin: bottom center; transform: rotate(35deg); border-radius: 2px; }
        .mgf-radar { display: grid; place-items: center; width: 240px; height: 240px; }
        .mgf-radar-grid { width: 100%; height: 100%; border-radius: 50%; background: repeating-radial-gradient(circle, transparent 0 30px, var(--mgf-color-border) 30px 31px); }
        .mgf-radar-shape { position: absolute; width: 160px; height: 160px; background: rgba(0,212,255,0.25); border: 2px solid var(--mgf-color-accent); clip-path: polygon(50% 0, 90% 30%, 75% 90%, 25% 90%, 10% 30%); }
        .mgf-axis-label { font-size: var(--mgf-text-xs); color: var(--mgf-color-text-secondary); }
        CSS;
    }

    private function dashboardDataJson($owner): string
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'];
        $mrr = [12, 18, 24, 31, 42, 55, 68, 81];
        $channels = ['Direct' => 62, 'Partner' => 26, 'Outbound' => 12];
        $heatmap = [
            ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            [3, 5, 7, 4, 2, 9, 11],
            [4, 6, 8, 5, 3, 10, 12],
            [5, 7, 9, 6, 4, 11, 13],
            [6, 8, 10, 7, 5, 12, 14],
            [7, 9, 11, 8, 6, 13, 15],
            [8, 10, 12, 9, 7, 14, 16],
        ];
        $data = [
            '_meta' => [
                'title' => $owner->name,
                'description' => $owner->description,
                'archetype' => 'dashboard-analytics',
            ],
            'slides' => [
                ['id' => '01', 'eyebrow' => 'Live · ' . now()->format('Y-m-d'), 'title' => $owner->name, 'subtitle' => 'A weekly look at the numbers that matter.'],
                ['id' => '02', 'kpis' => [
                    ['label' => 'MRR', 'value' => '$' . $mrr[7] . 'k', 'delta' => '+12% WoW'],
                    ['label' => 'Active orgs', 'value' => '1,284', 'delta' => '+38 this week'],
                    ['label' => 'Trial → paid', 'value' => '24.6%', 'delta' => '+1.2 pts'],
                    ['label' => 'NRR', 'value' => '118%', 'delta' => 'best in cohort'],
                ]],
                ['id' => '03', 'kind' => 'bar', 'title' => 'MRR by month', 'unit' => '$k', 'labels' => $months, 'values' => $mrr],
                ['id' => '04', 'hbar' => $channels, 'pie' => $channels],
                ['id' => '05', 'kind' => 'heatmap', 'title' => 'Active sessions by day/hour', 'rows' => $heatmap[0], 'values' => array_slice($heatmap, 1)],
                ['id' => '06', 'kind' => 'radar', 'axes' => ['Speed', 'Reliability', 'Coverage', 'Delight', 'Support'], 'values' => [4, 5, 3, 4, 5], 'gauge' => 78],
            ],
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function slideDashboardCover(): string
    {
        return <<<'HTML'
        <!-- Component: dashboard cover. Fields: eyebrow, title, subtitle. -->
        <section class="mgf-dashboard">
          <header class="mgf-dash-card" style="grid-column: span 12;">
            <p class="mgf-kpi-label" data-field="eyebrow">Live dashboard</p>
            <h1 class="mgf-stat-value-lg" data-field="title" style="margin-top: 0.5rem;">Product analytics</h1>
            <p class="mgf-stat-sub" data-field="subtitle" style="margin-top: 0.75rem;">A weekly look at the numbers that matter.</p>
          </header>
        </section>
        HTML;
    }

    private function slideDashboardKpiGrid(): string
    {
        return <<<'HTML'
        <!-- Component: KPI grid. 4 cells on a 12-col dashboard. Fields: kpis[]. -->
        <section class="mgf-dashboard">
          <div class="mgf-dash-grid" style="grid-column: span 12; grid-template-columns: repeat(4, 1fr);" data-field="kpis">
            <div class="mgf-dash-cell mgf-widget">
              <span class="mgf-kpi-label" data-field="label">MRR</span>
              <span class="mgf-kpi-value" data-field="value">$81k</span>
              <span class="mgf-stat-sub" data-field="delta">+12% WoW</span>
            </div>
            <div class="mgf-dash-cell mgf-widget">
              <span class="mgf-kpi-label" data-field="label">Active orgs</span>
              <span class="mgf-kpi-value" data-field="value">1,284</span>
              <span class="mgf-stat-sub" data-field="delta">+38 this week</span>
            </div>
            <div class="mgf-dash-cell mgf-widget">
              <span class="mgf-kpi-label" data-field="label">Trial → paid</span>
              <span class="mgf-kpi-value" data-field="value">24.6%</span>
              <span class="mgf-stat-sub" data-field="delta">+1.2 pts</span>
            </div>
            <div class="mgf-dash-cell mgf-widget">
              <span class="mgf-kpi-label" data-field="label">NRR</span>
              <span class="mgf-kpi-value" data-field="value">118%</span>
              <span class="mgf-stat-sub" data-field="delta">best in cohort</span>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDashboardBarChart(): string
    {
        return <<<'HTML'
        <!-- Component: vertical bar chart. Fields: title, labels[], values[]. -->
        <section class="mgf-dashboard">
          <div class="mgf-dash-card" style="grid-column: span 12;">
            <div class="mgf-chart">
              <span class="mgf-chart-title" data-field="title">MRR by month</span>
              <div class="mgf-chart-bar" data-field="bar">
                <div class="mgf-bar" style="height: 18%"><span class="mgf-bar-value" data-field="value">12</span></div>
                <div class="mgf-bar" style="height: 27%"><span class="mgf-bar-value" data-field="value">18</span></div>
                <div class="mgf-bar" style="height: 36%"><span class="mgf-bar-value" data-field="value">24</span></div>
                <div class="mgf-bar" style="height: 47%"><span class="mgf-bar-value" data-field="value">31</span></div>
                <div class="mgf-bar" style="height: 63%"><span class="mgf-bar-value" data-field="value">42</span></div>
                <div class="mgf-bar" style="height: 83%"><span class="mgf-bar-value" data-field="value">55</span></div>
                <div class="mgf-bar" style="height: 100%"><span class="mgf-bar-value" data-field="value">68</span></div>
                <div class="mgf-bar" style="height: 100%; background: var(--mgf-color-accent-2)"><span class="mgf-bar-value" data-field="value">81</span></div>
              </div>
              <div class="mgf-chart-bar" style="height: auto; padding-top: 0;" data-field="labels">
                <span class="mgf-bar-label" data-field="label">Jan</span>
                <span class="mgf-bar-label" data-field="label">Feb</span>
                <span class="mgf-bar-label" data-field="label">Mar</span>
                <span class="mgf-bar-label" data-field="label">Apr</span>
                <span class="mgf-bar-label" data-field="label">May</span>
                <span class="mgf-bar-label" data-field="label">Jun</span>
                <span class="mgf-bar-label" data-field="label">Jul</span>
                <span class="mgf-bar-label" data-field="label">Aug</span>
              </div>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDashboardHbarPie(): string
    {
        return <<<'HTML'
        <!-- Component: horizontal bars + pie + donut. -->
        <section class="mgf-dashboard">
          <div class="mgf-dash-card" style="grid-column: span 7;">
            <div class="mgf-chart">
              <span class="mgf-chart-title">Channel mix (MRR contribution)</span>
              <div class="mgf-chart-hbar" data-field="hbar">
                <div class="mgf-hbar"><span class="mgf-hbar-label" data-field="label">Direct</span><div class="mgf-hbar-track"><div class="mgf-hbar-fill" style="width: 62%"></div></div></div>
                <div class="mgf-hbar"><span class="mgf-hbar-label" data-field="label">Partner</span><div class="mgf-hbar-track"><div class="mgf-hbar-fill" style="width: 26%; background: var(--mgf-color-accent-2)"></div></div></div>
                <div class="mgf-hbar"><span class="mgf-hbar-label" data-field="label">Outbound</span><div class="mgf-hbar-track"><div class="mgf-hbar-fill" style="width: 12%; background: var(--mgf-color-border-strong)"></div></div></div>
              </div>
              <div class="mgf-chart-legend">
                <div class="mgf-legend-item"><span class="mgf-legend-swatch" style="background: var(--mgf-color-accent)"></span><span data-field="label">Direct · 62%</span></div>
                <div class="mgf-legend-item"><span class="mgf-legend-swatch" style="background: var(--mgf-color-accent-2)"></span><span data-field="label">Partner · 26%</span></div>
                <div class="mgf-legend-item"><span class="mgf-legend-swatch" style="background: var(--mgf-color-border-strong)"></span><span data-field="label">Outbound · 12%</span></div>
              </div>
            </div>
          </div>
          <div class="mgf-dash-card" style="grid-column: span 5; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; align-items: center;">
            <div class="mgf-chart">
              <span class="mgf-chart-title">Pie · share of revenue</span>
              <div class="mgf-chart-pie"><span class="mgf-pie-center">62%</span></div>
            </div>
            <div class="mgf-chart">
              <span class="mgf-chart-title">Donut · win rate</span>
              <div class="mgf-chart-donut"><span class="mgf-pie-center">45%</span></div>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDashboardHeatmap(): string
    {
        return <<<'HTML'
        <!-- Component: heatmap. 7 cols × 6 rows of mgf-heat-cell. -->
        <section class="mgf-dashboard">
          <div class="mgf-dash-card" style="grid-column: span 12;">
            <div class="mgf-chart">
              <span class="mgf-chart-title" data-field="title">Active sessions by day / hour</span>
              <div class="mgf-heatmap" data-field="values">
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.20)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.35)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.50)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.30)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.15)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.65)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.80)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.30)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.45)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.60)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.40)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.20)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.75)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.90)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.40)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.55)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.70)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.50)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.30)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.85)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,1.00)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.50)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.65)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.80)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.60)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.40)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,0.95)"></div>
                <div class="mgf-heat-cell" style="background: rgba(0,212,255,1.00)"></div>
              </div>
              <div class="mgf-chart-legend">
                <div class="mgf-legend-item"><span class="mgf-legend-swatch" style="background: rgba(0,212,255,0.20)"></span><span data-field="label">Low</span></div>
                <div class="mgf-legend-item"><span class="mgf-legend-swatch" style="background: rgba(0,212,255,0.55)"></span><span data-field="label">Mid</span></div>
                <div class="mgf-legend-item"><span class="mgf-legend-swatch" style="background: rgba(0,212,255,1.00)"></span><span data-field="label">Peak</span></div>
              </div>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDashboardRadarGauge(): string
    {
        return <<<'HTML'
        <!-- Component: radar + gauge + sparkline area. -->
        <section class="mgf-dashboard">
          <div class="mgf-dash-card" style="grid-column: span 6;">
            <div class="mgf-chart">
              <span class="mgf-chart-title">Reliability radar</span>
              <div class="mgf-radar">
                <div class="mgf-radar-grid"></div>
                <div class="mgf-radar-shape"></div>
              </div>
              <div class="mgf-chart-legend">
                <div class="mgf-legend-item"><span class="mgf-axis-label" data-field="label">Speed</span></div>
                <div class="mgf-legend-item"><span class="mgf-axis-label" data-field="label">Reliability</span></div>
                <div class="mgf-legend-item"><span class="mgf-axis-label" data-field="label">Coverage</span></div>
                <div class="mgf-legend-item"><span class="mgf-axis-label" data-field="label">Delight</span></div>
                <div class="mgf-legend-item"><span class="mgf-axis-label" data-field="label">Support</span></div>
              </div>
            </div>
          </div>
          <div class="mgf-dash-card" style="grid-column: span 3; display: flex; flex-direction: column; gap: 1rem;">
            <span class="mgf-chart-title">Uptime · last 30 days</span>
            <div class="mgf-gauge"><div class="mgf-gauge-needle"></div></div>
            <span class="mgf-stat-value-lg" data-field="gauge">78%</span>
          </div>
          <div class="mgf-dash-card" style="grid-column: span 3;">
            <div class="mgf-chart">
              <span class="mgf-chart-title">Sessions · 14 days</span>
              <svg class="mgf-sparkline" viewBox="0 0 200 56" preserveAspectRatio="none">
                <path class="mgf-area" d="M0,40 L20,34 L40,30 L60,32 L80,24 L100,28 L120,18 L140,22 L160,12 L180,16 L200,8 L200,56 L0,56 Z"/>
                <path class="mgf-line" d="M0,40 L20,34 L40,30 L60,32 L80,24 L100,28 L120,18 L140,22 L160,12 L180,16 L200,8"/>
              </svg>
              <span class="mgf-stat-sub">+34% over the window</span>
            </div>
          </div>
        </section>
        HTML;
    }

    // ─────────────────────────────────────────────────────────────────────
    // bento-features — bento grid + code blocks + marquee + spotlight + marks
    //   Exercises §5 bento layout, code-card / code-keyword / code-string /
    //   code-comment, marquee strip, spotlight single-card focus, marks
    //   list, and §7 frame variants.
    // ─────────────────────────────────────────────────────────────────────

    /** 6 slides exercising bento + code + marquee + spotlight + marks. */
    protected function bentoFeaturesFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->bentoContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('bento-features')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->bentoStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->bentoLayoutCss()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->bentoDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideBentoCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideBentoGrid()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideBentoCodeCard()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideBentoSpotlight()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideBentoMarquee()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideBentoMarks()],
        ];
    }

    private function bentoContext(): string
    {
        return <<<MD
        # Bento Context

        ## Purpose
        A feature-overview deck for a developer-tools product. Each
        "bento item" is one feature, sized by importance.

        ## Audience
        Engineers and technical buyers. They want to see what the
        product does in five seconds.

        ## Brand voice
        Concrete, technical, zero marketing fluff. Code in the slides
        is real and runnable.

        ## Visual constraints
        - Palette: warm cream + espresso + ink accent
        - Code is highlighted, not colored — keywords, strings, comments
        - Marquee is decorative — logos of partners, not testimonials
        MD;
    }

    private function bentoStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #F7F1E8;
          --mgf-color-surface:       #FFFFFF;
          --mgf-color-surface-2:     #EFE5D2;
          --mgf-color-border:        #D9C9A8;
          --mgf-color-border-strong: #8B7355;
          --mgf-color-text-primary:  #2C1F12;
          --mgf-color-text-secondary:#6B5A45;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #C2410C;
          --mgf-color-accent-soft:   #FFE7D6;
          --mgf-color-accent-2:      #15803D;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3rem;
          --mgf-text-4xl:  4.5rem;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 10px;
          --mgf-radius-lg: 18px;
          --mgf-radius-xl: 28px;

          --mgf-slide-w:     1440px;
          --mgf-slide-h:     900px;
          --mgf-slide-pad-x: 56px;
          --mgf-slide-pad-y: 48px;

          --mgf-accent-line: 2px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function bentoLayoutCss(): string
    {
        return <<<'CSS'
        .mgf-bento {
          display: grid;
          grid-template-columns: repeat(6, 1fr);
          grid-auto-rows: minmax(160px, auto);
          gap: var(--mgf-space-4);
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
          min-height: 100vh;
        }
        .mgf-bento-item {
          background: var(--mgf-color-surface);
          border: 1px solid var(--mgf-color-border);
          border-radius: var(--mgf-radius-lg);
          padding: var(--mgf-space-6);
          display: flex;
          flex-direction: column;
          gap: var(--mgf-space-3);
        }
        .mgf-bento-item[style*="--span-3"] { grid-column: span 3; }
        .mgf-bento-item[style*="--span-4"] { grid-column: span 4; }
        .mgf-bento-item[style*="--span-2"] { grid-column: span 2; }
        .mgf-bento-item[style*="--span-6"] { grid-column: span 6; }
        .mgf-bento-item .mgf-frame-accent { border-left: 4px solid var(--mgf-color-accent); }
        .mgf-bento-item .mgf-frame-double { border: 3px double var(--mgf-color-border-strong); }
        .mgf-code-card {
          background: #1F1812;
          color: #F5E9D8;
          border-radius: var(--mgf-radius-md);
          overflow: hidden;
          font-family: var(--mgf-font-mono);
          font-size: var(--mgf-text-sm);
        }
        .mgf-code-card-header {
          background: #2C2118;
          padding: var(--mgf-space-3) var(--mgf-space-4);
          color: #C8B596;
          font-size: var(--mgf-text-xs);
          letter-spacing: 0.08em;
          text-transform: uppercase;
          border-bottom: 1px solid #3D2F22;
        }
        .mgf-code-card-body { padding: var(--mgf-space-4); line-height: 1.7; }
        .mgf-code-keyword { color: #E68A4E; font-weight: 600; }
        .mgf-code-string { color: #8FBC8F; }
        .mgf-code-comment { color: #8B7355; font-style: italic; }
        .mgf-code-fn { color: #D4A574; }
        .mgf-code { display: block; }
        .mgf-marquee {
          overflow: hidden;
          background: var(--mgf-color-surface);
          border-top: 1px solid var(--mgf-color-border);
          border-bottom: 1px solid var(--mgf-color-border);
          padding: var(--mgf-space-4) 0;
        }
        .mgf-marquee-track {
          display: flex;
          gap: var(--mgf-space-12);
          animation: mgf-marquee 30s linear infinite;
          width: max-content;
        }
        @keyframes mgf-marquee { from { transform: translateX(0) } to { transform: translateX(-50%) } }
        .mgf-marquee-item {
          font-size: var(--mgf-text-lg);
          color: var(--mgf-color-text-secondary);
          font-family: var(--mgf-font-display);
          letter-spacing: -0.01em;
          white-space: nowrap;
        }
        .mgf-spotlight {
          background: linear-gradient(135deg, #FFF7E6 0%, #FFE7D6 100%);
          border: 1px solid var(--mgf-color-border);
          border-radius: var(--mgf-radius-xl);
          padding: var(--mgf-space-12);
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: var(--mgf-space-8);
          align-items: center;
        }
        .mgf-marks { display: flex; flex-wrap: wrap; gap: var(--mgf-space-3); }
        .mgf-mark {
          padding: var(--mgf-space-2) var(--mgf-space-4);
          border: 1px solid var(--mgf-color-border-strong);
          border-radius: 999px;
          font-family: var(--mgf-font-display);
          font-size: var(--mgf-text-sm);
          color: var(--mgf-color-text-primary);
          background: var(--mgf-color-surface);
        }
        .mgf-frame { padding: var(--mgf-space-6); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-md); }
        .mgf-frame-accent { border-left: 4px solid var(--mgf-color-accent); }
        .mgf-frame-double { border: 3px double var(--mgf-color-border-strong); }
        .mgf-feature-icon { width: 36px; height: 36px; border-radius: var(--mgf-radius-sm); background: var(--mgf-color-accent-soft); color: var(--mgf-color-accent); display: grid; place-items: center; font-family: var(--mgf-font-mono); font-weight: 700; }
        CSS;
    }

    private function bentoDataJson($owner): string
    {
        $features = [
            ['icon' => '⚡', 'title' => '60-second setup', 'desc' => 'One npm install, one env var, one deploy.'],
            ['icon' => '◆',  'title' => 'Type-safe contracts', 'desc' => 'OpenAPI generated into typed clients at build time.'],
            ['icon' => '↻',  'title' => 'Live schema migrations', 'desc' => 'Push a schema change without writing code.'],
            ['icon' => '⊞',  'title' => 'Built-in RLS', 'desc' => 'Row-level security at the database, not the controller.'],
            ['icon' => '✶',  'title' => 'Search by meaning', 'desc' => 'Vector search comes wired with the bootstrap.'],
            ['icon' => '◐',  'title' => 'Edge-ready', 'desc' => 'Run on Vercel, Cloudflare, Bun, or a plain Node host.'],
        ];
        $marks = ['Vercel', 'Cloudflare', 'Linear', 'Resend', 'Stripe', 'Perplexity', 'Anthropic', 'OpenAI', 'Supabase', 'Neon', 'PlanetScale', 'Turso'];
        $data = [
            '_meta' => [
                'title' => $owner->name,
                'description' => $owner->description,
                'archetype' => 'bento-features',
            ],
            'slides' => [
                ['id' => '01', 'eyebrow' => 'Feature tour', 'title' => $owner->name, 'subtitle' => 'Six things the product does. Picked by frequency of use.'],
                ['id' => '02', 'features' => $features],
                ['id' => '03', 'filename' => 'app/api/posts/route.ts', 'language' => 'typescript'],
                ['id' => '04', 'eyebrow' => 'In the spotlight', 'title' => 'Ship faster than you scope'],
                ['id' => '05', 'kind' => 'marquee'],
                ['id' => '06', 'kind' => 'marks', 'items' => $marks],
            ],
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function slideBentoCover(): string
    {
        return <<<'HTML'
        <!-- Component: bento cover. -->
        <section class="mgf-bento">
          <div class="mgf-bento-item" style="grid-column: span 6; align-items: center; text-align: center; padding: var(--mgf-space-12);">
            <p class="mgf-eyebrow" data-field="eyebrow">Feature tour</p>
            <h1 class="mgf-stat-value-lg" data-field="title" style="margin-top: 0.5rem;">Bento overview</h1>
            <p class="mgf-body" data-field="subtitle" style="max-width: 720px; margin: 0.5rem auto; font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary);">Six things the product does. Picked by frequency of use.</p>
          </div>
        </section>
        HTML;
    }

    private function slideBentoGrid(): string
    {
        return <<<'HTML'
        <!-- Component: bento grid (6 cells, mixed spans). -->
        <section class="mgf-bento" data-field="features">
          <div class="mgf-bento-item mgf-frame-accent" style="grid-column: span 3;">
            <div class="mgf-feature-icon" data-field="icon">⚡</div>
            <h3 class="mgf-title-md" data-field="title">60-second setup</h3>
            <p class="mgf-body" data-field="desc">One npm install, one env var, one deploy.</p>
          </div>
          <div class="mgf-bento-item" style="grid-column: span 3;">
            <div class="mgf-feature-icon" data-field="icon">◆</div>
            <h3 class="mgf-title-md" data-field="title">Type-safe contracts</h3>
            <p class="mgf-body" data-field="desc">OpenAPI generated into typed clients at build time.</p>
          </div>
          <div class="mgf-bento-item mgf-frame-double" style="grid-column: span 4;">
            <div class="mgf-feature-icon" data-field="icon">↻</div>
            <h3 class="mgf-title-md" data-field="title">Live schema migrations</h3>
            <p class="mgf-body" data-field="desc">Push a schema change without writing code. WAL-based diffing, reversible in one click.</p>
          </div>
          <div class="mgf-bento-item" style="grid-column: span 2;">
            <div class="mgf-feature-icon" data-field="icon">⊞</div>
            <h3 class="mgf-title-md" data-field="title">Built-in RLS</h3>
            <p class="mgf-body" data-field="desc">Row-level security at the database, not the controller.</p>
          </div>
          <div class="mgf-bento-item" style="grid-column: span 2;">
            <div class="mgf-feature-icon" data-field="icon">✶</div>
            <h3 class="mgf-title-md" data-field="title">Search by meaning</h3>
            <p class="mgf-body" data-field="desc">Vector search comes wired with the bootstrap.</p>
          </div>
          <div class="mgf-bento-item" style="grid-column: span 4;">
            <div class="mgf-feature-icon" data-field="icon">◐</div>
            <h3 class="mgf-title-md" data-field="title">Edge-ready</h3>
            <p class="mgf-body" data-field="desc">Run on Vercel, Cloudflare, Bun, or a plain Node host. Same bundle, same runtime.</p>
          </div>
        </section>
        HTML;
    }

    private function slideBentoCodeCard(): string
    {
        return <<<'HTML'
        <!-- Component: code-card with mgf-code-keyword / string / comment / fn. -->
        <section class="mgf-bento">
          <div class="mgf-bento-item" style="grid-column: span 6;">
            <div class="mgf-code-card" data-field="code">
              <div class="mgf-code-card-header" data-field="filename">app/api/posts/route.ts</div>
              <pre class="mgf-code-card-body"><code><span class="mgf-code-keyword">import</span> { <span class="mgf-code-fn">NextResponse</span> } <span class="mgf-code-keyword">from</span> <span class="mgf-code-string">"next/server"</span>;
        <span class="mgf-code-keyword">import</span> { <span class="mgf-code-fn">posts</span> } <span class="mgf-code-keyword">from</span> <span class="mgf-code-string">"@/db/schema"</span>;

        <span class="mgf-code-comment">// GET /api/posts — list posts visible to the current user</span>
        <span class="mgf-code-keyword">export async function</span> <span class="mgf-code-fn">GET</span>(req: Request) {
          <span class="mgf-code-keyword">const</span> rows = <span class="mgf-code-keyword">await</span> <span class="mgf-code-fn">posts</span>.<span class="mgf-code-fn">visibleTo</span>(req);
          <span class="mgf-code-keyword">return</span> <span class="mgf-code-fn">NextResponse</span>.<span class="mgf-code-fn">json</span>({ rows });
        }</code></pre>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideBentoSpotlight(): string
    {
        return <<<'HTML'
        <!-- Component: spotlight — one big card focused on a single idea. -->
        <section class="mgf-bento">
          <div class="mgf-spotlight" style="grid-column: span 6;">
            <div>
              <p class="mgf-eyebrow" data-field="eyebrow">In the spotlight</p>
              <h2 class="mgf-title-xl mgf-mt-md" data-field="title">Ship faster than you scope</h2>
              <p class="mgf-body mgf-mt-md" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary);">The deploy pipeline is built around the assumption that the spec arrived incomplete. Branch previews, ephemeral DBs, and a rollback in one keystroke.</p>
            </div>
            <div class="mgf-frame mgf-frame-accent">
              <p class="mgf-kpi-label">Avg. time to first deploy</p>
              <p class="mgf-stat-value-lg" data-field="metric">14m</p>
              <p class="mgf-stat-sub">vs. 4h 10m on the previous stack</p>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideBentoMarquee(): string
    {
        return <<<'HTML'
        <!-- Component: marquee — partners scrolling across a strip. -->
        <section class="mgf-bento">
          <div class="mgf-bento-item" style="grid-column: span 6;">
            <p class="mgf-eyebrow">Customers</p>
            <h3 class="mgf-title-md" style="margin-top: 0.5rem;">Who is using it</h3>
            <div class="mgf-marquee" style="margin-top: 1rem;">
              <div class="mgf-marquee-track" data-field="items">
                <span class="mgf-marquee-item">Vercel</span>
                <span class="mgf-marquee-item">Cloudflare</span>
                <span class="mgf-marquee-item">Linear</span>
                <span class="mgf-marquee-item">Resend</span>
                <span class="mgf-marquee-item">Stripe</span>
                <span class="mgf-marquee-item">Perplexity</span>
                <span class="mgf-marquee-item">Anthropic</span>
                <span class="mgf-marquee-item">OpenAI</span>
                <span class="mgf-marquee-item">Supabase</span>
                <span class="mgf-marquee-item">Neon</span>
                <span class="mgf-marquee-item">PlanetScale</span>
                <span class="mgf-marquee-item">Turso</span>
                <span class="mgf-marquee-item">Vercel</span>
                <span class="mgf-marquee-item">Cloudflare</span>
                <span class="mgf-marquee-item">Linear</span>
                <span class="mgf-marquee-item">Resend</span>
                <span class="mgf-marquee-item">Stripe</span>
                <span class="mgf-marquee-item">Perplexity</span>
                <span class="mgf-marquee-item">Anthropic</span>
                <span class="mgf-marquee-item">OpenAI</span>
                <span class="mgf-marquee-item">Supabase</span>
                <span class="mgf-marquee-item">Neon</span>
                <span class="mgf-marquee-item">PlanetScale</span>
                <span class="mgf-marquee-item">Turso</span>
              </div>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideBentoMarks(): string
    {
        return <<<'HTML'
        <!-- Component: marks — pill-style tags. -->
        <section class="mgf-bento">
          <div class="mgf-bento-item" style="grid-column: span 6;">
            <p class="mgf-eyebrow">In the press</p>
            <h3 class="mgf-title-md" style="margin-top: 0.5rem;">A few places it has shown up</h3>
            <div class="mgf-marks" style="margin-top: 1rem;" data-field="items">
              <span class="mgf-mark" data-field="label">Vercel</span>
              <span class="mgf-mark" data-field="label">Cloudflare</span>
              <span class="mgf-mark" data-field="label">Linear</span>
              <span class="mgf-mark" data-field="label">Resend</span>
              <span class="mgf-mark" data-field="label">Stripe</span>
              <span class="mgf-mark" data-field="label">Perplexity</span>
              <span class="mgf-mark" data-field="label">Anthropic</span>
              <span class="mgf-mark" data-field="label">OpenAI</span>
              <span class="mgf-mark" data-field="label">Supabase</span>
              <span class="mgf-mark" data-field="label">Neon</span>
              <span class="mgf-mark" data-field="label">PlanetScale</span>
              <span class="mgf-mark" data-field="label">Turso</span>
            </div>
          </div>
        </section>
        HTML;
    }

    // ─────────────────────────────────────────────────────────────────────
    // editorial-poster — backgrounds, frames, and modifiers
    //   Exercises §7 background patterns (mgf-bg-grid, bg-dots, bg-lines,
    //   bg-gradient, bg-accent, bg-surface), §7 frame variants (mgf-frame,
    //   frame-accent, frame-double), §8 modifiers (mgf-glass, neo, neo-inset,
    //   brutal-border, grain, ambient-glow, dense, air, hi, lo, flat,
    //   display-serif, body-serif, accent-bar-lg, chapter-num-lg).
    // ─────────────────────────────────────────────────────────────────────

    /** 5 slides exercising backgrounds, frames, modifiers, typography. */
    protected function editorialPosterFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->editorialPosterContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('editorial-poster')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->editorialPosterStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->editorialPosterLayoutCss()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->editorialPosterDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slidePosterCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slidePosterPatterns()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slidePosterFrames()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slidePosterModifiers()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slidePosterTypography()],
        ];
    }

    private function editorialPosterContext(): string
    {
        return <<<MD
        # Editorial Poster Context

        ## Purpose
        A reference deck for the design system's background, frame, and
        modifier families. Every slide is a swatch catalog.

        ## Audience
        Designers adopting the MGF tokens. The slides double as a
        living style guide.

        ## Brand voice
        Quiet, confident, descriptive. Each swatch label is one or two words.

        ## Visual constraints
        - Palette: paper cream + terracotta + ink
        - Display serif, body serif (Source Serif 4 throughout)
        - Every frame must show one and only one structural variant
        MD;
    }

    private function editorialPosterStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #F2EAD3;
          --mgf-color-surface:       #F8F4E5;
          --mgf-color-surface-2:     #E6DDB8;
          --mgf-color-border:        #C8B789;
          --mgf-color-border-strong: #8B7355;
          --mgf-color-text-primary:  #2C1F12;
          --mgf-color-text-secondary:#6B5A45;
          --mgf-color-text-inverse:  #F8F4E5;
          --mgf-color-accent:        #B85C38;
          --mgf-color-accent-soft:   #F0D9C5;
          --mgf-color-accent-2:      #2F5061;

          --mgf-font-display: 'Source Serif 4', 'Georgia', serif;
          --mgf-font-body:    'Source Serif 4', 'Georgia', serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3rem;
          --mgf-text-4xl:  4.5rem;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 0px;
          --mgf-radius-md: 2px;
          --mgf-radius-lg: 4px;
          --mgf-radius-xl: 8px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 4px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function editorialPosterLayoutCss(): string
    {
        return <<<'CSS'
        /* editorial-poster — backgrounds, frames, modifiers */
        .mgf-mt-sm { margin-top: var(--mgf-space-3); }
        .mgf-mt-md { margin-top: var(--mgf-space-6); }
        .mgf-mt-lg { margin-top: var(--mgf-space-12); }
        .mgf-poster {
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
          width: 100%; min-height: 100vh;
          position: relative;
        }
        /* Backgrounds */
        .mgf-bg-grid { background-image: linear-gradient(var(--mgf-color-border) 1px, transparent 1px), linear-gradient(90deg, var(--mgf-color-border) 1px, transparent 1px); background-size: 32px 32px; }
        .mgf-bg-grid-fine { background-image: linear-gradient(var(--mgf-color-border) 1px, transparent 1px), linear-gradient(90deg, var(--mgf-color-border) 1px, transparent 1px); background-size: 12px 12px; }
        .mgf-bg-grid-lg { background-image: linear-gradient(var(--mgf-color-border) 1px, transparent 1px), linear-gradient(90deg, var(--mgf-color-border) 1px, transparent 1px); background-size: 64px 64px; }
        .mgf-bg-dots { background-image: radial-gradient(var(--mgf-color-border-strong) 1px, transparent 1px); background-size: 20px 20px; }
        .mgf-bg-lines { background-image: repeating-linear-gradient(45deg, var(--mgf-color-border) 0 1px, transparent 1px 12px); }
        .mgf-bg-gradient { background: linear-gradient(135deg, var(--mgf-color-surface) 0%, var(--mgf-color-surface-2) 100%); }
        .mgf-bg-gradient-accent { background: linear-gradient(135deg, var(--mgf-color-accent) 0%, var(--mgf-color-accent-2) 100%); color: var(--mgf-color-text-inverse); }
        .mgf-bg-surface { background: var(--mgf-color-surface); }
        .mgf-bg-accent { background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); }
        /* Frames */
        .mgf-frame { padding: var(--mgf-space-6); border: 1px solid var(--mgf-color-border-strong); }
        .mgf-frame-accent { padding: var(--mgf-space-6); border-left: 4px solid var(--mgf-color-accent); background: var(--mgf-color-surface); }
        .mgf-frame-double { padding: var(--mgf-space-6); border: 3px double var(--mgf-color-border-strong); background: var(--mgf-color-surface); }
        /* Modifiers */
        .mgf-glass { background: rgba(255,255,255,0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.6); }
        .mgf-neo { background: var(--mgf-color-surface); border: 2px solid var(--mgf-color-text-primary); box-shadow: 6px 6px 0 var(--mgf-color-text-primary); }
        .mgf-neo-inset { background: var(--mgf-color-surface); border: 2px solid var(--mgf-color-text-primary); box-shadow: inset 4px 4px 0 var(--mgf-color-surface-2); }
        .mgf-brutal-border { background: var(--mgf-color-bg); border: 4px solid var(--mgf-color-text-primary); }
        .mgf-grain { background-color: var(--mgf-color-surface); background-image: radial-gradient(rgba(0,0,0,0.04) 1px, transparent 1px); background-size: 4px 4px; }
        .mgf-grain-heavy { background-color: var(--mgf-color-surface); background-image: radial-gradient(rgba(0,0,0,0.10) 1px, transparent 1px); background-size: 3px 3px; }
        .mgf-grain-soft { background-color: var(--mgf-color-surface); background-image: radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px); background-size: 6px 6px; }
        .mgf-grain-none { background-color: var(--mgf-color-surface); }
        .mgf-ambient-glow { box-shadow: 0 0 60px 0 var(--mgf-color-accent-soft); }
        .mgf-dense { padding: var(--mgf-space-3); line-height: 1.2; }
        .mgf-air { padding: var(--mgf-space-12); line-height: 1.8; }
        .mgf-hi { color: var(--mgf-color-text-primary); font-weight: 700; }
        .mgf-lo { color: var(--mgf-color-text-secondary); }
        .mgf-flat { box-shadow: none; }
        .mgf-display-serif { font-family: var(--mgf-font-display); }
        .mgf-body-serif { font-family: var(--mgf-font-body); }
        .mgf-display-mono { font-family: var(--mgf-font-mono); }
        .mgf-body-mono { font-family: var(--mgf-font-mono); font-size: var(--mgf-text-sm); }
        .mgf-accent-1 { color: var(--mgf-color-accent); }
        .mgf-accent-2 { color: var(--mgf-color-accent-2); }
        /* Other */
        .mgf-chapter-num { font-family: var(--mgf-font-mono); font-size: var(--mgf-text-sm); letter-spacing: 0.2em; text-transform: uppercase; color: var(--mgf-color-text-secondary); }
        .mgf-chapter-num-lg { font-family: var(--mgf-font-mono); font-size: var(--mgf-text-4xl); color: var(--mgf-color-accent); letter-spacing: -0.02em; }
        .mgf-accent-bar { width: 60px; height: 4px; background: var(--mgf-color-accent); }
        .mgf-accent-bar-lg { width: 120px; height: 8px; background: var(--mgf-color-accent); }
        .mgf-divider-short { width: 32px; height: 1px; background: var(--mgf-color-border-strong); }
        .mgf-swatches { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--mgf-space-4); }
        .mgf-swatch { padding: var(--mgf-space-6); min-height: 160px; display: flex; flex-direction: column; justify-content: flex-end; }
        CSS;
    }

    private function editorialPosterDataJson($owner): string
    {
        $data = [
            '_meta' => [
                'title' => $owner->name,
                'description' => $owner->description,
                'archetype' => 'editorial-poster',
            ],
            'slides' => [
                ['id' => '01', 'chapter' => '01', 'eyebrow' => 'Style guide', 'title' => $owner->name, 'subtitle' => 'Reference deck for backgrounds, frames, and modifiers.'],
                ['id' => '02', 'kind' => 'backgrounds'],
                ['id' => '03', 'kind' => 'frames'],
                ['id' => '04', 'kind' => 'modifiers'],
                ['id' => '05', 'kind' => 'typography'],
            ],
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function slidePosterCover(): string
    {
        return <<<'HTML'
        <!-- Component: editorial poster cover — chapter-num, accent-bar-lg, display-serif. -->
        <section class="mgf-poster mgf-bg-grid-fine">
          <p class="mgf-chapter-num" data-field="chapter">Chapter 01</p>
          <div class="mgf-accent-bar-lg mgf-mt-sm"></div>
          <h1 class="mgf-display-serif mgf-mt-md" data-field="title" style="font-size: var(--mgf-text-4xl); font-weight: 400; line-height: 1.1; max-width: 900px;">Backgrounds, frames, and modifiers</h1>
          <p class="mgf-body-serif mgf-mt-md" data-field="subtitle" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary); max-width: 720px;">Reference deck for the design-system families. One swatch per cell, one caption per row.</p>
          <div class="mgf-divider-short mgf-mt-lg"></div>
        </section>
        HTML;
    }

    private function slidePosterPatterns(): string
    {
        return <<<'HTML'
        <!-- Component: background swatches (mgf-bg-grid, bg-dots, bg-lines, bg-gradient). -->
        <section class="mgf-poster">
          <p class="mgf-chapter-num mgf-lo">§7 Backgrounds</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-display-serif mgf-mt-md mgf-hi" style="font-size: var(--mgf-text-2xl);">Patterns</h2>
          <div class="mgf-swatches mgf-mt-lg" data-field="backgrounds">
            <div class="mgf-swatch mgf-bg-grid mgf-body-serif"><span class="mgf-lo">grid</span></div>
            <div class="mgf-swatch mgf-bg-grid-fine mgf-body-serif"><span class="mgf-lo">grid-fine</span></div>
            <div class="mgf-swatch mgf-bg-grid-lg mgf-body-serif"><span class="mgf-lo">grid-lg</span></div>
            <div class="mgf-swatch mgf-bg-dots mgf-body-serif"><span class="mgf-lo">dots</span></div>
            <div class="mgf-swatch mgf-bg-lines mgf-body-serif"><span class="mgf-lo">lines</span></div>
            <div class="mgf-swatch mgf-bg-gradient mgf-body-serif"><span class="mgf-lo">gradient</span></div>
            <div class="mgf-swatch mgf-bg-gradient-accent mgf-body-serif"><span class="mgf-hi">gradient-accent</span></div>
            <div class="mgf-swatch mgf-bg-accent mgf-body-serif"><span class="mgf-hi">accent</span></div>
          </div>
        </section>
        HTML;
    }

    private function slidePosterFrames(): string
    {
        return <<<'HTML'
        <!-- Component: frame swatches (mgf-frame, frame-accent, frame-double). -->
        <section class="mgf-poster">
          <p class="mgf-chapter-num mgf-lo">§7 Frames</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-display-serif mgf-mt-md mgf-hi" style="font-size: var(--mgf-text-2xl);">Containers</h2>
          <div class="mgf-swatches mgf-mt-lg" data-field="frames">
            <div class="mgf-frame mgf-body-serif"><span class="mgf-lo">frame</span><p class="mgf-mt-sm">A 1px rule and a small inner gutter.</p></div>
            <div class="mgf-frame-accent mgf-body-serif"><span class="mgf-accent-1">frame-accent</span><p class="mgf-mt-sm">A 4px left bar in the accent color.</p></div>
            <div class="mgf-frame-double mgf-body-serif"><span class="mgf-lo">frame-double</span><p class="mgf-mt-sm">A 3px double border for emphasis.</p></div>
            <div class="mgf-frame mgf-glass mgf-body-serif"><span class="mgf-lo">frame · glass</span><p class="mgf-mt-sm">A translucent interior over a pattern.</p></div>
          </div>
        </section>
        HTML;
    }

    private function slidePosterModifiers(): string
    {
        return <<<'HTML'
        <!-- Component: modifier swatches (mgf-glass, neo, neo-inset, brutal-border, grain). -->
        <section class="mgf-poster">
          <p class="mgf-chapter-num mgf-lo">§8 Modifiers</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-display-serif mgf-mt-md mgf-hi" style="font-size: var(--mgf-text-2xl);">Surface treatments</h2>
          <div class="mgf-swatches mgf-mt-lg" data-field="modifiers">
            <div class="mgf-swatch mgf-glass mgf-body-serif"><span class="mgf-lo">glass</span></div>
            <div class="mgf-swatch mgf-neo mgf-body-serif"><span class="mgf-lo">neo</span></div>
            <div class="mgf-swatch mgf-neo-inset mgf-body-serif"><span class="mgf-lo">neo-inset</span></div>
            <div class="mgf-swatch mgf-brutal-border mgf-body-serif"><span class="mgf-lo">brutal-border</span></div>
            <div class="mgf-swatch mgf-grain mgf-body-serif"><span class="mgf-lo">grain</span></div>
            <div class="mgf-swatch mgf-grain-heavy mgf-body-serif"><span class="mgf-lo">grain-heavy</span></div>
            <div class="mgf-swatch mgf-grain-soft mgf-body-serif"><span class="mgf-lo">grain-soft</span></div>
            <div class="mgf-swatch mgf-grain-none mgf-ambient-glow mgf-body-serif"><span class="mgf-lo">none · ambient-glow</span></div>
          </div>
        </section>
        HTML;
    }

    private function slidePosterTypography(): string
    {
        return <<<'HTML'
        <!-- Component: typography swatches (display-serif, body-serif, dense, air, hi, lo). -->
        <section class="mgf-poster">
          <p class="mgf-chapter-num mgf-lo">§3 Typography</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-display-serif mgf-mt-md mgf-hi mgf-dense" style="font-size: var(--mgf-text-2xl);">Hierarchy</h2>
          <div class="mgf-swatches mgf-mt-lg" data-field="typography" style="grid-template-columns: repeat(2, 1fr);">
            <div class="mgf-frame mgf-air">
              <p class="mgf-chapter-num-lg">Aa</p>
              <p class="mgf-display-serif mgf-hi mgf-mt-sm" style="font-size: var(--mgf-text-3xl);">Display · Source Serif 4</p>
              <p class="mgf-body-serif mgf-lo mgf-mt-sm" style="font-size: var(--mgf-text-base);">Body · Source Serif 4 — for long-form reading.</p>
            </div>
            <div class="mgf-frame mgf-dense">
              <p class="mgf-chapter-num-lg">Aa</p>
              <p class="mgf-display-mono mgf-hi mgf-mt-sm" style="font-size: var(--mgf-text-xl);">Display · JetBrains Mono</p>
              <p class="mgf-body-mono mgf-lo mgf-mt-sm">Body · for code, captions, and tickers.</p>
              <p class="mgf-divider-short mgf-mt-md"></p>
              <p class="mgf-mt-md"><span class="mgf-accent-1">accent-1</span> · <span class="mgf-accent-2">accent-2</span> · <span class="mgf-hi">hi</span> · <span class="mgf-lo">lo</span></p>
            </div>
          </div>
        </section>
        HTML;
    }

    // ─────────────────────────────────────────────────────────────────────
    // code-tutorial — code walkthrough, comparison table, form
    //   Exercises §5 code-card + code, table / th / td, form / input,
    //   and reuses the code-keyword / string / comment / fn color classes.
    // ─────────────────────────────────────────────────────────────────────

    /** 5 slides — code-first walkthrough + comparison table + form. */
    protected function codeTutorialFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->codeTutorialContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('code-tutorial')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->codeTutorialStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->codeTutorialLayoutCss()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->codeTutorialDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideCodeCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideCodeWalkthrough()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideCodeTable()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideCodeForm()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideCodeClosing()],
        ];
    }

    private function codeTutorialContext(): string
    {
        return <<<MD
        # Code Tutorial Context

        ## Purpose
        A walkthrough deck for an internal API — half docs, half cheatsheet.
        Every slide has one runnable thing on it.

        ## Audience
        Engineers integrating the SDK. They want to copy-paste working code.

        ## Brand voice
        Plain. No marketing. Comments in code are part of the lesson.

        ## Visual constraints
        - Palette: paper white + emerald accent + slate text
        - Each code block is paired with a one-line "what just happened" caption
        - Tables are small and square — no horizontal scroll
        MD;
    }

    private function codeTutorialStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #F8FAF7;
          --mgf-color-surface:       #FFFFFF;
          --mgf-color-surface-2:     #F1F4ED;
          --mgf-color-border:        #DCE4D6;
          --mgf-color-border-strong: #95A48C;
          --mgf-color-text-primary:  #0F1B14;
          --mgf-color-text-secondary:#5A6B58;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #0F766E;
          --mgf-color-accent-soft:   #D5ECE7;
          --mgf-color-accent-2:      #B45309;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3rem;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 8px;
          --mgf-radius-lg: 12px;
          --mgf-radius-xl: 20px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 80px;
          --mgf-slide-pad-y: 60px;

          --mgf-accent-line: 2px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function codeTutorialLayoutCss(): string
    {
        return <<<'CSS'
        .mgf-cta-flow {
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
          min-height: 100vh;
          display: flex;
          flex-direction: column;
          gap: var(--mgf-space-6);
        }
        .mgf-code-card {
          background: #0F1B14;
          color: #DCE4D6;
          border-radius: var(--mgf-radius-md);
          overflow: hidden;
          font-family: var(--mgf-font-mono);
          font-size: 0.85rem;
        }
        .mgf-code-card-header { background: #182B20; padding: var(--mgf-space-3) var(--mgf-space-4); color: #95A48C; font-size: var(--mgf-text-xs); letter-spacing: 0.08em; text-transform: uppercase; border-bottom: 1px solid #25372A; }
        .mgf-code-card-body { padding: var(--mgf-space-4); line-height: 1.7; }
        .mgf-code-keyword { color: #7DD3C0; font-weight: 600; }
        .mgf-code-string  { color: #FCD34D; }
        .mgf-code-comment { color: #95A48C; font-style: italic; }
        .mgf-code-fn      { color: #93C5FD; }
        .mgf-code { display: block; }
        .mgf-table { width: 100%; border-collapse: collapse; font-size: var(--mgf-text-sm); }
        .mgf-th { text-align: left; padding: var(--mgf-space-3) var(--mgf-space-4); background: var(--mgf-color-surface-2); border-bottom: 2px solid var(--mgf-color-border-strong); font-family: var(--mgf-font-mono); font-size: var(--mgf-text-xs); text-transform: uppercase; letter-spacing: 0.08em; color: var(--mgf-color-text-secondary); }
        .mgf-td { padding: var(--mgf-space-3) var(--mgf-space-4); border-bottom: 1px solid var(--mgf-color-border); }
        .mgf-form { display: flex; flex-direction: column; gap: var(--mgf-space-4); max-width: 560px; }
        .mgf-input { padding: var(--mgf-space-3) var(--mgf-space-4); background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border-strong); border-radius: var(--mgf-radius-sm); font-family: var(--mgf-font-body); font-size: var(--mgf-text-base); color: var(--mgf-color-text-primary); }
        .mgf-input:focus { outline: 2px solid var(--mgf-color-accent); outline-offset: 2px; }
        .mgf-label { font-size: var(--mgf-text-sm); font-weight: 600; color: var(--mgf-color-text-primary); }
        .mgf-hint { font-size: var(--mgf-text-xs); color: var(--mgf-color-text-secondary); }
        .mgf-cta-solid { display: inline-block; padding: var(--mgf-space-3) var(--mgf-space-6); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); border-radius: var(--mgf-radius-sm); font-weight: 600; text-decoration: none; }
        .mgf-mt-sm { margin-top: var(--mgf-space-3); }
        .mgf-mt-md { margin-top: var(--mgf-space-6); }
        .mgf-mt-lg { margin-top: var(--mgf-space-12); }
        CSS;
    }

    private function codeTutorialDataJson($owner): string
    {
        $rows = [
            ['op' => 'CONFIGURE', 'php' => 'configure([api_key, region])', 'node' => 'new Client({ apiKey, region })', 'curl' => '-H "Authorization: Bearer $K"'],
            ['op' => 'LIST',      'php' => '$c->list([limit => 50])',       'node' => 'await c.list({ limit: 50 })',  'curl' => 'GET /v1/records?limit=50'],
            ['op' => 'FIND',      'php' => '$c->find($id)',                 'node' => 'await c.find(id)',              'curl' => 'GET /v1/records/$id'],
            ['op' => 'CREATE',    'php' => '$c->create([...])',             'node' => 'await c.create({...})',         'curl' => 'POST /v1/records -d {...}'],
            ['op' => 'UPDATE',    'php' => '$c->update($id, [...])',        'node' => 'await c.update(id, {...})',     'curl' => 'PATCH /v1/records/$id -d {...}'],
            ['op' => 'DELETE',    'php' => '$c->delete($id)',               'node' => 'await c.delete(id)',            'curl' => 'DELETE /v1/records/$id'],
        ];
        $data = [
            '_meta' => [
                'title' => $owner->name,
                'description' => $owner->description,
                'archetype' => 'code-tutorial',
            ],
            'slides' => [
                ['id' => '01', 'eyebrow' => 'SDK · v3', 'title' => $owner->name, 'subtitle' => $owner->description],
                ['id' => '02', 'filename' => 'examples/list.ts', 'language' => 'typescript'],
                ['id' => '03', 'rows' => $rows],
                ['id' => '04', 'kind' => 'form'],
                ['id' => '05', 'eyebrow' => 'Docs', 'title' => 'Continue in the docs'],
            ],
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function slideCodeCover(): string
    {
        return <<<'HTML'
        <!-- Component: code-tutorial cover. -->
        <section class="mgf-cta-flow">
          <p class="mgf-eyebrow" data-field="eyebrow">SDK · v3</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-mt-md" data-field="title" style="font-size: var(--mgf-text-3xl); font-weight: 700; max-width: 720px;">A tour of the SDK, in five slides</h1>
          <p class="mgf-mt-md" data-field="subtitle" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary); max-width: 720px;">One pattern per slide. Copy-paste, run, ship.</p>
        </section>
        HTML;
    }

    private function slideCodeWalkthrough(): string
    {
        return <<<'HTML'
        <!-- Component: code walkthrough. -->
        <section class="mgf-cta-flow">
          <p class="mgf-eyebrow">Listing records</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl);">Five lines, one paginated list</h2>
          <div class="mgf-code-card mgf-mt-md" data-field="code">
            <div class="mgf-code-card-header" data-field="filename">examples/list.ts</div>
            <pre class="mgf-code-card-body"><code><span class="mgf-code-keyword">import</span> { <span class="mgf-code-fn">Client</span> } <span class="mgf-code-keyword">from</span> <span class="mgf-code-string">"@acme/sdk"</span>;
        <span class="mgf-code-keyword">const</span> c = <span class="mgf-code-keyword">new</span> <span class="mgf-code-fn">Client</span>({ apiKey: process.env.<span class="mgf-code-fn">ACME_KEY</span> });
        <span class="mgf-code-keyword">const</span> page = <span class="mgf-code-keyword">await</span> c.<span class="mgf-code-fn">list</span>({ limit: <span class="mgf-code-string">50</span> });
        <span class="mgf-code-keyword">for</span> (<span class="mgf-code-keyword">const</span> r <span class="mgf-code-keyword">of</span> page.items) <span class="mgf-code-fn">console</span>.<span class="mgf-code-fn">log</span>(r.id, r.name);
        <span class="mgf-code-comment">// page.nextCursor tells you if there's more</span></code></pre>
          </div>
          <p class="mgf-hint mgf-mt-md">Pagination is cursor-based by default — the SDK hands you the next cursor and you keep going.</p>
        </section>
        HTML;
    }

    private function slideCodeTable(): string
    {
        return <<<'HTML'
        <!-- Component: comparison table (mgf-table). -->
        <section class="mgf-cta-flow">
          <p class="mgf-eyebrow">Cross-language reference</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl);">Same six operations, four runtimes</h2>
          <table class="mgf-table mgf-mt-md" data-field="rows">
            <thead>
              <tr>
                <th class="mgf-th">Operation</th>
                <th class="mgf-th">PHP</th>
                <th class="mgf-th">Node</th>
                <th class="mgf-th">curl</th>
              </tr>
            </thead>
            <tbody>
              <tr><td class="mgf-td"><span class="mgf-code-keyword">CONFIGURE</span></td><td class="mgf-td"><span class="mgf-code">configure([api_key, region])</span></td><td class="mgf-td"><span class="mgf-code">new Client({ apiKey, region })</span></td><td class="mgf-td"><span class="mgf-code">-H "Authorization: Bearer $K"</span></td></tr>
              <tr><td class="mgf-td"><span class="mgf-code-keyword">LIST</span></td><td class="mgf-td"><span class="mgf-code">$c->list([limit =&gt; 50])</span></td><td class="mgf-td"><span class="mgf-code">await c.list({ limit: 50 })</span></td><td class="mgf-td"><span class="mgf-code">GET /v1/records?limit=50</span></td></tr>
              <tr><td class="mgf-td"><span class="mgf-code-keyword">FIND</span></td><td class="mgf-td"><span class="mgf-code">$c-&gt;find($id)</span></td><td class="mgf-td"><span class="mgf-code">await c.find(id)</span></td><td class="mgf-td"><span class="mgf-code">GET /v1/records/$id</span></td></tr>
              <tr><td class="mgf-td"><span class="mgf-code-keyword">CREATE</span></td><td class="mgf-td"><span class="mgf-code">$c-&gt;create([...])</span></td><td class="mgf-td"><span class="mgf-code">await c.create({...})</span></td><td class="mgf-td"><span class="mgf-code">POST /v1/records -d {...}</span></td></tr>
              <tr><td class="mgf-td"><span class="mgf-code-keyword">UPDATE</span></td><td class="mgf-td"><span class="mgf-code">$c-&gt;update($id, [...])</span></td><td class="mgf-td"><span class="mgf-code">await c.update(id, {...})</span></td><td class="mgf-td"><span class="mgf-code">PATCH /v1/records/$id -d {...}</span></td></tr>
              <tr><td class="mgf-td"><span class="mgf-code-keyword">DELETE</span></td><td class="mgf-td"><span class="mgf-code">$c-&gt;delete($id)</span></td><td class="mgf-td"><span class="mgf-code">await c.delete(id)</span></td><td class="mgf-td"><span class="mgf-code">DELETE /v1/records/$id</span></td></tr>
            </tbody>
          </table>
        </section>
        HTML;
    }

    private function slideCodeForm(): string
    {
        return <<<'HTML'
        <!-- Component: form with mgf-input / mgf-label / mgf-hint. -->
        <section class="mgf-cta-flow">
          <p class="mgf-eyebrow">Set up a new project</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl);">Two fields, one click</h2>
          <form class="mgf-form mgf-mt-md">
            <label class="mgf-label" for="name">Project name</label>
            <input class="mgf-input" id="name" type="text" placeholder="cleartab" />
            <span class="mgf-hint">Lowercase, no spaces. Used as the subdomain.</span>
            <label class="mgf-label mgf-mt-md" for="region">Region</label>
            <input class="mgf-input" id="region" type="text" value="us-east-1" />
            <span class="mgf-hint">Stored in <code>.env</code> as <span class="mgf-code-string">ACME_REGION</span>.</span>
            <button class="mgf-cta-solid mgf-mt-md" type="button">Create project</button>
          </form>
        </section>
        HTML;
    }

    private function slideCodeClosing(): string
    {
        return <<<'HTML'
        <!-- Component: code-tutorial closing. -->
        <section class="mgf-cta-flow mgf-flex mgf-flex-center" style="align-items: center; justify-content: center; text-align: center;">
          <p class="mgf-eyebrow" data-field="eyebrow">Docs</p>
          <div class="mgf-accent-bar mgf-mt-sm" style="margin: 0.5rem auto 0;"></div>
          <h2 class="mgf-mt-md" data-field="title" style="font-size: var(--mgf-text-3xl); font-weight: 700;">Continue in the docs</h2>
          <p class="mgf-mt-md" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary);">Full reference, recipes, and the changelog.</p>
          <a class="mgf-cta-solid mgf-mt-lg" href="#">docs.acme.dev</a>
        </section>
        HTML;
    }

    // ─────────────────────────────────────────────────────────────────────
    // marketing-deck — deck archetype family
    //   Exercises §4 deck-* components (mgf-deck, deck-vertical, deck-progress,
    //   deck-dots, deck-card, deck-cta, deck-feature, deck-team, deck-table,
    //   deck-faq, deck-price, deck-media) and slide-size-16x9/4x3/a4/square.
    // ─────────────────────────────────────────────────────────────────────

    /** 6 slides exercising the deck-* component family. */
    protected function marketingDeckFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->marketingDeckContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('marketing-deck')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->marketingDeckStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->marketingDeckLayoutCss()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->marketingDeckDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideDeckCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideDeckFeatures()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideDeckTeam()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideDeckPricing()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideDeckFaq()],
            ['layer' => 'slide', 'name' => 'slide-06.html', 'extension' => 'html', 'content' => $this->slideDeckClosing()],
        ];
    }

    private function marketingDeckContext(): string
    {
        return <<<MD
        # Marketing Deck Context

        ## Purpose
        A sales-marketing deck for a B2B SaaS. Six slides, one CTA.

        ## Audience
        Mid-market IT leaders. They have 20 minutes and 15 tabs.

        ## Brand voice
        Confident, plain-spoken, a single accent.

        ## Visual constraints
        - Palette: cobalt + lime + warm white
        - Deck-vertical layout, with progress dots in the upper-right
        - Every slide is sized to a 16:9 canvas
        MD;
    }

    private function marketingDeckStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #FFFFFF;
          --mgf-color-surface:       #F4F6FB;
          --mgf-color-surface-2:     #E8ECF6;
          --mgf-color-border:        #DCDFEC;
          --mgf-color-border-strong: #1F2A44;
          --mgf-color-text-primary:  #0F172A;
          --mgf-color-text-secondary:#5B6577;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #1E40AF;
          --mgf-color-accent-soft:   #DBEAFE;
          --mgf-color-accent-2:      #84CC16;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3rem;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 8px;
          --mgf-radius-lg: 14px;
          --mgf-radius-xl: 24px;

          --mgf-slide-w:     1280px;
          --mgf-slide-h:     720px;
          --mgf-slide-pad-x: 56px;
          --mgf-slide-pad-y: 48px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function marketingDeckLayoutCss(): string
    {
        return <<<'CSS'
        .mgf-deck {
          display: grid;
          grid-template-columns: repeat(1, 1fr);
          gap: var(--mgf-space-6);
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
          min-height: 100vh;
          position: relative;
        }
        .mgf-deck-vertical { display: flex; flex-direction: column; gap: var(--mgf-space-4); }
        .mgf-deck-progress { position: absolute; top: var(--mgf-space-4); right: var(--mgf-space-4); display: flex; gap: 6px; z-index: 2; }
        .mgf-deck-dots { width: 10px; height: 10px; border-radius: 50%; background: var(--mgf-color-border); }
        .mgf-deck-dots[data-active="true"] { background: var(--mgf-color-accent); }
        .mgf-slide-size-16x9 { aspect-ratio: 16 / 9; max-width: 1280px; }
        .mgf-slide-size-4x3  { aspect-ratio: 4 / 3;  max-width: 1024px; }
        .mgf-slide-size-a4   { aspect-ratio: 1 / 1.414; max-width: 720px; }
        .mgf-slide-size-square { aspect-ratio: 1 / 1; max-width: 720px; }
        .mgf-deck-card { background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-lg); padding: var(--mgf-space-6); display: flex; flex-direction: column; gap: var(--mgf-space-3); }
        .mgf-deck-cta { display: inline-block; padding: var(--mgf-space-3) var(--mgf-space-6); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); border-radius: var(--mgf-radius-md); font-weight: 600; text-decoration: none; }
        .mgf-deck-feature { display: flex; gap: var(--mgf-space-4); align-items: flex-start; }
        .mgf-deck-feature-icon { width: 40px; height: 40px; border-radius: var(--mgf-radius-sm); background: var(--mgf-color-accent-soft); color: var(--mgf-color-accent); display: grid; place-items: center; font-weight: 700; flex: 0 0 40px; }
        .mgf-deck-team { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--mgf-space-4); }
        .mgf-deck-team-card { background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-md); padding: var(--mgf-space-4); text-align: center; }
        .mgf-deck-team-avatar { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--mgf-color-accent), var(--mgf-color-accent-2)); margin: 0 auto var(--mgf-space-3); display: grid; place-items: center; color: var(--mgf-color-text-inverse); font-size: var(--mgf-text-2xl); font-weight: 700; }
        .mgf-deck-table { width: 100%; border-collapse: collapse; }
        .mgf-deck-table th { text-align: left; padding: var(--mgf-space-3); background: var(--mgf-color-surface-2); }
        .mgf-deck-table td { padding: var(--mgf-space-3); border-bottom: 1px solid var(--mgf-color-border); }
        .mgf-deck-faq { display: flex; flex-direction: column; gap: var(--mgf-space-3); }
        .mgf-deck-faq-item { background: var(--mgf-color-surface); border: 1px solid var(--mgf-color-border); border-radius: var(--mgf-radius-md); padding: var(--mgf-space-4); }
        .mgf-deck-price { background: var(--mgf-color-surface); border: 2px solid var(--mgf-color-border-strong); border-radius: var(--mgf-radius-lg); padding: var(--mgf-space-8); text-align: center; }
        .mgf-deck-price[data-featured="true"] { border-color: var(--mgf-color-accent); box-shadow: 0 0 0 4px var(--mgf-color-accent-soft); }
        .mgf-deck-media { display: grid; place-items: center; background: var(--mgf-color-surface-2); border-radius: var(--mgf-radius-lg); aspect-ratio: 16 / 9; color: var(--mgf-color-text-secondary); }
        .mgf-mt-sm { margin-top: var(--mgf-space-3); }
        .mgf-mt-md { margin-top: var(--mgf-space-6); }
        .mgf-mt-lg { margin-top: var(--mgf-space-12); }
        .mgf-accent-bar { width: 60px; height: 3px; background: var(--mgf-color-accent); }
        CSS;
    }

    private function marketingDeckDataJson($owner): string
    {
        $features = [
            ['icon' => '⚡', 'title' => 'Live', 'desc' => 'No batch jobs. State changes are visible in 200ms.'],
            ['icon' => '◆',  'title' => 'Typed', 'desc' => 'Every record has a schema. No stringly-typed fields.'],
            ['icon' => '⊞',  'title' => 'Local', 'desc' => 'Reads served from edge caches. Latency under 30ms p95.'],
        ];
        $team = [
            ['name' => 'A. Hassan', 'role' => 'CEO', 'initials' => 'AH'],
            ['name' => 'M. Roy',    'role' => 'CTO', 'initials' => 'MR'],
            ['name' => 'L. Bauer',  'role' => 'VP Eng', 'initials' => 'LB'],
            ['name' => 'S. Patel',  'role' => 'VP Design', 'initials' => 'SP'],
        ];
        $pricing = [
            ['name' => 'Starter', 'price' => '$0', 'period' => '/forever', 'cta' => 'Use it', 'featured' => false],
            ['name' => 'Team',    'price' => '$49', 'period' => '/seat/mo', 'cta' => 'Start a trial', 'featured' => true],
            ['name' => 'Scale',   'price' => 'Talk to us', 'period' => '', 'cta' => 'Book a call', 'featured' => false],
        ];
        $faqs = [
            ['q' => 'How is this different from Postgres + a queue?', 'a' => 'It is Postgres. The queue is the database.'],
            ['q' => 'Can I run it on my own infra?', 'a' => 'Yes — single-container deploy.'],
            ['q' => 'Do you support SSO?', 'a' => 'SAML and OIDC, on the Team and Scale plans.'],
        ];
        $data = [
            '_meta' => [
                'title' => $owner->name,
                'description' => $owner->description,
                'archetype' => 'marketing-deck',
            ],
            'slides' => [
                ['id' => '01', 'eyebrow' => 'A new shape of database', 'title' => $owner->name, 'subtitle' => $owner->description],
                ['id' => '02', 'features' => $features],
                ['id' => '03', 'team' => $team],
                ['id' => '04', 'pricing' => $pricing],
                ['id' => '05', 'faqs' => $faqs],
                ['id' => '06', 'cta' => 'Start free', 'footer' => 'No credit card.'],
            ],
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function slideDeckCover(): string
    {
        return <<<'HTML'
        <!-- Component: deck cover with progress dots in the upper-right. -->
        <section class="mgf-deck mgf-slide-size-16x9">
          <div class="mgf-deck-progress" data-field="progress">
            <span class="mgf-deck-dots" data-active="true"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
          </div>
          <p class="mgf-eyebrow" data-field="eyebrow">A new shape of database</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-mt-md" data-field="title" style="font-size: var(--mgf-text-4xl); font-weight: 700; max-width: 900px;">Built for the workload of the next decade</h1>
          <p class="mgf-mt-md" data-field="subtitle" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary); max-width: 720px;">Real-time, schema-strict, and small enough to run on a single container.</p>
          <div class="mgf-mt-lg">
            <a class="mgf-deck-cta" href="#" data-field="cta_url">Start free</a>
          </div>
        </section>
        HTML;
    }

    private function slideDeckFeatures(): string
    {
        return <<<'HTML'
        <!-- Component: deck-feature grid. -->
        <section class="mgf-deck mgf-slide-size-16x9">
          <div class="mgf-deck-progress">
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots" data-active="true"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
          </div>
          <p class="mgf-eyebrow">Features</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Three things it does that nothing else does</h2>
          <div class="mgf-mt-lg" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--mgf-space-4);" data-field="features">
            <div class="mgf-deck-card">
              <div class="mgf-deck-feature-icon" data-field="icon">⚡</div>
              <h3 class="mgf-title-md" data-field="title">Live</h3>
              <p class="mgf-body" data-field="desc">No batch jobs. State changes are visible in 200ms.</p>
            </div>
            <div class="mgf-deck-card">
              <div class="mgf-deck-feature-icon" data-field="icon">◆</div>
              <h3 class="mgf-title-md" data-field="title">Typed</h3>
              <p class="mgf-body" data-field="desc">Every record has a schema. No stringly-typed fields.</p>
            </div>
            <div class="mgf-deck-card">
              <div class="mgf-deck-feature-icon" data-field="icon">⊞</div>
              <h3 class="mgf-title-md" data-field="title">Local</h3>
              <p class="mgf-body" data-field="desc">Reads served from edge caches. Latency under 30ms p95.</p>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDeckTeam(): string
    {
        return <<<'HTML'
        <!-- Component: deck-team grid (4 cards). -->
        <section class="mgf-deck mgf-slide-size-16x9">
          <div class="mgf-deck-progress">
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots" data-active="true"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
          </div>
          <p class="mgf-eyebrow">Team</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Who built it</h2>
          <div class="mgf-deck-team mgf-mt-lg" data-field="team">
            <div class="mgf-deck-team-card">
              <div class="mgf-deck-team-avatar" data-field="initials">AH</div>
              <p class="mgf-title-sm" data-field="name">A. Hassan</p>
              <p class="mgf-body" data-field="role">CEO</p>
            </div>
            <div class="mgf-deck-team-card">
              <div class="mgf-deck-team-avatar" data-field="initials">MR</div>
              <p class="mgf-title-sm" data-field="name">M. Roy</p>
              <p class="mgf-body" data-field="role">CTO</p>
            </div>
            <div class="mgf-deck-team-card">
              <div class="mgf-deck-team-avatar" data-field="initials">LB</div>
              <p class="mgf-title-sm" data-field="name">L. Bauer</p>
              <p class="mgf-body" data-field="role">VP Eng</p>
            </div>
            <div class="mgf-deck-team-card">
              <div class="mgf-deck-team-avatar" data-field="initials">SP</div>
              <p class="mgf-title-sm" data-field="name">S. Patel</p>
              <p class="mgf-body" data-field="role">VP Design</p>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDeckPricing(): string
    {
        return <<<'HTML'
        <!-- Component: deck-price columns (3 tiers). -->
        <section class="mgf-deck mgf-slide-size-16x9">
          <div class="mgf-deck-progress">
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots" data-active="true"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
          </div>
          <p class="mgf-eyebrow">Pricing</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Three plans, no surprise overage</h2>
          <div class="mgf-mt-lg" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--mgf-space-4);" data-field="pricing">
            <div class="mgf-deck-price" data-featured="false">
              <p class="mgf-eyebrow" data-field="name">Starter</p>
              <p class="mgf-stat-value-lg" data-field="price" style="margin-top: 0.5rem;">$0</p>
              <p class="mgf-hint" data-field="period">/forever</p>
              <a class="mgf-deck-cta mgf-mt-md" href="#" data-field="cta">Use it</a>
            </div>
            <div class="mgf-deck-price" data-featured="true">
              <p class="mgf-eyebrow mgf-accent-1" data-field="name">Team</p>
              <p class="mgf-stat-value-lg" data-field="price" style="margin-top: 0.5rem;">$49</p>
              <p class="mgf-hint" data-field="period">/seat/mo</p>
              <a class="mgf-deck-cta mgf-mt-md" href="#" data-field="cta">Start a trial</a>
            </div>
            <div class="mgf-deck-price" data-featured="false">
              <p class="mgf-eyebrow" data-field="name">Scale</p>
              <p class="mgf-stat-value-lg" data-field="price" style="margin-top: 0.5rem; font-size: var(--mgf-text-2xl);">Talk to us</p>
              <p class="mgf-hint" data-field="period">&nbsp;</p>
              <a class="mgf-deck-cta mgf-mt-md" href="#" data-field="cta">Book a call</a>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDeckFaq(): string
    {
        return <<<'HTML'
        <!-- Component: deck-faq list. -->
        <section class="mgf-deck mgf-slide-size-16x9">
          <div class="mgf-deck-progress">
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots" data-active="true"></span>
            <span class="mgf-deck-dots"></span>
          </div>
          <p class="mgf-eyebrow">FAQ</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Three things people ask first</h2>
          <div class="mgf-deck-faq mgf-mt-lg" data-field="faqs">
            <div class="mgf-deck-faq-item">
              <p class="mgf-title-sm" data-field="q">How is this different from Postgres + a queue?</p>
              <p class="mgf-body mgf-mt-sm" data-field="a">It is Postgres. The queue is the database.</p>
            </div>
            <div class="mgf-deck-faq-item">
              <p class="mgf-title-sm" data-field="q">Can I run it on my own infra?</p>
              <p class="mgf-body mgf-mt-sm" data-field="a">Yes — single-container deploy.</p>
            </div>
            <div class="mgf-deck-faq-item">
              <p class="mgf-title-sm" data-field="q">Do you support SSO?</p>
              <p class="mgf-body mgf-mt-sm" data-field="a">SAML and OIDC, on the Team and Scale plans.</p>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideDeckClosing(): string
    {
        return <<<'HTML'
        <!-- Component: deck closing — single CTA. -->
        <section class="mgf-deck mgf-slide-size-16x9" style="align-content: center; text-align: center;">
          <div class="mgf-deck-progress">
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots"></span>
            <span class="mgf-deck-dots" data-active="true"></span>
          </div>
          <div class="mgf-deck-media" data-field="media">
            <p class="mgf-eyebrow">Product walkthrough</p>
          </div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-3xl); font-weight: 700;">Spin up a free project in two minutes</h2>
          <p class="mgf-mt-md" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary);">No credit card needed.</p>
          <a class="mgf-deck-cta mgf-mt-lg" href="#" data-field="cta_url" data-label-field="cta">Start free</a>
          <p class="mgf-hint mgf-mt-md" data-field="footer">Or talk to us — hello@product.dev</p>
        </section>
        HTML;
    }

    // ─────────────────────────────────────────────────────────────────────
    // showcase-carousel — carousel + modern card variants
    //   Exercises §4 carousel (mgf-carousel, carousel-track, carousel-item,
    //   carousel-dots), §5 card variants (mgf-card-hover, mgf-card-glass,
    //   mgf-card-neo, mgf-card-footer, mgf-card-body, mgf-card-value),
    //   §5 stat-value-lg / stat-sub, reuses marquee + marks.
    // ─────────────────────────────────────────────────────────────────────

    /** 5 slides exercising carousel + card variants + marquee + marks. */
    protected function showcaseCarouselFiles($owner): array
    {
        return [
            ['layer' => 'meta',    'name' => 'meta.md',     'extension' => 'md',   'content' => "# {$owner->name}\n\n{$owner->description}"],
            ['layer' => 'context', 'name' => 'context.md',  'extension' => 'md',   'content' => $this->showcaseCarouselContext()],
            ['layer' => 'rules',   'name' => 'rules.md',    'extension' => 'md',   'content' => $this->rulesFor('showcase-carousel')],
            ['layer' => 'style',   'name' => 'style.css',   'extension' => 'css',  'content' => $this->showcaseCarouselStyleCss()],
            ['layer' => 'layout',  'name' => 'layout.css',  'extension' => 'css',  'content' => $this->showcaseCarouselLayoutCss()],
            ['layer' => 'content', 'name' => 'data.json',   'extension' => 'json', 'content' => $this->showcaseCarouselDataJson($owner)],

            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => $this->slideShowcaseCover()],
            ['layer' => 'slide', 'name' => 'slide-02.html', 'extension' => 'html', 'content' => $this->slideShowcaseCardGrid()],
            ['layer' => 'slide', 'name' => 'slide-03.html', 'extension' => 'html', 'content' => $this->slideShowcaseCarousel()],
            ['layer' => 'slide', 'name' => 'slide-04.html', 'extension' => 'html', 'content' => $this->slideShowcaseMarquee()],
            ['layer' => 'slide', 'name' => 'slide-05.html', 'extension' => 'html', 'content' => $this->slideShowcaseMarks()],
        ];
    }

    private function showcaseCarouselContext(): string
    {
        return <<<MD
        # Showcase Carousel Context

        ## Purpose
        A consumer-facing showcase deck for a design-system library.
        Each slide is a "look at this component" demo.

        ## Audience
        Designers and front-end engineers. The card variants shown here
        are the ones the library ships.

        ## Brand voice
        Bold, minimal, a single accent. Let the components speak.

        ## Visual constraints
        - Palette: white + black + magenta accent
        - Cards: hover, glass, and neo variants — same content, three looks
        - Carousel uses full-bleed horizontal scrolling
        MD;
    }

    private function showcaseCarouselStyleCss(): string
    {
        return <<<'CSS'
        :root {
          --mgf-color-bg:            #FFFFFF;
          --mgf-color-surface:       #FAFAFA;
          --mgf-color-surface-2:     #F0F0F2;
          --mgf-color-border:        #E5E5E8;
          --mgf-color-border-strong: #1A1A1F;
          --mgf-color-text-primary:  #0F0F12;
          --mgf-color-text-secondary:#6B6B75;
          --mgf-color-text-inverse:  #FFFFFF;
          --mgf-color-accent:        #D946EF;
          --mgf-color-accent-soft:   #FAE8FF;
          --mgf-color-accent-2:      #0EA5E9;

          --mgf-font-display: 'Inter', system-ui, sans-serif;
          --mgf-font-body:    'Inter', system-ui, sans-serif;
          --mgf-font-mono:    'JetBrains Mono', ui-monospace, monospace;

          --mgf-text-xs:   0.75rem;
          --mgf-text-sm:   0.875rem;
          --mgf-text-base: 1rem;
          --mgf-text-lg:   1.25rem;
          --mgf-text-xl:   1.75rem;
          --mgf-text-2xl:  2.25rem;
          --mgf-text-3xl:  3rem;
          --mgf-text-4xl:  4.5rem;

          --mgf-space-1:  0.25rem;
          --mgf-space-2:  0.5rem;
          --mgf-space-3:  0.75rem;
          --mgf-space-4:  1rem;
          --mgf-space-6:  1.5rem;
          --mgf-space-8:  2rem;
          --mgf-space-12: 3rem;
          --mgf-space-16: 4rem;
          --mgf-space-24: 6rem;

          --mgf-radius-sm: 4px;
          --mgf-radius-md: 10px;
          --mgf-radius-lg: 18px;
          --mgf-radius-xl: 28px;

          --mgf-slide-w:     1440px;
          --mgf-slide-h:     900px;
          --mgf-slide-pad-x: 64px;
          --mgf-slide-pad-y: 56px;

          --mgf-accent-line: 3px solid var(--mgf-color-accent);
          --mgf-divider:     1px solid var(--mgf-color-border);
        }
        CSS;
    }

    private function showcaseCarouselLayoutCss(): string
    {
        return <<<'CSS'
        .mgf-showcase {
          padding: var(--mgf-slide-pad-y) var(--mgf-slide-pad-x);
          background: var(--mgf-color-bg);
          color: var(--mgf-color-text-primary);
          font-family: var(--mgf-font-body);
          min-height: 100vh;
          display: flex; flex-direction: column; gap: var(--mgf-space-6);
        }
        /* Card variants */
        .mgf-card {
          background: var(--mgf-color-surface);
          border: 1px solid var(--mgf-color-border);
          border-radius: var(--mgf-radius-lg);
          padding: var(--mgf-space-6);
          display: flex; flex-direction: column; gap: var(--mgf-space-3);
        }
        .mgf-card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .mgf-card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
        .mgf-card-glass { background: rgba(255,255,255,0.5); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.6); }
        .mgf-card-neo { background: var(--mgf-color-surface); border: 2px solid var(--mgf-color-text-primary); box-shadow: 6px 6px 0 var(--mgf-color-text-primary); }
        .mgf-card-body { padding: var(--mgf-space-3) 0; }
        .mgf-card-footer { padding-top: var(--mgf-space-3); border-top: 1px solid var(--mgf-color-border); font-size: var(--mgf-text-sm); color: var(--mgf-color-text-secondary); }
        .mgf-card-value { font-size: var(--mgf-text-2xl); font-weight: 700; font-family: var(--mgf-font-display); }
        .mgf-stat-value-lg { font-size: var(--mgf-text-4xl); font-weight: 700; font-family: var(--mgf-font-display); }
        .mgf-stat-sub { font-size: var(--mgf-text-sm); color: var(--mgf-color-text-secondary); }
        /* Carousel */
        .mgf-carousel { position: relative; overflow: hidden; }
        .mgf-carousel-track { display: flex; gap: var(--mgf-space-6); overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: var(--mgf-space-3); }
        .mgf-carousel-item { flex: 0 0 320px; scroll-snap-align: start; }
        .mgf-carousel-dots { display: flex; gap: 6px; justify-content: center; padding-top: var(--mgf-space-3); }
        .mgf-carousel-dots span { width: 8px; height: 8px; border-radius: 50%; background: var(--mgf-color-border); }
        .mgf-carousel-dots span[data-active="true"] { background: var(--mgf-color-accent); }
        /* Marquee */
        .mgf-marquee { overflow: hidden; background: var(--mgf-color-surface); border-top: 1px solid var(--mgf-color-border); border-bottom: 1px solid var(--mgf-color-border); padding: var(--mgf-space-4) 0; }
        .mgf-marquee-track { display: flex; gap: var(--mgf-space-12); animation: mgf-marquee 30s linear infinite; width: max-content; }
        @keyframes mgf-marquee { from { transform: translateX(0) } to { transform: translateX(-50%) } }
        .mgf-marquee-item { font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary); font-family: var(--mgf-font-display); white-space: nowrap; }
        /* Marks */
        .mgf-marks { display: flex; flex-wrap: wrap; gap: var(--mgf-space-3); }
        .mgf-mark { padding: var(--mgf-space-2) var(--mgf-space-4); border: 1px solid var(--mgf-color-border-strong); border-radius: 999px; font-family: var(--mgf-font-display); font-size: var(--mgf-text-sm); background: var(--mgf-color-surface); }
        .mgf-spotlight { background: linear-gradient(135deg, var(--mgf-color-accent-soft) 0%, var(--mgf-color-surface-2) 100%); border-radius: var(--mgf-radius-xl); padding: var(--mgf-space-12); text-align: center; }
        .mgf-cta-solid { display: inline-block; padding: var(--mgf-space-3) var(--mgf-space-6); background: var(--mgf-color-accent); color: var(--mgf-color-text-inverse); border-radius: var(--mgf-radius-md); font-weight: 600; text-decoration: none; }
        .mgf-mt-sm { margin-top: var(--mgf-space-3); }
        .mgf-mt-md { margin-top: var(--mgf-space-6); }
        .mgf-mt-lg { margin-top: var(--mgf-space-12); }
        .mgf-accent-bar { width: 60px; height: 3px; background: var(--mgf-color-accent); }
        CSS;
    }

    private function showcaseCarouselDataJson($owner): string
    {
        $items = [
            ['name' => 'Pulse', 'tag' => 'Analytics', 'metric' => '4.2M', 'unit' => 'events/day', 'desc' => 'A real-time event pipeline with sub-200ms p95.'],
            ['name' => 'Loam',  'tag' => 'Climate',   'metric' => '12k',   'unit' => 'tCO₂e removed', 'desc' => 'Direct-air-capture operating data, plotted hourly.'],
            ['name' => 'Veil',  'tag' => 'Security',  'metric' => '0',    'unit' => 'CVEs in 6 months', 'desc' => 'FIDO2 + on-device risk scoring, no middle tier.'],
            ['name' => 'Quill', 'tag' => 'Editorial', 'metric' => '38',   'unit' => 'essayists', 'desc' => 'A long-form publication hand-curated every Friday.'],
            ['name' => 'Marble','tag' => 'Design',    'metric' => '240',  'unit' => 'components', 'desc' => 'A tokenized design system used by 60 teams.'],
        ];
        $marks = ['Linear', 'Vercel', 'Cloudflare', 'Stripe', 'Anthropic', 'OpenAI', 'Resend', 'Loom', 'Notion', 'Figma', 'Framer', 'Retool'];
        $data = [
            '_meta' => [
                'title' => $owner->name,
                'description' => $owner->description,
                'archetype' => 'showcase-carousel',
            ],
            'slides' => [
                ['id' => '01', 'eyebrow' => 'Component library', 'title' => $owner->name, 'subtitle' => 'A specimen deck for the card, carousel, and marquee families.'],
                ['id' => '02', 'kind' => 'card-grid'],
                ['id' => '03', 'kind' => 'carousel', 'items' => $items],
                ['id' => '04', 'kind' => 'marquee'],
                ['id' => '05', 'kind' => 'marks', 'items' => $marks],
            ],
        ];
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function slideShowcaseCover(): string
    {
        return <<<'HTML'
        <!-- Component: showcase cover. -->
        <section class="mgf-showcase">
          <p class="mgf-eyebrow" data-field="eyebrow">Component library</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h1 class="mgf-mt-md" data-field="title" style="font-size: var(--mgf-text-4xl); font-weight: 700; max-width: 900px;">Cards, carousel, and marquee</h1>
          <p class="mgf-mt-md" data-field="subtitle" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary); max-width: 720px;">A specimen deck for the families that ship the most in the consumer library.</p>
          <div class="mgf-spotlight mgf-mt-lg">
            <p class="mgf-eyebrow">Specimen</p>
            <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-3xl); font-weight: 700;">Same content, three looks</h2>
            <p class="mgf-mt-md" style="font-size: var(--mgf-text-lg); color: var(--mgf-color-text-secondary);">Hover, glass, and neo. The data is identical.</p>
          </div>
        </section>
        HTML;
    }

    private function slideShowcaseCardGrid(): string
    {
        return <<<'HTML'
        <!-- Component: card variants grid (mgf-card, mgf-card-hover, mgf-card-glass, mgf-card-neo). -->
        <section class="mgf-showcase">
          <p class="mgf-eyebrow">Card family</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Three looks, one record</h2>
          <div class="mgf-mt-lg" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--mgf-space-4);">
            <div class="mgf-card mgf-card-hover">
              <p class="mgf-eyebrow">Pulse</p>
              <p class="mgf-card-value">4.2M</p>
              <div class="mgf-card-body">
                <p class="mgf-body">events/day</p>
                <p class="mgf-stat-sub">A real-time event pipeline with sub-200ms p95.</p>
              </div>
              <div class="mgf-card-footer">last updated 14s ago</div>
            </div>
            <div class="mgf-card mgf-card-glass">
              <p class="mgf-eyebrow">Loam</p>
              <p class="mgf-card-value">12k</p>
              <div class="mgf-card-body">
                <p class="mgf-body">tCO₂e removed</p>
                <p class="mgf-stat-sub">Direct-air-capture operating data, plotted hourly.</p>
              </div>
              <div class="mgf-card-footer">operating data · live</div>
            </div>
            <div class="mgf-card mgf-card-neo">
              <p class="mgf-eyebrow">Veil</p>
              <p class="mgf-card-value">0</p>
              <div class="mgf-card-body">
                <p class="mgf-body">CVEs in 6 months</p>
                <p class="mgf-stat-sub">FIDO2 + on-device risk scoring, no middle tier.</p>
              </div>
              <div class="mgf-card-footer">audited · 2026</div>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideShowcaseCarousel(): string
    {
        return <<<'HTML'
        <!-- Component: carousel. -->
        <section class="mgf-showcase">
          <p class="mgf-eyebrow">Carousel</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Five products, one track</h2>
          <div class="mgf-carousel mgf-mt-lg" data-field="items">
            <div class="mgf-carousel-track">
              <div class="mgf-carousel-item"><div class="mgf-card mgf-card-hover"><p class="mgf-eyebrow" data-field="tag">Analytics</p><p class="mgf-card-value" data-field="metric">4.2M</p><p class="mgf-body" data-field="unit">events/day</p><p class="mgf-stat-sub" data-field="desc">Real-time event pipeline.</p></div></div>
              <div class="mgf-carousel-item"><div class="mgf-card mgf-card-hover"><p class="mgf-eyebrow" data-field="tag">Climate</p><p class="mgf-card-value" data-field="metric">12k</p><p class="mgf-body" data-field="unit">tCO₂e removed</p><p class="mgf-stat-sub" data-field="desc">Direct-air-capture in basalt.</p></div></div>
              <div class="mgf-carousel-item"><div class="mgf-card mgf-card-hover"><p class="mgf-eyebrow" data-field="tag">Security</p><p class="mgf-card-value" data-field="metric">0</p><p class="mgf-body" data-field="unit">CVEs in 6 months</p><p class="mgf-stat-sub" data-field="desc">FIDO2 + on-device risk.</p></div></div>
              <div class="mgf-carousel-item"><div class="mgf-card mgf-card-hover"><p class="mgf-eyebrow" data-field="tag">Editorial</p><p class="mgf-card-value" data-field="metric">38</p><p class="mgf-body" data-field="unit">essayists</p><p class="mgf-stat-sub" data-field="desc">Long-form, hand-curated.</p></div></div>
              <div class="mgf-carousel-item"><div class="mgf-card mgf-card-hover"><p class="mgf-eyebrow" data-field="tag">Design</p><p class="mgf-card-value" data-field="metric">240</p><p class="mgf-body" data-field="unit">components</p><p class="mgf-stat-sub" data-field="desc">A tokenized system.</p></div></div>
            </div>
            <div class="mgf-carousel-dots">
              <span data-active="true"></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideShowcaseMarquee(): string
    {
        return <<<'HTML'
        <!-- Component: marquee. -->
        <section class="mgf-showcase">
          <p class="mgf-eyebrow">Marquee</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">A scrolling customer strip</h2>
          <div class="mgf-marquee mgf-mt-lg">
            <div class="mgf-marquee-track">
              <span class="mgf-marquee-item">Vercel</span>
              <span class="mgf-marquee-item">Cloudflare</span>
              <span class="mgf-marquee-item">Linear</span>
              <span class="mgf-marquee-item">Resend</span>
              <span class="mgf-marquee-item">Stripe</span>
              <span class="mgf-marquee-item">Perplexity</span>
              <span class="mgf-marquee-item">Anthropic</span>
              <span class="mgf-marquee-item">OpenAI</span>
              <span class="mgf-marquee-item">Supabase</span>
              <span class="mgf-marquee-item">Neon</span>
              <span class="mgf-marquee-item">PlanetScale</span>
              <span class="mgf-marquee-item">Turso</span>
              <span class="mgf-marquee-item">Vercel</span>
              <span class="mgf-marquee-item">Cloudflare</span>
              <span class="mgf-marquee-item">Linear</span>
              <span class="mgf-marquee-item">Resend</span>
              <span class="mgf-marquee-item">Stripe</span>
              <span class="mgf-marquee-item">Perplexity</span>
              <span class="mgf-marquee-item">Anthropic</span>
              <span class="mgf-marquee-item">OpenAI</span>
              <span class="mgf-marquee-item">Supabase</span>
              <span class="mgf-marquee-item">Neon</span>
              <span class="mgf-marquee-item">PlanetScale</span>
              <span class="mgf-marquee-item">Turso</span>
            </div>
          </div>
        </section>
        HTML;
    }

    private function slideShowcaseMarks(): string
    {
        return <<<'HTML'
        <!-- Component: marks (pill-style tags). -->
        <section class="mgf-showcase">
          <p class="mgf-eyebrow">Marks</p>
          <div class="mgf-accent-bar mgf-mt-sm"></div>
          <h2 class="mgf-mt-md" style="font-size: var(--mgf-text-2xl); font-weight: 700;">Who is using it</h2>
          <div class="mgf-marks mgf-mt-lg" data-field="items">
            <span class="mgf-mark" data-field="label">Linear</span>
            <span class="mgf-mark" data-field="label">Vercel</span>
            <span class="mgf-mark" data-field="label">Cloudflare</span>
            <span class="mgf-mark" data-field="label">Stripe</span>
            <span class="mgf-mark" data-field="label">Anthropic</span>
            <span class="mgf-mark" data-field="label">OpenAI</span>
            <span class="mgf-mark" data-field="label">Resend</span>
            <span class="mgf-mark" data-field="label">Loom</span>
            <span class="mgf-mark" data-field="label">Notion</span>
            <span class="mgf-mark" data-field="label">Figma</span>
            <span class="mgf-mark" data-field="label">Framer</span>
            <span class="mgf-mark" data-field="label">Retool</span>
          </div>
        </section>
        HTML;
    }
}