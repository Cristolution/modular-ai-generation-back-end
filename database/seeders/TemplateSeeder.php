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

        // Five public templates map to MGF archetypes. Forks carry the
        // exact same file set the template has — see MgfFileBuilders trait.
        //
        //   - Business Pitch Deck       → pitch   (10 slides + theme variant)
        //   - Creative Portfolio        → summary ( 8 slides)
        //   - Annual Report Poster      → minimal ( 2 slides)
        //   - Social Media Carousel     → minimal ( 2 slides)
        //   - Arabic Business Proposal  → pitch   (10 slides, RTL)
        $templates = [
            [
                'name' => 'Business Pitch Deck',
                'description' => 'A professional pitch deck template for startup presentations',
                'thumbnail_url' => 'https://picsum.photos/seed/pitch/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'pitch', 'professional'],
                'locale' => 'en',
                'direction' => 'ltr',
                'archetype' => 'pitch',
            ],
            [
                'name' => 'Creative Portfolio',
                'description' => 'Showcase your creative work with this modern portfolio template',
                'thumbnail_url' => 'https://picsum.photos/seed/portfolio/400/300',
                'visibility' => 'public',
                'tags' => ['creative', 'portfolio', 'minimal'],
                'locale' => 'en',
                'direction' => 'ltr',
                'archetype' => 'summary',
            ],
            [
                'name' => 'Annual Report Poster',
                'description' => 'Clean and professional poster template for annual reports',
                'thumbnail_url' => 'https://picsum.photos/seed/report/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'report', 'professional'],
                'locale' => 'en',
                'direction' => 'ltr',
                'archetype' => 'minimal',
            ],
            [
                'name' => 'Social Media Carousel',
                'description' => 'Engaging carousel template for Instagram and LinkedIn',
                'thumbnail_url' => 'https://picsum.photos/seed/carousel/400/300',
                'visibility' => 'public',
                'tags' => ['social', 'marketing', 'colorful'],
                'locale' => 'en',
                'direction' => 'ltr',
                'archetype' => 'minimal',
            ],
            [
                'name' => 'Arabic Business Proposal',
                'description' => 'RTL template for Arabic business proposals',
                'thumbnail_url' => 'https://picsum.photos/seed/arabic/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'arabic', 'rtl'],
                'locale' => 'ar',
                'direction' => 'rtl',
                'archetype' => 'pitch',
            ],
        ];

        foreach ($templates as $templateData) {
            $type = $types->where('name', 'presentation')->first();
            if (str_contains(strtolower($templateData['name']), 'carousel')) {
                $type = $types->where('name', 'carousel')->first();
            } elseif (str_contains(strtolower($templateData['name']), 'poster')) {
                $type = $types->where('name', 'poster')->first();
            }

            $archetype = $templateData['archetype'];
            unset($templateData['archetype']);

            $template = Template::factory()->create(array_merge($templateData, [
                'user_id' => $user->id,
                'type_id' => $type->id,
                'fork_count' => rand(0, 20),
                'upvote_count' => rand(0, 50),
            ]));

            $this->createMgfFiles($template, $user, $archetype);
        }

        // Five additional generic templates for variety.
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
            'pitch'   => $this->pitchFiles($template),
            'summary' => $this->summaryFiles($template),
            default   => $this->minimalFiles($template),
        };

        $this->persistFilesOnTemplate($template, $user, $files);
    }
}
