<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds the community `resources` table with curated examples for every
 * kind documented in the OpenAPI contract:
 *   - prompt       — system/role/task prompts, with placeholders
 *   - skill        — discrete capabilities (e.g. CSV parser, format converter)
 *   - agent        — full agent specs with tools and instructions
 *   - rule         — guard-rails / style guides / behavioural constraints
 *   - mcp          — Model Context Protocol server definitions
 *   - design_doc   — long-form markdown specifications
 *   - hook         — code snippets that fire on lifecycle events
 *
 * The "Test User" gets a hand-curated flagship set so the demo data reads
 * well on the landing page; other users get one of every kind so the
 * listings, search, and admin views all see healthy volume across all
 * kinds and visibilities.
 */
class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();

        if ($testUser) {
            $this->seedFlagshipSet($testUser);
        }

        // Per-other-user: one of every kind so the listings and the admin
        // moderation view see all kinds across the user base.
        User::where('email', '!=', 'test@example.com')
            ->get()
            ->each(fn (User $user) => $this->seedPerKindSet($user));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Flagship set for the test user
    // ─────────────────────────────────────────────────────────────────────

    private function seedFlagshipSet(User $user): void
    {
        // ── Prompt ─────────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'prompt',
            'name' => 'Professional Email Writer',
            'description' => 'Drafts a polished email given a topic, recipient role, and tone.',
            'content' => <<<'PROMPT'
You are a senior communications specialist. Write a professional email on the following topic.

Topic: {{topic}}
Recipient role: {{recipient_role}}
Tone: {{tone}}

Constraints:
- Open with a single-line greeting that matches the tone.
- State the purpose in the first sentence — no throat-clearing.
- Keep the body under 200 words unless the topic genuinely needs more.
- Close with a clear next step or call to action.
- Sign off in a way that matches the recipient role and tone.

Output only the email — no commentary, no "Here is the email:" prefix.
PROMPT,
            'placeholders' => [
                ['key' => 'topic', 'label' => 'Topic', 'default' => 'Quarterly product roadmap review', 'type' => 'textarea'],
                ['key' => 'recipient_role', 'label' => 'Recipient role', 'default' => 'Director of Engineering', 'type' => 'text'],
                ['key' => 'tone', 'label' => 'Tone', 'default' => 'concise and friendly', 'type' => 'select'],
            ],
            'visibility' => 'public',
            'tags' => ['business', 'communication'],
        ]);

        Resource::create([
            'user_id' => $user->id,
            'kind' => 'prompt',
            'name' => 'Code Review Assistant',
            'description' => 'Reviews a code snippet and surfaces issues by severity.',
            'content' => <<<'PROMPT'
You are a senior {{language}} engineer performing a code review.

Review the following code:

{{code}}

Output a markdown report with sections:
1. **Critical** — bugs, security issues, data loss
2. **Warnings** — performance, correctness edge cases
3. **Suggestions** — readability, naming, idioms
4. **Praise** — things done well (be specific)

Do not rewrite the code. Each finding gets a line number, a one-line summary, and a fix suggestion.
PROMPT,
            'placeholders' => [
                ['key' => 'language', 'label' => 'Language', 'default' => 'PHP', 'type' => 'text'],
                ['key' => 'code', 'label' => 'Code snippet', 'default' => "function add(int \$a, int \$b): int {\n    return \$a - \$b;\n}", 'type' => 'textarea'],
            ],
            'visibility' => 'public',
            'tags' => ['technical', 'engineering'],
        ]);

        // ── Skill ──────────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'skill',
            'name' => 'Markdown → HTML converter',
            'description' => 'Pure-PHP markdown-to-HTML transformer for the MGF slide pipeline.',
            'content' => <<<'SKILL'
Convert a markdown string into the HTML fragment expected by the MGF slide template:
  - Headings (h1..h4) → matching tags with class="mgf-h{n}"
  - Paragraphs wrapped in <p>
  - Inline code → <code>, fenced code blocks → <pre><code class="lang-...">
  - Bullet lists → <ul>, ordered lists → <ol>
  - Links → <a target="_blank" rel="noopener">

Inputs:
  - md: string — the markdown source

Output:
  - html: string — sanitised HTML ready for slide insertion

Hard rules:
  - Strip raw <script> and on* attributes.
  - Preserve paragraph whitespace.
  - No external CSS or JS — inline styling only when unavoidable.
SKILL,
            'visibility' => 'public',
            'tags' => ['technical', 'slides'],
        ]);

        // ── Agent ─────────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'agent',
            'name' => 'Full-Stack Web Developer Agent',
            'description' => 'Plans and ships React + Laravel features end-to-end.',
            'content' => <<<'AGENT'
# Full-Stack Web Developer Agent

## Role
You are a senior full-stack engineer shipping production features in
React 19 + TypeScript on the frontend and Laravel 11 + MySQL on the
backend.

## Tools available
- read_file, write_file, search_code (workspace-wide)
- shell (run npm / composer / artisan / phpunit / vitest)
- http_request (only against the local dev server)

## Behaviour
1. Read the relevant files end-to-end. Do not skim.
2. Sketch a 3–7 step plan; commit to it before writing code.
3. Implement one step at a time. After each, run the smallest meaningful
   test (phpunit / vitest) — never batch three changes and "test at the end".
5. When the feature crosses the network boundary, curl the endpoint with
   the actual request shape, not a "shape I assume the spec says".
6. On failure, return the smallest reproducer first, then the fix.

## Hard rules
- No silent fallbacks. If a contract says A and the server returns B,
  raise a typed error — do not paper over it.
- Never log API keys, tokens, or user-provided secrets.
- Keep PRs under 400 lines of diff when possible.
AGENT,
            'visibility' => 'public',
            'tags' => ['engineering', 'agent', 'fullstack'],
        ]);

        // ── Rule ──────────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'rule',
            'name' => 'MGF Code Style Rules',
            'description' => 'House style for Laravel + React contributions.',
            'content' => <<<'RULE'
# Code Style

1. **Naming**
   - Models: singular PascalCase (`Template`, `UserAiProvider`).
   - Controllers: noun + purpose (`TemplateController`, `AdminUserController`).
   - UUIDs for all primary keys — never auto-increment.

2. **Errors**
   - Validation → 422 with `{message, errors:{field:[]}}`.
   - Foreign-resource access → 404 (not 403) to avoid enumeration leaks.
   - Upstream failure → 502 with `{error, code}` — never 500.

3. **Security**
   - Encrypt user-provided secrets at rest with `encrypt()`.
   - Never echo a secret back in any response, even truncated.
   - Sanctum for auth; never roll our own.

4. **Tests**
   - Every new endpoint gets at least one happy-path + one failure-path
     feature test using `RefreshDatabase`.
   - Streamed responses must call `streamedContent()` to drain.

5. **Comments**
   - "Why" comments, not "what" comments.
   - No commentary about the author's feelings; this is production code.
RULE,
            'visibility' => 'public',
            'tags' => ['engineering', 'style'],
        ]);

        // ── MCP ────────────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'mcp',
            'name' => 'Filesystem MCP Server',
            'description' => 'Local stdio MCP that exposes the workspace filesystem read-only.',
            'content' => <<<'MCP'
{
  "name": "filesystem",
  "transport": "stdio",
  "command": "npx",
  "args": ["-y", "@modelcontextprotocol/server-filesystem", "/Users/me/projects"],
  "env": {},
  "capabilities": [
    "read_file",
    "list_directory",
    "search_files",
    "get_file_info"
  ],
  "restrictions": {
    "write_paths": [],
    "denied_globs": [".git/**", "node_modules/**", "vendor/**"]
  }
}
MCP,
            'visibility' => 'public',
            'tags' => ['mcp', 'tools'],
        ]);

        // ── Design doc ─────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'design_doc',
            'name' => 'MGF Architecture Overview',
            'description' => 'High-level view of the MGF backend + frontend split.',
            'content' => <<<'DOC'
# MGF Architecture Overview

## Goals
- Per-user AI keys: no shared env-var fallback.
- Modular files (slide / style / layout / content / context / rules /
  meta / asset) per project.
- Community templates and resources, forkable.

## Top-level layout
- `01_MGF_BACKEND` — Laravel 11 + Sanctum + MySQL.
- `modular-ai-generation-front-end` — React 19 + Vite + TypeScript.

## Data flow
1. Browser hits `/api/v1/...` (proxied by Vite to `php artisan serve` in
   dev).
2. Sanctum bearer token authenticates the request.
3. Controller resolves user-owned resources (`templates`, `projects`,
   `user_ai_providers`) — foreign IDs return 404 to avoid enumeration.
4. For AI generation: controller decrypts the user's API key, forwards
   to the upstream (Anthropic / MiniMax / OpenAI / etc.), and streams
   the SSE response back to the browser. The browser never sees the key.

## Why two processes?
- Frontend can ship to Vercel without exposing Laravel internals.
- Laravel can be hosted on Railway / Render / VPS with the upstream AI
  keys kept server-side, encrypted in MySQL.

## What is NOT in this doc
- Concrete database schema — see `00_docs/ERD.db`.
- Per-endpoint contracts — see `00_docs/openapi_api_contract_*.yaml`.
DOC,
            'visibility' => 'public',
            'tags' => ['design', 'architecture'],
        ]);

        // ── Hook ──────────────────────────────────────────────────────────
        Resource::create([
            'user_id' => $user->id,
            'kind' => 'hook',
            'name' => 'Pre-commit Lint Hook',
            'description' => 'Git hook that fails the commit if PHP / JS lint fails.',
            'content' => <<<'HOOK'
#!/usr/bin/env bash
# .git/hooks/pre-commit
set -e

echo "[pre-commit] running php -l on staged .php files..."
for f in $(git diff --cached --name-only --diff-filter=ACMR | grep -E '\.php$'); do
    php -l "$f" | grep -v "No syntax errors" && exit 1
done

echo "[pre-commit] running eslint on staged .ts / .tsx files..."
for f in $(git diff --cached --name-only --diff-filter=ACMR | grep -E '\.(ts|tsx)$'); do
    npx eslint --max-warnings=0 "$f" || exit 1
done

echo "[pre-commit] OK"
HOOK,
            'visibility' => 'public',
            'tags' => ['engineering', 'git'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Per-other-user set: one of every kind, varied visibility
    // ─────────────────────────────────────────────────────────────────────

    private function seedPerKindSet(User $user): void
    {
        $kinds = [
            'prompt' => [
                'name' => "{$user->name}'s Quick Task Prompt",
                'content' => "Solve the following task step-by-step: {{task.",
                'placeholders' => [['key' => 'task', 'label' => 'Task', 'default' => '', 'type' => 'textarea']],
                'tags' => ['utility'],
            ],
            'skill' => [
                'name' => "{$user->name}'s URL Slugifier",
                'content' => "Convert any string into a kebab-case URL slug. Strip diacritics, lowercase, replace non-alphanumeric with '-', collapse repeats, trim.",
                'placeholders' => null,
                'tags' => ['utility'],
            ],
            'agent' => [
                'name' => "{$user->name}'s Personal Coding Agent",
                'content' => "Role: Pair-programming agent.\nTools: read_file, write_file, shell.\nRules: never push, never delete without confirmation, write tests first.",
                'placeholders' => null,
                'tags' => ['agent'],
            ],
            'rule' => [
                'name' => "{$user->name}'s Comment Hygiene Rules",
                'content' => "1. No TODO comments — open a ticket.\n2. No 'this works but...' — fix it or revert.\n3. Every public function gets a one-line docblock.",
                'placeholders' => null,
                'tags' => ['style'],
            ],
            'mcp' => [
                'name' => "{$user->name}'s SQLite MCP",
                'content' => "{\n  \"name\": \"sqlite-{$user->id}\",\n  \"transport\": \"stdio\",\n  \"command\": \"uvx\",\n  \"args\": [\"mcp-server-sqlite\", \"--db-path\", \"./local.db\"]\n}",
                'placeholders' => null,
                'tags' => ['mcp'],
            ],
            'design_doc' => [
                'name' => "{$user->name}'s Side Project Plan",
                'content' => "# Side Project\n\n## Goal\n\nA small, useful thing.\n\n## Milestones\n\n- [ ] Spec\n- [ ] v0\n- [ ] Feedback loop\n",
                'placeholders' => null,
                'tags' => ['design'],
            ],
            'hook' => [
                'name' => "{$user->name}'s Post-format on Save Hook",
                'content' => "// editor hook: prettier-format on save\neditor.onDidSave(() => prettier.format(editor.document.uri.fsPath));",
                'placeholders' => null,
                'tags' => ['editor'],
            ],
        ];

        // Cycle visibility so each user gets a mix of public, private, and
        // unlisted — keeps the admin moderation view interesting.
        $visibilities = ['public', 'public', 'private', 'unlisted', 'public', 'private', 'public'];

        $i = 0;
        foreach ($kinds as $kind => $spec) {
            Resource::create([
                'user_id' => $user->id,
                'kind' => $kind,
                'name' => $spec['name'],
                'description' => "Sample {$kind} contributed by {$user->name}.",
                'content' => $spec['content'],
                'placeholders' => $spec['placeholders'],
                'visibility' => $visibilities[$i++ % count($visibilities)],
                'tags' => $spec['tags'],
            ]);
        }
    }
}