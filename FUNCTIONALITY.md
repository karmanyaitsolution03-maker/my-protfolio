# Portfolio — Functionality Overview

A Laravel-based personal portfolio site with a fully dynamic, database-driven front end, a custom admin panel, and OpenAI-powered features (AI chat assistant, text-to-speech, contact-message triage).

---

## 1. Public Site (`/`)

**Route:** `GET /` → `PortfolioController@home`

Renders `resources/views/portfolio.blade.php`, a single-page "AI command center" themed site built entirely from database content (no hardcoded copy). Sections, in order:

| Module | Content source |
|---|---|
| **AI boot intro** | Greeting, diagnostics list, welcome line — all editable text (`intro_*` settings) |
| **Hero / Command Deck** | Tagline, coordinate strip, CTA buttons, floating chips (`hero_*` settings) |
| **Module 01 — Profile Report** | Name, designation, frameworks, specialization, animated years-of-experience counter, location, status — built in `PortfolioController::home()` as `$profileRows` |
| **Module 02 — Skills** | `SkillCategory` → `Skill` (name + % level bars), ordered by `position` |
| **Module 03 — Experience ("Mission Logs")** | `Experience` records: company, role, period, "LIVE" flag for current job, JSON list of responsibilities |
| **Module 04 — Projects** | `Project` records: title, kicker, description, accent color, animated architecture diagram (JSON node graph), metrics, tags, "wide/featured" layout flag |
| **Module 05 — Achievements** | `Achievement` records: animated numeric counters or text values + labels |
| **Module 06 — Contact** | Contact form (AJAX POST), resume download link, WhatsApp button, LinkedIn link |
| **Module 07 — Career & Availability** | `Availability` rows (CTC, notice period, etc.) + `CareerPoint` highlight bullets; section can be hidden via `career_show` setting |
| **Nav / HUD / Footer** | Waypoint labels, sector names, footer text — all editable |
| **Floating AI Assistant widget** | Chat bubble with quick-reply suggestions, idle tips, section-aware voiced narration |

**Content tokens:** setting values can embed `:name`, `:first`, `:last`, `:exp`, `:proj`, `:ach` — replaced live with the person's name and live record counts (`PortfolioController::applyTokens()`).

**SEO:** `GET /sitemap.xml` — generates a sitemap XML from the current request host.

---

## 2. Resume Download

**Route:** `GET /resume` → `PortfolioController@resume`

- If a PDF resume has been uploaded via the admin Settings page, streams that file as a download.
- Otherwise, generates a plain-text "subject file" on the fly from live DB data (designation, experience log, project list, contact info) and serves it as a `.txt` download.

---

## 3. Contact Form

**Route:** `POST /contact` (throttled 5/min) → `ContactController@store`

- **Honeypot spam protection** — hidden `hp_check` field; if filled, silently pretends success without saving/sending anything.
- Validates name/email/message, saves to `messages` table.
- **AI triage** (`MessageTriageService`): sends the message to OpenAI (`gpt-4o-mini`) to classify it as `recruiter` / `client` / `spam` / `other` and generate a one-sentence summary, stored on the message (`ai_category`, `ai_summary`). Fails silently (returns `null`) if no API key or on any error.
- Sends two emails (each independently try/caught so one failure doesn't block the other):
  - **`NewContactMessage`** — notifies the site owner at their configured contact email.
  - **`ContactAutoReply`** — auto-reply sent back to the visitor.

---

## 4. Interaction Tracking

**Route:** `POST /interactions` (throttled 20/min) → `InteractionController@track`

Logs lightweight click events (`contact_click`, `resume_click`, `whatsapp_click`) with IP address, for admin dashboard analytics. Type is restricted to an allow-list.

---

## 5. Visitor Tracking (Middleware)

**`App\Http\Middleware\TrackVisitor`** — runs on every GET request except `/admin*` and `/up`.

- Dedupes by IP: increments `visit_count` on an existing `Visit` row, or creates a new one with user agent, path, and referrer.
- Fails silently — never breaks the page if tracking errors out.
- `Visit` model enriches rows on read with:
  - **Geolocation** (`getLocationAttribute`) — reverse-IP lookup via `ip-api.com`, cached 7 days, skips private/local IPs.
  - **Device label** (`getDeviceLabelAttribute`) — parses the user agent into a readable "Chrome on Windows" style string.

---

## 6. AI Assistant — Chat

**Route:** `POST /assistant/chat` (throttled 10/min) → `AssistantController@chat`

- Accepts a `question` plus optional short conversation `history`.
- Builds a system prompt (`buildContext()`) grounded entirely in live portfolio data: identity, about text, career highlights, skills, experience, projects, achievements, availability, and contact info — pulled fresh from the DB on every request.
- Calls OpenAI `gpt-4o-mini` with JSON-mode, asking the model to answer strictly from the supplied data and never invent details.
- Response is split into **segments**, each tagged with a page `section` id (`deck`, `profile`, `skills`, `logs`, `projects`, `achievements`, `contact`, `career`) so the front end can auto-scroll the page in sync with each spoken sentence.
- Instructs the model to speak monetary/numeric values in spoken-word form (for the TTS layer) rather than digits/symbols.
- Graceful fallbacks: if no `OPENAI_API_KEY` is set, or the API call fails, returns a friendly canned message pointing the visitor to the contact form.

## 7. AI Assistant — Text-to-Speech

**Route:** `POST /assistant/speak` (throttled 40/min) → `AssistantController@speak`

- Converts a line of assistant text to speech using OpenAI TTS (`gpt-4o-mini-tts`, fixed `nova` voice, fixed pacing instructions) so every visitor/device hears the identical voice — no browser/OS TTS fallback.
- **Content-addressed caching**: filename is a SHA1 hash of `voice|instructions|text`; identical lines are generated once and reused from `storage/app/public/tts/*.mp3` forever after.
- Validates the cached file isn't a 0-byte artifact from a previous failed write before trusting it; re-verifies the write after generating new audio.
- Returns the audio as an inline `data:audio/mpeg;base64,...` URI (not a separate storage URL), so the browser never makes a second request and nothing depends on storage-path routing/CDN rules.

---

## 8. Admin Panel (`/admin`)

Simple single-admin auth: email + password checked against the `users` table (`AdminController::login`), gated via `session('is_admin')` (`AdminController::gate`, `abort_unless(..., 403)`).

| Route | Purpose |
|---|---|
| `GET/POST /admin/login`, `POST /admin/logout` | Session-based login/logout |
| `GET /admin` | Dashboard — record counts per resource, total messages, total visits, and click counters (contact/resume/WhatsApp) |
| `GET/POST /admin/settings` | Edit every site text field (grouped per `config/portfolio.php`), plus file uploads (resume PDF, OG share image, profile photo) |
| `POST /admin/migrate` | Runs `php artisan migrate --force` from the browser and shows the output |
| `GET /admin/messages` | Paginated, searchable (name/email/message) contact-message inbox; opening the list auto-marks unread messages read |
| `GET /admin/messages/export` | Streams matching messages as a CSV download |
| `DELETE /admin/messages/{id}` | Delete a message |
| `GET /admin/visitors` | Visitor analytics: total visits, unique IPs, visits today, paginated visit log (with geolocation + device label) |

### Generic CRUD (`/admin/{resource}`)

A single config-driven CRUD controller (`AdminController::$resources`) drives `index` / `create` / `store` / `edit` / `update` / `destroy` for six resource types, each with typed field definitions (`text`, `number`, `textarea`, `checkbox`, `json`, `category`):

- **Skill Categories** — name, icon (emoji), wide-card flag, order
- **Skills** — category, name, level %, order
- **Experiences** — company, sub-line, role, period, "live" flag, responsibilities (JSON array), order
- **Projects** — title, kicker, description, accent color, wide flag, architecture diagram (JSON node graph), metrics (JSON), tags (JSON), order
- **Availability** — label, value, accent color, order
- **Career Points** — HTML-allowed highlight text, order

JSON fields are validated and decoded server-side (`decodeJson`), aborting with a 422 and a clear error if malformed.

---

## 9. Data Model

| Model | Notes |
|---|---|
| `Setting` | Key/value store; `resolved()` merges DB overrides onto defaults defined in `config/portfolio.php` so every key is always present; `syncDefaults()` seeds missing keys without touching existing edits |
| `SkillCategory` → `Skill` | One-to-many, ordered |
| `Experience` | `live` boolean, `responsibilities` JSON array cast |
| `Project` | `wide`, `arch`/`metrics`/`tags` JSON casts |
| `Achievement` | Numeric `count` (animated) or free-text `value` |
| `Availability` | Label/value rows with optional accent color |
| `CareerPoint` | HTML bullet points |
| `Message` | Contact submissions + AI triage fields (`ai_category`, `ai_summary`), `read_at` |
| `Interaction` | Click-event log (`contact_click` / `resume_click` / `whatsapp_click`) |
| `Visit` | Per-IP visit counter with computed `location` and `device_label` accessors |
| `User` | Admin login only |

---

## 10. Configuration & Environment

- `config/portfolio.php` — single source of truth for every editable text field on the site, grouped for the admin Settings UI, with default values and content tokens.
- `.env` keys of note:
  - `ADMIN_PASSWORD` — legacy; actual admin auth now checks the `users` table (see `AdminController::login`)
  - `OPENAI_API_KEY` — powers contact-message AI triage, the AI chat assistant, and TTS voice generation
  - Standard Laravel DB (SQLite by default), mail, cache, queue, session config

---

## 11. Front-End Build

- Vite + Tailwind CSS v4 (`vite.config.js`, `package.json`)
- `npm run dev` for local asset watching, `npm run build` for production assets
