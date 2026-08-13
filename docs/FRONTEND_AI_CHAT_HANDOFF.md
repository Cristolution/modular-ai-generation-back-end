# Frontend handoff — AI chat (per-user keys)

This is the spec the **frontend agent** needs. The backend is already done; this document tells the frontend agent exactly what to build / change.

---

## TL;DR

Every user stores their own MiniMax / Anthropic / OpenAI / LM Studio API key in the backend's `user_ai_providers` table. The backend holds the *encrypted* key — the browser never sees it.

The chat panel must:

1. **Stop storing any AI API key** in browser storage. Delete all uses of `mgf.ai.key.*` (sessionStorage and localStorage).
2. **Send the user's chosen `provider_id`** in the body of `POST /api/v1/ai/chat` so the backend knows whose key to use.
3. **Send Sanctum auth** on the chat request (`Authorization: Bearer <token>`). The token already lives in `localStorage` under `mgf.authToken` and is auto-attached by `src/lib/api/client.ts`. The chat panel currently uses raw `fetch()` — that strips the header, which is why every chat came back `401 Unauthenticated.`
4. **List the user's providers** with `GET /api/v1/me/ai-providers` and let them pick one in the chat panel (or default to the first `is_active: true` row).
5. **Surface errors** using the `code` field in the response body (`anthropic_key_missing`, `provider_not_found`, `model_required`, `upstream_unreachable`, `upstream_error`).

That's it. The backend already does the rest — decrypts the user's key, resolves their base URL, calls MiniMax, streams the SSE back, and never lets a user touch another user's key.

---

## Why this matters (do not skip the security review)

The previous architecture cached a SHARED AI key in the browser. With multiple users, every user would have called MiniMax using the same key — which means **one user's prompt is billed to another user's account**, and **any user could read another's API key** by opening devtools. Per-user keys fix both at the database level.

---

## API contract

### 1. List the current user's providers

```
GET /api/v1/me/ai-providers
Headers: Authorization: Bearer <token>
```

Response (200):

```json
{
  "data": [
    {
      "id": "019ffa96-17b9-72e4-96f4-7639a75c2f18",
      "provider": "anthropic",
      "display_name": "MiniMax (testing)",
      "base_url": "https://api.minimax.io/anthropic",
      "default_model": "MiniMax-M3",
      "has_key": true,
      "is_active": true,
      "created_at": "2026-08-13T10:06:08+00:00"
    }
  ]
}
```

Note: `api_key` / `api_key_encrypted` are **never** returned. The `has_key` boolean is the only signal.

### 2. Add or update a provider

```
POST /api/v1/me/ai-providers
Headers: Authorization: Bearer <token>
Content-Type: application/json

{
  "provider": "anthropic",                  // one of: openai|anthropic|gemini|local|custom
  "display_name": "MiniMax",               // optional, shown in the UI
  "api_key": "sk-cp-...",                  // optional for `local`; encrypted server-side
  "base_url": "https://api.minimax.io/anthropic",
  "default_model": "MiniMax-M3"
}
```

Validation: `provider` + `base_url` required. `api_key` required for all non-`local` providers. One provider per `provider` value per user (a user can have one MiniMax, one OpenAI, one LM Studio, etc.).

Update:

```
PUT /api/v1/me/ai-providers/{id}
```

Same body. Omit `api_key` to leave it unchanged. Setting a new `api_key` re-encrypts.

Delete:

```
DELETE /api/v1/me/ai-providers/{id}    → 204
```

Test connection (no upstream call is made — just probes the URL):

```
POST /api/v1/me/ai-providers/{id}/test
```

Returns 200 with `{ok, reachable, status, code, message, latency_ms}`.

### 3. Send a chat (the call that previously returned 401)

```
POST /api/v1/ai/chat
Headers: Authorization: Bearer <token>
Content-Type: application/json
Accept: text/event-stream

{
  "provider_id": "019ffa96-17b9-72e4-96f4-7639a75c2f18",
  "model": "MiniMax-M3",                   // optional — falls back to provider.default_model
  "system": "You are ...",                 // optional
  "messages": [
    { "role": "user", "content": "hi" },
    { "role": "assistant", "content": "..." }   // optional, for conversation history
  ],
  "max_tokens": 4096                       // optional
}
```

Response (200) — stream of Anthropic SSE events, frame-by-frame:

```
event: message_start
data: {"type":"message_start","message":{...}}

event: content_block_start
data: {"type":"content_block_start", ...}

event: content_block_delta
data: {"type":"content_block_delta","index":0,"delta":{"type":"text_delta","text":"Hello"}}

event: content_block_stop
data: {...}

event: message_stop
data: {"type":"message_stop"}
```

Use the existing `consumeAnthropicSse` parser in `src/lib/ai/providers/minimax.ts` — it already handles this format. Just fix the request side.

### 4. Error responses (JSON, not streamed)

| Status | `code`                      | When                                                                                                  |
| ------ | --------------------------- | ----------------------------------------------------------------------------------------------------- |
| 401    | —                           | No / bad Sanctum token. The bearer token must be the same one stored in `localStorage['mgf.authToken']`. |
| 404    | `provider_not_found`        | `provider_id` is not a row belonging to the current user. Same shape for foreign and missing IDs.    |
| 422    | (Laravel validation body)   | Missing `provider_id`, invalid UUID, missing `messages[]`, invalid `role`.                              |
| 422    | `model_required`            | Neither request `model` nor `provider.default_model` is set.                                           |
| 502    | `anthropic_key_missing`     | The provider row has no key, or the encrypted blob is unreadable. Prompt the user to add one.         |
| 502    | `upstream_unreachable`      | Network error reaching MiniMax.                                                                       |
| 502    | `upstream_error`            | MiniMax returned non-2xx (bad model, exhausted quota, etc.). Check `message` for the upstream status. |

A 502 mid-stream is also reported as a final SSE event (`event: error\ndata: {...}`) after headers have been sent — keep an eye out for it in the parser.

---

## Concrete changes for the frontend agent

### A. `src/features/editor/components/AI/ChatView.tsx`

- Remove the imports of `getKey`, `clearKey`, `setBaseUrlOverride`, `getBaseUrl` from `src/lib/ai/apiKeys.ts`.
- Remove the "Reset key" button — keys aren't in the browser anymore. Add a "Manage providers" link that opens the settings page.
- Replace the "Base URL" field with **"Provider"** (a select pulling from `GET /api/v1/me/ai-providers`). Display `display_name`. Store the chosen `id` in component state.
- Use the chosen provider's `default_model` as the default value for the model textbox.
- When the user has zero providers, show "Add an AI provider in Settings to start chatting." Don't disable the input — let them click through to settings.

### B. `src/lib/ai/providers/minimax.ts`

- In `streamChat`, add `credentials: 'include'` to the `fetch()` so Sanctum cookies flow (defensive — axios already handles Bearer, but direct fetch needs both).
- Better: use the existing axios client (`apiClient.post(...)`) which already attaches the bearer token. But axios doesn't stream nicely — for streaming you can keep `fetch()` and add the header explicitly:

  ```ts
  const token = window.localStorage.getItem('mgf.authToken');
  response = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'text/event-stream',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
    body: JSON.stringify({
      provider_id: params.providerId,   // <- new, required
      model: params.model,
      system: params.system,
      messages: params.messages,
      max_tokens: 4096,
    }),
    signal: params.signal,
  });
  ```

- Add the new `providerId` to the `StreamChatParams` type (see `src/lib/ai/AIService.ts`). Don't default to anything — the backend will 422 if omitted.
- Update `testConnection` to include the Authorization header too (currently the response is treated as "reachable" on any non-404 status, but with auth now in play it can also be tested with a real call).
- The `provider` field on the `AIProvider` type means "minimax vs lmstudio" — that's a *family*, not a row id. Rename it to `family` or keep it; the new `providerId` (uuid) lives alongside it.

### C. `src/lib/ai/AIService.ts`

- Add `providerId: string` to `StreamChatParams`.

### D. `src/lib/ai/apiKeys.ts`

- Either delete the file or zero it out. Nothing in the codebase needs to read an AI key from the browser anymore.
- Keep `getStorageMode`, `setStorageMode`, `getUseProxy`, `setUseProxy`, `getProvider`, `setProvider` *only if the chat panel still uses them for non-secret UI prefs*. Otherwise remove.

### E. New screen: provider picker

If it doesn't exist yet, the user needs a way to add their MiniMax key. Build (or extend) an "AI Providers" page:

- List current providers via `GET /api/v1/me/ai-providers`.
- "Add provider" form posts `provider`, `display_name`, `base_url`, `default_model`, and `api_key` to `POST /api/v1/me/ai-providers`.
- "Test" button posts to `POST /api/v1/me/ai-providers/{id}/test`. Display the latency.
- Inline edit reuses the PUT endpoint. The edit form NEVER shows the existing key — only a placeholder ("•••• key set ••••") and a "Replace key" button.

---

## What the frontend agent will NOT need to do

- Compute or apply any encryption. The backend uses Laravel's `Crypt` facade via `encrypt()` / `decrypt()`. Round-trips the key transparently.
- Send the API key anywhere. The backend decrypts and discards.
- Implement rate limiting or quota tracking (backend handles it).
- Configure CORS or any other transport detail.
- Add a `proxy` mode for MiniMax in production. The backend already proxies (or, when Laravel isn't reachable, the Vercel fallback does). The browser always talks to `/api/v1/ai/chat` on its own origin.

---

## Auth recap (so the 401 doesn't come back)

The user's Sanctum auth token is set in `localStorage['mgf.authToken']` after `POST /api/v1/auth/login`. The axios client attaches it via `Authorization: Bearer <token>`. Anything using `fetch()` directly **must add that header itself**.

If the chat panel still 401s after these changes:

1. Confirm the token is present: `localStorage.getItem('mgf.authToken')` should return a non-empty string.
2. Confirm the request includes `Authorization: Bearer <that-string>` — check the Network tab in devtools.
3. Confirm `POST /api/v1/auth/login` was called and returned a token. If not, the chat panel opened before the user logged in — gate the chat on `isAuthenticated`.

---

## Test checklist for the frontend agent

- [ ] After login, `GET /api/v1/me/ai-providers` returns the seeded / user-added providers.
- [ ] Add a provider with a real MiniMax key, then send "say hi" — the assistant responds with real MiniMax content (not "401", not "CORS error").
- [ ] Send without `provider_id` — backend returns 422; frontend shows a clear message and does not retry with stale state.
- [ ] Send with another user's `provider_id` (devtools override) — backend returns 404 `provider_not_found`; frontend logs out the user (security: that means someone is replaying credentials, alert).
- [ ] Refresh the page — the previously chosen provider is restored from component state (or a tiny preference, not the key).
- [ ] `localStorage` after refresh contains `mgf.authToken` and NO `mgf.ai.key.*`. Run `localStorage.getItem('mgf.ai.key.minimax')` in the console — it should return `null`.

---

## Files in the backend that implement this

- `app/Http/Controllers/Api/V1/AiChatController.php` — the chat endpoint, per-user lookup.
- `app/Services/AnthropicChat.php` — thin streaming client. Take its argument signature literally; do NOT reintroduce config-based fallback to `services.anthropic.key`.
- `app/Http/Controllers/AiProviderController.php` — full CRUD + test.
- `app/Models/UserAiProvider.php` — the row model.
- `tests/Feature/Api/V1/AiChatTest.php` — 15 tests including a guard against re-introducing the shared-key path.

For deep context: `~/.claude/projects/c--Users-Crist-Desktop-4-th-year-project-01-MGF-BACKEND/memory/per-user-ai-provider-architecture.md`.
