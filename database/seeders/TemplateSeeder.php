<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\Concerns\MgfFileBuilders;
use Illuminate\Support\Facades\Hash;

class TemplateSeeder extends BaseSeeder
{
    use MgfFileBuilders;

    protected function seed(): void
    {
        $user = User::factory()->create([
            'name' => 'Template Author',
            'email' => 'author@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $types = Type::all();

        // Eight public templates cover the six MGF archetype slots.
        // Forks carry the exact same file set the template has — see
        // MgfFileBuilders trait. The combination (template, locale,
        // direction, archetype) gives the fork gallery a visible
        // spread of palette + typography + locale presets.
        //
        //   - Business Pitch Deck        → pitch          (10 slides + theme variant)
        //   - Fintech Pitch (Series A)   → pitch          (10 slides, identity-tuned)
        //   - Creative Portfolio         → summary        ( 8 slides)
        //   - Annual Report Poster       → minimal        ( 2 slides)
        //   - Marketing Site Website     → website        ( 8 sections)
        //   - Arabic Business Proposal   → arabic-pitch   ( 8 slides, RTL)
        //   - Arabic Design Studio Site  → arabic-pitch   ( 8 sections, RTL)
        //   - Editorial Impact Report    → infographic    ( 6 slides, serif)
        $templates = [
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
            // ── Six new archetypes (added 2026-08) ──────────────────
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

        foreach ($templates as $templateData) {
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

        // A handful of generic factory-built public templates for
        // gallery variety. Each gets minimal scaffold files so the
        // fork gallery always has something to render.
        Template::factory(5)->public()->create()->each(function ($template) use ($user) {
            $this->createMgfFiles($template, $user, 'minimal');
        });
    }

    /**
     * Build a complete MGF file set for a template based on its archetype.
     * Same call as ProjectSeeder produces project files — forking a template
     * gives a new project the exact file set the template carried.
     */
    private function createMgfFiles(Template $template, User $user, string $archetype): void
    {
        $files = match ($archetype) {
            'pitch'            => $this->pitchFiles($template),
            'summary'          => $this->summaryFiles($template),
            'website'          => $this->websiteFiles($template),
            'arabic-pitch'     => $this->arabicPitchFiles($template),
            'infographic'      => $this->infographicFiles($template),
            'academic-math'    => $this->academicMathFiles($template),
            'earth-organic'    => $this->earthOrganicFiles($template),
            'neon-cyber'       => $this->neonCyberFiles($template),
            'sunset-warm'      => $this->sunsetWarmFiles($template),
            'monochrome-editorial' => $this->monochromeEditorialFiles($template),
            'vibrant-festival' => $this->vibrantFestivalFiles($template),
            default            => $this->minimalFiles($template),
        };

        $this->persistFilesOnTemplate($template, $user, $files);
    }
}