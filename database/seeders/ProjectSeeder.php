<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\Concerns\MgfFileBuilders;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds projects across MULTIPLE users so the project gallery reads
 * like a community workspace, not a single-author scratch pad.
 *
 *   projects@example.com — Original 27 hand-built projects (archive).
 *   studio@example.com   — Studio Member projects (marketing + bento).
 *   analyst@example.com  — Data Analyst projects (dashboard + code).
 *   consumer@example.com — Consumer Curator projects (carousel + showcase).
 *
 * Each user gets a coherent family of projects so the project detail
 * view can render rich MGF previews.
 */
class ProjectSeeder extends BaseSeeder
{
    use MgfFileBuilders;

    protected function seed(): void
    {
        $types = Type::all();
        $templates = Template::all();

        // Resolve users by email — use firstOrCreate so the order of
        // seeders doesn't matter (TemplateSeeder may have created
        // them already).
        $projects = $this->findOrCreateUser(
            'Project Owner',
            'projects@example.com'
        );
        $studio = $this->findOrCreateUser(
            'Studio Member',
            'studio@example.com'
        );
        $analyst = $this->findOrCreateUser(
            'Data Analyst',
            'analyst@example.com'
        );
        $consumer = $this->findOrCreateUser(
            'Consumer Curator',
            'consumer@example.com'
        );

        // ── Project Owner: original 27 archive entries ───────────
        $ownerProjects = [
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
            // ── Minimal family (English) ─────────────────────────────
            [
                'name' => 'Annual Report 2026',
                'description' => 'A scaffolded annual report presentation in early draft stage.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['business', 'report'],
                'archetype' => 'minimal', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            // ── Website family (English) ─────────────────────────────
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

        // ── Studio: marketing-deck + bento + poster ───────────────
        $studioProjects = [
            [
                'name' => 'Studio Pitch — New Brand Identity',
                'description' => 'A six-slide deck for a brand-identity pitch — deck-feature, deck-team, deck-price, deck-faq, deck-cta.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['agency', 'pitch', 'design'],
                'archetype' => 'marketing-deck', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Bento Feature Tour — Studio',
                'description' => 'A six-slide feature tour for a product redesign — bento grid, code-card, spotlight, marquee, marks.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['product', 'feature', 'bento'],
                'archetype' => 'bento-features', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Style Guide — Reference Poster',
                'description' => 'A reference deck for the design-system background, frame, and modifier families.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['design', 'system', 'reference'],
                'archetype' => 'editorial-poster', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Studio Site — Marketing Page',
                'description' => 'A scrollable single-page site for a one-person design studio.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['marketing', 'website', 'studio'],
                'archetype' => 'website', 'type' => 'website',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Festival Sponsorship Deck',
                'description' => 'A six-slide sponsorship deck for a local music festival.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['event', 'festival', 'sponsorship'],
                'archetype' => 'vibrant-festival', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
        ];

        // ── Analyst: dashboard + code-tutorial + deck ────────────
        $analystProjects = [
            [
                'name' => 'Q3 SaaS Metrics Dashboard',
                'description' => 'A six-slide dashboard wall — MRR, active orgs, conversion, NRR; bar / hbar / pie / donut / heatmap / radar / gauge / sparkline.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['analytics', 'dashboard', 'saas'],
                'archetype' => 'dashboard-analytics', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Churn-Funnel Analytics Dashboard',
                'description' => 'A six-slide dashboard for the weekly churn funnel — KPIs, channel mix, cohort heatmap.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['analytics', 'dashboard', 'churn'],
                'archetype' => 'dashboard-analytics', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'SDK Reference — Five Slides',
                'description' => 'A five-slide SDK walkthrough — code blocks with syntax highlighting, comparison table, signup form.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['developer', 'sdk', 'tutorial'],
                'archetype' => 'code-tutorial', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Engineering Marketing Deck — Analyst',
                'description' => 'A six-slide engineering deck for a developer-tools product.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['engineering', 'sales', 'deck'],
                'archetype' => 'marketing-deck', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Math Reference — Six Equations',
                'description' => 'Six equations, one per slide — KaTeX math contract.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['math', 'reference', 'katex'],
                'archetype' => 'academic-math', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
        ];

        // ── Consumer: carousel + bento + poster ───────────────────
        $consumerProjects = [
            [
                'name' => 'Component Library Showcase',
                'description' => 'A five-slide specimen deck for the card, carousel, and marquee families — hover, glass, neo variants.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['design', 'components', 'showcase'],
                'archetype' => 'showcase-carousel', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Bento Feature Tour — Consumer',
                'description' => 'A six-slide feature tour for a consumer app — bento grid + code-card + spotlight + marquee + marks.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['product', 'feature', 'bento'],
                'archetype' => 'bento-features', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Editorial Color & Type Guide',
                'description' => 'A reference deck for the design-system background, frame, and modifier families.',
                'status' => 'published', 'visibility' => 'public',
                'tags' => ['design', 'system', 'reference'],
                'archetype' => 'editorial-poster', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
            [
                'name' => 'Slowroom — DTC Coffee Subscription',
                'description' => 'A consumer / lifestyle pitch deck — warm peach + coral + soft indigo.',
                'status' => 'draft', 'visibility' => 'private',
                'tags' => ['consumer', 'pitch', 'lifestyle'],
                'archetype' => 'sunset-warm', 'type' => 'presentation',
                'locale' => 'en', 'direction' => 'ltr',
            ],
        ];

        $rp = fn (User $user, array $rows) => $this->createProjectsForUser($user, $rows, $types, $templates);

        $rp($projects, $ownerProjects);
        $rp($studio,   $studioProjects);
        $rp($analyst,  $analystProjects);
        $rp($consumer, $consumerProjects);

        // ── Bundle projects — one fork per bundle template ────────
        // The bundle templates were just created in TemplateSeeder.
        // We add ONE clone per bundle so the gallery has a complete
        // fork graph without doubling the seeded project count with
        // "(Draft N)" variants — the design system is best evaluated
        // when each bundle renders once, not five half-clones.
        $bundleTemplateNames = [
            'mgf-deck'          => 'Crist — One Vocabulary',
            'mgf-components'    => 'Crist — Cards in Every Flavor',
            'anti-ai-studio'    => 'Crist — Anti-AI Studio',
            'mgf-rtl'           => 'MGF — ملف واحد، كل العلامات',
            'mgf-showcase'      => 'Crist — Diagrams → Static SVG',
            'mgf-themes'        => 'Crist — Theme Gallery',
            'mgf-website'       => 'Crist — MGF Studio Site',
            'summary-archetype' => 'Crist — Q1 2026 Summary',
        ];

        // User pool — each bundle project goes to a user OTHER than
        // its template's author so the fork graph has real edges.
        $bundleUserPool = [$projects, $studio, $analyst, $consumer];

        $bundleUserIndex = 0;
        foreach ($bundleTemplateNames as $bundle => $templateName) {
            $template = $templates->firstWhere('name', $templateName);

            if ($template === null) {
                continue;
            }

            // Cycle through the pool, skipping the template's author.
            do {
                $owner = $bundleUserPool[$bundleUserIndex % count($bundleUserPool)];
                $bundleUserIndex++;
            } while ($owner->id === $template->user_id && $bundleUserIndex < count($bundleUserPool) * 2);

            $type = $types->where('name', $template->type?->name ?? 'presentation')->first()
                ?? $types->where('name', 'presentation')->first();

            $project = Project::factory()->create([
                'user_id'     => $owner->id,
                'template_id' => $template->id,
                'type_id'     => $type->id,
                'name'        => $template->name,
                'description' => $template->description,
                'status'      => 'published',
                'visibility'  => 'public',
                'tags'        => $template->tags ?? [],
                'locale'      => $template->locale ?? 'en',
                'direction'   => $template->direction ?? 'ltr',
                'cloned_at'   => now(),
            ]);

            $files = $this->bundleFiles($bundle);
            $this->persistFilesOnProject($project, $owner, $files);
        }

        // A handful of generic factory-built drafts so each user has
        // empty/scratch surfaces to experiment with.
        Project::factory(2)->create([
            'user_id' => $projects->id,
            'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
            'cloned_at' => $templates->isNotEmpty() ? now() : null,
        ])->each(fn ($p) => $this->createMgfFiles($p, $projects, 'minimal'));

        Project::factory(2)->create([
            'user_id' => $studio->id,
            'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
            'cloned_at' => $templates->isNotEmpty() ? now() : null,
        ])->each(fn ($p) => $this->createMgfFiles($p, $studio, 'minimal'));

        Project::factory(1)->create([
            'user_id' => $analyst->id,
            'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
            'cloned_at' => $templates->isNotEmpty() ? now() : null,
        ])->each(fn ($p) => $this->createMgfFiles($p, $analyst, 'minimal'));
    }

    /** Find or create a user by email — used so seeder order doesn't matter. */
    private function findOrCreateUser(string $name, string $email): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password_hash' => Hash::make('password'),
            ]
        );
    }

    /** Create a project per row and attach its archetype's MGF files. */
    private function createProjectsForUser(User $user, array $rows, $types, $templates): void
    {
        foreach ($rows as $projectData) {
            $type = $types->where('name', $projectData['type'])->first()
                ?? $types->where('name', 'presentation')->first();

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
    }

    /**
     * Build a complete MGF file set for a project based on its archetype.
     * Delegates to the MgfFileBuilders trait — same files are produced for
     * any owner (template fork produces a clone with the same file set).
     */
    private function createMgfFiles(Project $project, User $user, string $archetype): void
    {
        $files = match ($archetype) {
            'pitch'                => $this->pitchFiles($project),
            'summary'              => $this->summaryFiles($project),
            'minimal'              => $this->minimalFiles($project),
            'website'              => $this->websiteFiles($project),
            'arabic-pitch'         => $this->arabicPitchFiles($project),
            'infographic'          => $this->infographicFiles($project),
            'academic-math'        => $this->academicMathFiles($project),
            'earth-organic'        => $this->earthOrganicFiles($project),
            'neon-cyber'           => $this->neonCyberFiles($project),
            'sunset-warm'          => $this->sunsetWarmFiles($project),
            'monochrome-editorial' => $this->monochromeEditorialFiles($project),
            'vibrant-festival'     => $this->vibrantFestivalFiles($project),
            'dashboard-analytics'  => $this->dashboardAnalyticsFiles($project),
            'bento-features'       => $this->bentoFeaturesFiles($project),
            'editorial-poster'     => $this->editorialPosterFiles($project),
            'code-tutorial'        => $this->codeTutorialFiles($project),
            'marketing-deck'       => $this->marketingDeckFiles($project),
            'showcase-carousel'    => $this->showcaseCarouselFiles($project),
            default                => $this->minimalFiles($project),
        };

        $this->persistFilesOnProject($project, $user, $files);
    }
}
