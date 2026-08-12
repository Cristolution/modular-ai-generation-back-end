<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\Concerns\MgfFileBuilders;
use Illuminate\Support\Facades\Hash;

class ProjectSeeder extends BaseSeeder
{
    use MgfFileBuilders;

    protected function seed(): void
    {
        $user = User::factory()->create([
            'name' => 'Project Owner',
            'email' => 'projects@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $types = Type::all();
        $templates = Template::all();

        // 17 hand-built projects across 4 archetype families and 2
        // locales, mirroring the bundles documented in
        // modular-ai-generation-front-end/docs/superpowers/seed-data/.
        //
        //   pitch         — 4 variations (fintech, healthtech, climate, consumer)
        //   summary       — 1 variation (executive summary)
        //   minimal       — 1 variation (annual report scaffold)
        //   website       — 3 variations (saas, agency, ecommerce)
        //   arabic-pitch  — 2 variations (fintech, executive summary)
        //   arabic-website— 1 variation (design studio)
        //   infographic   — 2 variations (annual report, product explainer)
        //   ────────────
        //   total         = 17 projects
        //
        // Each entry produces locale-aware MGF files via the trait,
        // with archetype-specific style.css / data.json / slide-NN.html.
        $archetypes = [
            // ── Pitch family (English) ─────────────────────────────────
            [
                'name' => 'Cleartab — SMB Credit in 60 Seconds',
                'description' => 'Series A pitch for a fintech that issues SMB credit decisions in 60 seconds.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['fintech', 'pitch', 'investor', 'saas'],
                'archetype' => 'pitch', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Pulsestat — At-Home Cardiac Monitor',
                'description' => 'Series A pitch for a connected cardiac monitor that flags arrhythmias a week in advance.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['healthtech', 'pitch', 'consumer'],
                'archetype' => 'pitch', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Loamgrid — DAC for Hot Climates',
                'description' => 'Seed-stage pitch for a direct-air-capture system optimized for arid regions.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['climate', 'pitch', 'hardware'],
                'archetype' => 'pitch', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Marask — DTC Hair Care for the Gulf',
                'description' => 'Pre-seed pitch for a direct-to-consumer hair care brand built for hot, humid climates.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['consumer', 'pitch', 'd2c'],
                'archetype' => 'pitch', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Summary family (English) ──────────────────────────────
            [
                'name' => 'My Business Pitch',
                'description' => 'A compelling investor pitch deck for a B2B SaaS startup.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['business', 'pitch', 'investor'],
                'archetype' => 'summary', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Product Launch Plan',
                'description' => 'A presentation summarizing the launch plan for our new product.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['product', 'launch', 'marketing'],
                'archetype' => 'summary', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Minimal family (English) ──────────────────────────────
            [
                'name' => 'Annual Report 2026',
                'description' => 'A scaffolded annual report presentation in early draft stage.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['business', 'report'],
                'archetype' => 'minimal', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Website family (English) ──────────────────────────────
            [
                'name' => 'Northwind — B2B SaaS Marketing Site',
                'description' => 'A scrollable single-page marketing site for a B2B SaaS — calm slate + indigo.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['marketing', 'website', 'saas'],
                'archetype' => 'website', 'type' => 'website',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Folio — Design Agency Portfolio',
                'description' => 'A portfolio-style single-page site for a small design agency.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['marketing', 'website', 'portfolio', 'agency'],
                'archetype' => 'website', 'type' => 'website',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Kettler — Coffee Gear Ecommerce',
                'description' => 'A single-page ecommerce site for a specialty coffee gear brand.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['marketing', 'website', 'ecommerce'],
                'archetype' => 'website', 'type' => 'website',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Arabic-pitch family (RTL) ─────────────────────────────
            [
                'name' => 'نملة — حلول ائتمانية للشركات الصغيرة',
                'description' => 'سلسلة أ لمنصة ذكاء اصطناعي تربط الشركات الصغيرة بمقرضين مؤسسيين.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['fintech', 'pitch', 'mena'],
                'archetype' => 'arabic-pitch', 'type' => 'presentation',
                'locale' => 'ar', 'direction' => 'rtl',
            ],
            [
                'name' => 'نبذة تنفيذية — شركة تقنية مالية خليجية',
                'description' => 'ملخص تنفيذي لمشروع تقني في قطاع الخدمات المالية.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['finance', 'summary'],
                'archetype' => 'arabic-pitch', 'type' => 'presentation',
                'locale' => 'ar', 'direction' => 'rtl',
            ],

            // ── Arabic-website family (RTL) ────────────────────────────
            [
                'name' => 'بيت — استوديو تصميم في عمّان',
                'description' => 'موقع استوديو تصميم أردني يقدّم هوية بصرية وتصاميم رقمية.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['design', 'website', 'agency'],
                'archetype' => 'arabic-pitch', 'type' => 'website',
                'locale' => 'ar', 'direction' => 'rtl',
            ],

            // ── Infographic family (English, editorial) ───────────────
            [
                'name' => 'Atlas Foundation — 2025 Impact Report',
                'description' => 'Editorial-style annual report: scholarship program across 37 partner schools.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['nonprofit', 'report', 'editorial'],
                'archetype' => 'infographic', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'How a Heat Pump Works',
                'description' => 'Engineering explainer for residential heat pump systems, COP stats and savings.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['climate', 'explainer', 'engineering'],
                'archetype' => 'infographic', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Academic-math family (KaTeX reference deck) ──────────
            // Reference deck used to verify the math.md render contract.
            [
                'name' => 'KaTeX Reference Deck — Algebra to Quantum',
                'description' => 'Six-slide reference deck for the KaTeX math contract — algebra, identity, calculus, matrices, differential equations, and a closing wave.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['math', 'reference', 'katex', 'engineering'],
                'archetype' => 'academic-math', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Field Notes — Mathematical Beauty',
                'description' => 'A short supplemental deck for the math reference card — six equations, one per slide.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['math', 'editorial'],
                'archetype' => 'academic-math', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Earth-organic family (climate/sustainability pitch) ──
            [
                'name' => 'Loamgrid — Mineralizing CO2 in Basalt',
                'description' => 'Series B pitch for a direct-air-capture system that mineralizes CO2 in basalt — Iceland pilot + Oman commercial.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['climate', 'pitch', 'dac', 'hardware'],
                'archetype' => 'earth-organic', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Kelpworks — Seaweed Sinks for Tropical Coasts',
                'description' => 'Seed-stage pitch for a community-owned seaweed-sink network across Indonesia and the Philippines.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['climate', 'pitch', 'ocean', 'community'],
                'archetype' => 'earth-organic', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Neon-cyber family (fintech-security pitch) ───────────
            [
                'name' => 'Vitral — Device-Bound Authentication',
                'description' => 'Series A pitch for a passwordless authentication platform with FIDO2 + on-device risk scoring.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['security', 'pitch', 'fintech', 'auth'],
                'archetype' => 'neon-cyber', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Halberd — Continuous Penetration Testing',
                'description' => 'Series A pitch for an autonomous pen-test platform that runs full kill-chains weekly against SaaS.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['security', 'pitch', 'devtools'],
                'archetype' => 'neon-cyber', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Sunset-warm family (consumer / lifestyle pitch) ─────
            [
                'name' => 'Marask — DTC Hair Care for the Gulf',
                'description' => 'Pre-seed pitch for a personalized DTC hair-care brand built for hot, humid climates.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['consumer', 'pitch', 'd2c', 'beauty'],
                'archetype' => 'sunset-warm', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Slowroom — A Coffee Subscription for Slow Mornings',
                'description' => 'Pre-seed pitch for a curated weekly coffee subscription, paired with a one-page weekly newsletter.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['consumer', 'pitch', 'lifestyle'],
                'archetype' => 'sunset-warm', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Monochrome-editorial family (haiku announcements) ───
            [
                'name' => 'Studio Arrow — Launch Announcement',
                'description' => 'Four-slide announcement deck for a one-indie design studio — haiku + quote + closing.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['announcement', 'editorial', 'minimal'],
                'archetype' => 'monochrome-editorial', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Quietbeam — A New Editorial Channel',
                'description' => 'A four-slide press release for a small editorial publication — quiet, specific, deliberate.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['announcement', 'editorial', 'press'],
                'archetype' => 'monochrome-editorial', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],

            // ── Vibrant-festival family (event / festival pitch) ────
            [
                'name' => 'Summerglow — An Audience-Curated Festival',
                'description' => 'Sponsorship deck for a three-day outdoor festival with crowdsourced lineups and a zero-waste site.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['event', 'festival', 'pitch'],
                'archetype' => 'vibrant-festival', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Frontera — A Borderless Music Conference',
                'description' => 'Pitch for a cross-border music conference in Casablanca / Tangier — three days, four stages, 80+ acts.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['event', 'music', 'mena'],
                'archetype' => 'vibrant-festival', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
        ];

        foreach ($archetypes as $projectData) {
            $type = $types->where('name', $projectData['type'])->first() ?? $types->where('name', 'presentation')->first();

            $project = Project::factory()->create([
                'user_id' => $user->id,
                'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
                'type_id' => $type->id,
                'name' => $projectData['name'],
                'description' => $projectData['description'],
                'status' => $projectData['status'],
                'visibility' => $projectData['visibility'],
                'tags' => $projectData['tags'],
                'locale' => $projectData['locale'],
                'direction' => $projectData['direction'],
                'cloned_at' => $templates->isNotEmpty() ? now() : null,
            ]);

            $this->createMgfFiles($project, $user, $projectData['archetype']);
        }

        // A handful of generic factory-built drafts so the user has
        // empty/scratch surfaces to experiment with.
        Project::factory(3)->create([
            'user_id' => $user->id,
            'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
            'cloned_at' => $templates->isNotEmpty() ? now() : null,
        ])->each(function ($project) use ($user) {
            $this->createMgfFiles($project, $user, 'minimal');
        });
    }

    /**
     * Build a complete MGF file set for a project based on its archetype.
     * Delegates to the MgfFileBuilders trait — same files are produced for
     * any owner (template fork produces a clone with the same file set).
     */
    private function createMgfFiles(Project $project, User $user, string $archetype): void
    {
        $files = match ($archetype) {
            'pitch'            => $this->pitchFiles($project),
            'summary'          => $this->summaryFiles($project),
            'website'          => $this->websiteFiles($project),
            'arabic-pitch'     => $this->arabicPitchFiles($project),
            'infographic'      => $this->infographicFiles($project),
            'academic-math'    => $this->academicMathFiles($project),
            'earth-organic'    => $this->earthOrganicFiles($project),
            'neon-cyber'       => $this->neonCyberFiles($project),
            'sunset-warm'      => $this->sunsetWarmFiles($project),
            'monochrome-editorial' => $this->monochromeEditorialFiles($project),
            'vibrant-festival' => $this->vibrantFestivalFiles($project),
            default            => $this->minimalFiles($project),
        };

        $this->persistFilesOnProject($project, $user, $files);
    }
}