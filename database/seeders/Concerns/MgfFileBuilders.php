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
            'pitch'   => "Slide titles under 8 words.\nMax 40 words per slide body.",
            'website' => "Section titles under 10 words.\nSections are full-width bands of a single scrollable page — no fixed viewport.\nNo mgf-slide-number footer (scrollable pages do not have a 1-of-N counter).",
            default   => "Use bullets and tables.\nKeep prose under 40 words per slide.",
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
}