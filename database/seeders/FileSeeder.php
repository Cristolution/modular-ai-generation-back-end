<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FileSeeder extends BaseSeeder
{
    protected function seed(): void
    {
        $user = User::factory()->create([
            'name' => 'File Author',
            'email' => 'files@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $templates = Template::where('visibility', 'public')->limit(3)->get();

        foreach ($templates as $template) {
            $this->createSampleFiles($template, $user);
        }
    }

    private function createSampleFiles(Template $template, User $user): void
    {
        $files = [
            [
                'layer' => 'meta',
                'name' => 'meta.md',
                'extension' => 'md',
                'content' => "# {$template->name}\n\n{$template->description}\n\n## Author\nПроєкт створено з ❤️ для презентацій\n\n### Теги\n- Open Source\n- 日本語テスト\n- Emoji: 🚀🎉✨\n- Special chars: <\" & >\n",
            ],
            [
                'layer' => 'context',
                'name' => 'context.md',
                'extension' => 'md',
                'content' => "## Project Context\n\nThis is a multi-line context\nwith several paragraphs.\n\nLine 1\nLine 2\nLine 3 with \"double quotes\" and 'single quotes'\n\n\tIndented text\n\nEnd of context.",
            ],
            [
                'layer' => 'rules',
                'name' => 'rules.md',
                'extension' => 'md',
                'content' => "# Style Rules\n\n## Typography\n- Font: System UI, -apple-system, sans-serif\n- Headers: bold, uppercase\n- Body: normal weight\n\n## Colors\n- Primary: #3B82F6\n- Secondary: #8B5CF6\n- Warning: \"Don't use red for success!\"\n\n## Code\n```css\n.class {\n    color: var(--primary);\n}\n```\n",
            ],
            [
                'layer' => 'layout',
                'name' => 'layout.html',
                'extension' => 'html',
                'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{title}}</title>
</head>
<body>
    <div class="layout-container">
        <header class="header">
            <nav>{{nav}}</nav>
        </header>
        <main class="content">
            {{content}}
        </main>
        <footer class="footer">
            &copy; 2024 - All rights reserved
        </footer>
    </div>
</body>
</html>',
            ],
            [
                'layer' => 'style',
                'name' => 'style.css',
                'extension' => 'css',
                'content' => '/* Global Styles */
:root {
    --primary-color: #3498db;
    --secondary-color: #2ecc71;
    --font-main: "Helvetica Neue", Helvetica, Arial, sans-serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-main);
    line-height: 1.6;
    color: #333;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.slide {
    padding: 2rem;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

h1, h2, h3 {
    margin-bottom: 1rem;
    color: var(--primary-color);
}

p {
    margin-bottom: 0.5rem;
}

code {
    font-family: "Fira Code", "Consolas", monospace;
    background: #f4f4f4;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
}

/* Responsive */
@media (max-width: 768px) {
    .slide {
        padding: 1rem;
    }
}',
            ],
            [
                'layer' => 'slide',
                'name' => 'slide-01.html',
                'extension' => 'html',
                'content' => '<div class="slide" data-slide="1">
    <div class="slide-content">
        <h1>Welcome to Our Presentation</h1>
        <p class="subtitle">Exploring the future of web development</p>
        <div class="highlights">
            <span class="badge">New</span>
            <span class="badge">Exciting</span>
            <span class="badge">Modern</span>
        </div>
    </div>
</div>',
            ],
            [
                'layer' => 'slide',
                'name' => 'slide-02.html',
                'extension' => 'html',
                'content' => '<div class="slide" data-slide="2">
    <h2>Key Features</h2>
    <ul class="features">
        <li>Responsive Design</li>
        <li>Fast Performance</li>
        <li>SEO Friendly</li>
        <li>Accessible</li>
        <li>Modern Technologies</li>
    </ul>
    <blockquote cite="https://example.com">
        "The best way to predict the future is to create it."
        — Peter Drucker
    </blockquote>
</div>',
            ],
            [
                'layer' => 'content',
                'name' => 'content.json',
                'extension' => 'json',
                'content' => '{
    "title": "Sample Content",
    "description": "A sample JSON file with special chars: \\n\\t\\r and \"quotes\"",
    "items": [
        {
            "id": 1,
            "name": "Item One",
            "tags": ["featured", "new", "popular"]
        },
        {
            "id": 2,
            "name": "Item Two",
            "tags": ["recommended"]
        }
    ],
    "metadata": {
        "version": "1.0.0",
        "author": "John Doe \"The Developer\"",
        "special_chars": "<>&\'\"\'\'",
        "unicode": "日本語 中文 한국어 العربية"
    }
}',
            ],
            [
                'layer' => 'asset',
                'name' => 'config.json',
                'extension' => 'json',
                'content' => '{
    "settings": {
        "theme": "dark",
        "language": "en-US",
        "direction": "ltr",
        "fonts": {
            "primary": "Inter, system-ui, sans-serif",
            "monospace": "Fira Code, Consolas, monospace"
        },
        "colors": {
            "primary": "#3B82F6",
            "secondary": "#8B5CF6",
            "accent": "#F59E0B",
            "background": "#0F172A",
            "text": "#F8FAFC"
        }
    }
}',
            ],
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
