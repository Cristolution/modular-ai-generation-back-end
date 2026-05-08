<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TemplateSeeder extends BaseSeeder
{
    protected function seed(): void
    {
        $user = User::factory()->create([
            'name' => 'Template Author',
            'email' => 'author@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $types = Type::all();

        $templates = [
            [
                'name' => 'Business Pitch Deck',
                'description' => 'A professional pitch deck template for startup presentations',
                'thumbnail_url' => 'https://picsum.photos/seed/pitch/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'pitch', 'professional'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
            [
                'name' => 'Creative Portfolio',
                'description' => 'Showcase your creative work with this modern portfolio template',
                'thumbnail_url' => 'https://picsum.photos/seed/portfolio/400/300',
                'visibility' => 'public',
                'tags' => ['creative', 'portfolio', 'minimal'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
            [
                'name' => 'Annual Report Poster',
                'description' => 'Clean and professional poster template for annual reports',
                'thumbnail_url' => 'https://picsum.photos/seed/report/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'report', 'professional'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
            [
                'name' => 'Social Media Carousel',
                'description' => 'Engaging carousel template for Instagram and LinkedIn',
                'thumbnail_url' => 'https://picsum.photos/seed/carousel/400/300',
                'visibility' => 'public',
                'tags' => ['social', 'marketing', 'colorful'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
            [
                'name' => 'Arabic Business Proposal',
                'description' => ' RTL template for Arabic business proposals',
                'thumbnail_url' => 'https://picsum.photos/seed/arabic/400/300',
                'visibility' => 'public',
                'tags' => ['business', 'arabic', 'rtl'],
                'locale' => 'ar',
                'direction' => 'rtl',
            ],
        ];

        foreach ($templates as $templateData) {
            $type = $types->where('name', 'presentation')->first();
            if (str_contains(strtolower($templateData['name']), 'carousel')) {
                $type = $types->where('name', 'carousel')->first();
            } elseif (str_contains(strtolower($templateData['name']), 'poster')) {
                $type = $types->where('name', 'poster')->first();
            }

            $template = Template::factory()->create([
                'user_id' => $user->id,
                'type_id' => $type->id,
                'name' => $templateData['name'],
                'description' => $templateData['description'],
                'thumbnail_url' => $templateData['thumbnail_url'],
                'visibility' => $templateData['visibility'],
                'tags' => $templateData['tags'],
                'locale' => $templateData['locale'],
                'direction' => $templateData['direction'],
                'fork_count' => rand(0, 20),
                'upvote_count' => rand(0, 50),
            ]);

            $this->createSampleFiles($template, $user);
        }

        Template::factory(5)->public()->create()->each(function ($template) {
            File::factory(3)->create([
                'template_id' => $template->id,
                'user_id' => $template->user_id,
            ]);
        });
    }

    private function createSampleFiles(Template $template, User $user): void
    {
        $files = [
            ['layer' => 'meta', 'name' => 'meta.md', 'extension' => 'md', 'content' => "# {$template->name}\n\n{$template->description}"],
            ['layer' => 'context', 'name' => 'context.md', 'extension' => 'md', 'content' => "This template is designed for {$template->name}."],
            ['layer' => 'rules', 'name' => 'rules.md', 'extension' => 'md', 'content' => "Style guidelines for this template."],
            ['layer' => 'layout', 'name' => 'layout.html', 'extension' => 'html', 'content' => '<div class="layout">{{content}}</div>'],
            ['layer' => 'style', 'name' => 'style.css', 'extension' => 'css', 'content' => 'body { font-family: system-ui; }'],
            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => '<div class="slide"><h1>Welcome</h1></div>'],
        ];

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
}
