<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\Concerns\MgfFileBuilders;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the public + private templates across MULTIPLE "design studios"
 * so the template gallery reads like a community marketplace rather than
 * a single-author showcase.
 *
 *   author@example.com   — General templates (11 archive entries).
 *   studio@example.com   — Studio Member (deck + editorial + bento).
 *   analyst@example.com  — Data Analyst (dashboard + code + deck).
 *   consumer@example.com — Consumer Curator (carousel + editorial + bento).
 *
 * Each user's templates are scoped to a few archetypes from the design
 * system so the MGF frontend can render a coherent gallery filter.
 */
class TemplateSeeder extends BaseSeeder
{
    use MgfFileBuilders;

    protected function seed(): void
    {
        $types = Type::all();

        // ── Fixed assignment of users to archetype specialties ──
        // Each user gets a coherent subset so the gallery feels like
        // four studios with different specializations.
        $author = User::factory()->create([
            'name' => 'Template Author',
            'email' => 'author@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $studio = User::factory()->create([
            'name' => 'Studio Member',
            'email' => 'studio@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $analyst = User::factory()->create([
            'name' => 'Data Analyst',
            'email' => 'analyst@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $consumer = User::factory()->create([
            'name' => 'Consumer Curator',
            'email' => 'consumer@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        // ── Author: the original 11 public templates ─────────────
        $authorTemplates = [
            [
                'name' => 'Business Pitch Deck',
                'description' => 'A professional pitch deck template for startup presentations',
                'thumbnail_url' => 'https://picsum.photos/seed/pitch/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'pitch', 'professional'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'pitch',
                'type' => 'presentation',
            ],
            [
                'name' => 'Fintech Series A Pitch',
                'description' => 'Investor deck template tuned for fintech startups with 60-second credit decisions.',
                'thumbnail_url' => 'https://picsum.photos/seed/fintech/400/300',
                'visibility' => 'public',
                'tags' => ['fintech', 'pitch', 'investor'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'pitch',
                'type' => 'presentation',
            ],
            [
                'name' => 'Creative Portfolio',
                'description' => 'Showcase your creative work with this modern portfolio template',
                'thumbnail_url' => 'https://picsum.photos/seed/portfolio/400/300',
                'visibility' => 'public',
                'tags' => ['creative', 'portfolio', 'minimal'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'summary',
                'type' => 'presentation',
            ],
            [
                'name' => 'Annual Report Poster',
                'description' => 'Clean and professional poster template for annual reports',
                'thumbnail_url' => 'https://picsum.photos/seed/report/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'report', 'professional'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'minimal',
                'type' => 'presentation',
            ],
            [
                'name' => 'Marketing Site Website',
                'description' => 'A scrollable single-page marketing website with hero, features, stats, testimonial, pricing, FAQ, CTA, and contact sections.',
                'thumbnail_url' => 'https://picsum.photos/seed/website/400/300',
                'visibility' => 'public',
                'tags' => ['marketing', 'website', 'landing-page'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'website',
                'type' => 'website',
            ],
            [
                'name' => 'Arabic Business Proposal',
                'description' => 'RTL template for Arabic business proposals — Cairo + Noto Naskh Arabic, dark navy + cyan accent.',
                'thumbnail_url' => 'https://picsum.photos/seed/arabic/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'arabic', 'rtl'],
                'locale' => 'ar', 'direction' => 'rtl',
                'archetype' => 'arabic-pitch',
                'type' => 'presentation',
            ],
            [
                'name' => 'Arabic Design Studio Site',
                'description' => 'RTL single-page website for design studios — Arabic-Indic digits, indigo + warm gold accent.',
                'thumbnail_url' => 'https://picsum.photos/seed/bayt/400/300',
                'visibility' => 'public',
                'tags' => ['design', 'arabic', 'rtl', 'website'],
                'locale' => 'ar', 'direction' => 'rtl',
                'archetype' => 'arabic-pitch',
                'type' => 'website',
            ],
            [
                'name' => 'Editorial Impact Report',
                'description' => 'A six-slide editorial infographic deck — Playfair Display + Source Serif 4, paper-cream + copper accent.',
                'thumbnail_url' => 'https://picsum.photos/seed/impact/400/300',
                'visibility' => 'public',
                'tags' => ['editorial', 'report', 'infographic'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'infographic',
                'type' => 'presentation',
            ],
            [
                'name' => 'KaTeX Math Reference Deck',
                'description' => 'A six-slide deck that exercises the KaTeX math contract — Source Serif 4 display + Inter body, double-escaped data-tex.',
                'thumbnail_url' => 'https://picsum.photos/seed/math/400/300',
                'visibility' => 'public',
                'tags' => ['math', 'reference', 'katex', 'editorial'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'academic-math',
                'type' => 'presentation',
            ],
            [
                'name' => 'Earth — Climate Pitch Deck',
                'description' => 'Climate / sustainability pitch deck — sand + olive + clay, Source Serif 4 display, hopeful tone.',
                'thumbnail_url' => 'https://picsum.photos/seed/earth/400/300',
                'visibility' => 'public',
                'tags' => ['climate', 'pitch', 'sustainability'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'earth-organic',
                'type' => 'presentation',
            ],
            [
                'name' => 'Neon — Cyber / Security Pitch Deck',
                'description' => 'Cyber / security pitch deck — deep black + electric magenta + cyan, JetBrains Mono display, terse tone.',
                'thumbnail_url' => 'https://picsum.photos/seed/neon/400/300',
                'visibility' => 'public',
                'tags' => ['security', 'pitch', 'fintech', 'cyber'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'neon-cyber',
                'type' => 'presentation',
            ],
            [
                'name' => 'Sunset — Warm Consumer Pitch Deck',
                'description' => 'Consumer / lifestyle pitch deck — warm peach + coral + soft indigo, Inter everywhere, optimistic tone.',
                'thumbnail_url' => 'https://picsum.photos/seed/sunset/400/300',
                'visibility' => 'public',
                'tags' => ['consumer', 'pitch', 'lifestyle', 'd2c'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'sunset-warm',
                'type' => 'presentation',
            ],
            [
                'name' => 'Editorial — Four-Slide Haiku Announcement',
                'description' => 'A four-slide announcement deck — pure white + near-black + single yellow accent, Inter everywhere, max 12 words per slide.',
                'thumbnail_url' => 'https://picsum.photos/seed/editorial/400/300',
                'visibility' => 'public',
                'tags' => ['announcement', 'editorial', 'minimal', 'haiku'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'monochrome-editorial',
                'type' => 'presentation',
            ],
            [
                'name' => 'Festival — Vibrant Event Pitch Deck',
                'description' => 'Event / festival pitch deck — cream + fuchsia + lime + orange, three accents, high energy.',
                'thumbnail_url' => 'https://picsum.photos/seed/festival/400/300',
                'visibility' => 'public',
                'tags' => ['event', 'festival', 'pitch'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'vibrant-festival',
                'type' => 'presentation',
            ],
        ];

        // ── Studio: marketing-deck + editorial-poster + bento ────
        $studioTemplates = [
            [
                'name' => 'Modern Marketing Deck',
                'description' => 'A six-slide B2B marketing deck — deck-progress dots, deck-card / deck-feature / deck-team / deck-price / deck-faq.',
                'thumbnail_url' => 'https://picsum.photos/seed/marketing-deck/400/300',
                'visibility' => 'public',
                'tags' => ['marketing', 'sales', 'deck', 'b2b'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'marketing-deck',
                'type' => 'presentation',
            ],
            [
                'name' => 'Editorial Style Guide Poster',
                'description' => 'A reference deck for the design-system background, frame, and modifier families — every slide is a swatch catalog.',
                'thumbnail_url' => 'https://picsum.photos/seed/style-guide/400/300',
                'visibility' => 'public',
                'tags' => ['design', 'system', 'reference', 'editorial'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'editorial-poster',
                'type' => 'presentation',
            ],
            [
                'name' => 'Bento Feature Tour',
                'description' => 'A six-slide feature tour using the bento grid — one feature per cell, sized by importance.',
                'thumbnail_url' => 'https://picsum.photos/seed/bento/400/300',
                'visibility' => 'public',
                'tags' => ['product', 'feature', 'bento', 'overview'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'bento-features',
                'type' => 'presentation',
            ],
            [
                'name' => 'Vibrant Festival — Sponsorship Deck',
                'description' => 'A six-slide sponsorship deck for an outdoor festival — pitch + line-up + sponsor tiers.',
                'thumbnail_url' => 'https://picsum.photos/seed/festival-sponsor/400/300',
                'visibility' => 'public',
                'tags' => ['event', 'festival', 'sponsorship'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'vibrant-festival',
                'type' => 'presentation',
            ],
            [
                'name' => 'Marketing Site Website — Studio',
                'description' => 'A scrollable single-page marketing site for a design studio.',
                'thumbnail_url' => 'https://picsum.photos/seed/studio-site/400/300',
                'visibility' => 'public',
                'tags' => ['marketing', 'website', 'studio'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'website',
                'type' => 'website',
            ],
        ];

        // ── Analyst: dashboard-analytics + code-tutorial + marketing-deck
        $analystTemplates = [
            [
                'name' => 'Product Analytics Dashboard',
                'description' => 'A six-slide dashboard wall — KPI grid, charts (bar, hbar, pie, donut, heatmap, radar, gauge, sparkline), legend.',
                'thumbnail_url' => 'https://picsum.photos/seed/dashboard/400/300',
                'visibility' => 'public',
                'tags' => ['analytics', 'dashboard', 'data', 'saas'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'dashboard-analytics',
                'type' => 'presentation',
            ],
            [
                'name' => 'SDK Code Walkthrough',
                'description' => 'A five-slide tutorial deck — code blocks with syntax highlighting, comparison table, signup form.',
                'thumbnail_url' => 'https://picsum.photos/seed/code-tutorial/400/300',
                'visibility' => 'public',
                'tags' => ['developer', 'sdk', 'tutorial', 'reference'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'code-tutorial',
                'type' => 'presentation',
            ],
            [
                'name' => 'Engineering Marketing Deck',
                'description' => 'A six-slide B2B engineering deck — deck-feature, deck-team, deck-price, deck-faq, deck-cta.',
                'thumbnail_url' => 'https://picsum.photos/seed/eng-deck/400/300',
                'visibility' => 'public',
                'tags' => ['engineering', 'sales', 'deck', 'dev'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'marketing-deck',
                'type' => 'presentation',
            ],
            [
                'name' => 'KaTeX Math Reference — Analyst',
                'description' => 'Math reference deck — eight equations, one per slide, with double-escaped data-tex.',
                'thumbnail_url' => 'https://picsum.photos/seed/math-analyst/400/300',
                'visibility' => 'public',
                'tags' => ['math', 'reference', 'katex'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'academic-math',
                'type' => 'presentation',
            ],
        ];

        // ── Consumer: showcase-carousel + editorial-poster + bento ──
        $consumerTemplates = [
            [
                'name' => 'Component Library Showcase',
                'description' => 'A five-slide specimen deck for the card, carousel, and marquee families — hover, glass, neo variants.',
                'thumbnail_url' => 'https://picsum.photos/seed/showcase/400/300',
                'visibility' => 'public',
                'tags' => ['design', 'components', 'system', 'showcase'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'showcase-carousel',
                'type' => 'presentation',
            ],
            [
                'name' => 'Editorial Color & Type Guide',
                'description' => 'A reference deck for the design-system background, frame, and modifier families — paper-cream palette.',
                'thumbnail_url' => 'https://picsum.photos/seed/poster/400/300',
                'visibility' => 'public',
                'tags' => ['design', 'system', 'reference', 'editorial'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'editorial-poster',
                'type' => 'presentation',
            ],
            [
                'name' => 'Bento Feature Tour — Consumer',
                'description' => 'A six-slide feature tour for a consumer app — one feature per bento cell, code-card + spotlight + marquee + marks.',
                'thumbnail_url' => 'https://picsum.photos/seed/bento-consumer/400/300',
                'visibility' => 'public',
                'tags' => ['product', 'feature', 'bento', 'consumer'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'bento-features',
                'type' => 'presentation',
            ],
            [
                'name' => 'Sunset — Warm Consumer Pitch',
                'description' => 'A consumer / lifestyle pitch deck — warm peach + coral + soft indigo.',
                'thumbnail_url' => 'https://picsum.photos/seed/sunset-consumer/400/300',
                'visibility' => 'public',
                'tags' => ['consumer', 'pitch', 'lifestyle', 'd2c'],
                'locale' => 'en', 'direction' => 'ltr',
                'archetype' => 'sunset-warm',
                'type' => 'presentation',
            ],
        ];

        $ru = fn (User $user, array $rows) => $this->createForUser($user, $rows, $types);

        $ru($author,   $authorTemplates);
        $ru($studio,   $studioTemplates);
        $ru($analyst,  $analystTemplates);
        $ru($consumer, $consumerTemplates);

        // A handful of generic factory-built public templates for
        // gallery variety. Distribute 1-2 across each user so the
        // factory noise doesn't all stack on one author.
        Template::factory(2)->public()->create(['user_id' => $author->id])->each(
            fn ($t) => $this->createMgfFiles($t, $author, 'minimal')
        );
        Template::factory(2)->public()->create(['user_id' => $studio->id])->each(
            fn ($t) => $this->createMgfFiles($t, $studio, 'minimal')
        );
        Template::factory(1)->public()->create(['user_id' => $analyst->id])->each(
            fn ($t) => $this->createMgfFiles($t, $analyst, 'minimal')
        );
    }

    /** Create a template per row and attach its archetype's MGF files. */
    private function createForUser(User $user, array $rows, $types): void
    {
        foreach ($rows as $templateData) {
            $type = $types->where('name', $templateData['type'])->first()
                ?? $types->where('name', 'presentation')->first();

            $archetype = $templateData['archetype'];
            unset($templateData['archetype'], $templateData['type']);

            $template = Template::factory()->create(array_merge($templateData, [
                'user_id' => $user->id,
                'type_id' => $type->id,
                'fork_count' => rand(0, 20),
                'upvote_count' => rand(0, 50),
            ]));

            $this->createMgfFiles($template, $user, $archetype);
        }
    }

    /**
     * Build a complete MGF file set for a template based on its archetype.
     * Same call as ProjectSeeder produces project files — forking a template
     * gives a new project the exact file set the template carried.
     */
    private function createMgfFiles(Template $template, User $user, string $archetype): void
    {
        $files = match ($archetype) {
            'pitch'                => $this->pitchFiles($template),
            'summary'              => $this->summaryFiles($template),
            'minimal'              => $this->minimalFiles($template),
            'website'              => $this->websiteFiles($template),
            'arabic-pitch'         => $this->arabicPitchFiles($template),
            'infographic'          => $this->infographicFiles($template),
            'academic-math'        => $this->academicMathFiles($template),
            'earth-organic'        => $this->earthOrganicFiles($template),
            'neon-cyber'           => $this->neonCyberFiles($template),
            'sunset-warm'          => $this->sunsetWarmFiles($template),
            'monochrome-editorial' => $this->monochromeEditorialFiles($template),
            'vibrant-festival'     => $this->vibrantFestivalFiles($template),
            'dashboard-analytics'  => $this->dashboardAnalyticsFiles($template),
            'bento-features'       => $this->bentoFeaturesFiles($template),
            'editorial-poster'     => $this->editorialPosterFiles($template),
            'code-tutorial'        => $this->codeTutorialFiles($template),
            'marketing-deck'       => $this->marketingDeckFiles($template),
            'showcase-carousel'    => $this->showcaseCarouselFiles($template),
            default                => $this->minimalFiles($template),
        };

        $this->persistFilesOnTemplate($template, $user, $files);
    }
}
