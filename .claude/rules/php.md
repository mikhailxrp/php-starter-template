---
description: PHP rules for Mini-MVC projects (PHP 8+, no framework)
globs: ["**/*.php"]
---

## Core principles

- PHP 8+ only — use match, named arguments, nullsafe operator
- Always add `declare(strict_types=1)` at the top of every file
- Functional style by default — no classes unless justified
- No spaghetti — logic and HTML must never mix in the same file

## Structure rules

- public/index.php — front controller only, no business logic
- src/Controllers/ — handle request, call model, pass data to view
- src/Models/ — only DB queries, return plain arrays
- src/Views/ — only HTML + echo, no loops over DB, no business logic
- src/Core/ — router, request helpers, shared utilities
- src/Services/ — email, file upload, external APIs
- config/routes.php — all routes in one place
- config/config.php — all constants and env loading

## Functions

- One function = one job
- Name functions as verbs: getUser(), saveTransaction()
- Always validate input at the controller level
- Return early — avoid deep nesting

## Database

- PDO only — no mysqli
- Always use prepared statements
- All DB functions live in src/Models/
- Return plain arrays from model functions
- One function = one query

## Views

- Views receive data as variables — never query DB inside a view
- Escape all output: htmlspecialchars($var, ENT_QUOTES, 'UTF-8')
- Use e() helper function as shortcut

## Security

- Never trust user input
- Use password_hash() / password_verify(); minimum 8 characters,
  validated before hashing — don't add further complexity rules
  unless the product explicitly needs them
- Call `regenerateSession()` right after a successful login (rotates
  session id and CSRF token — `src/Core/functions.php`), not the raw
  `session_regenerate_id()` call
- Login/register/forgot-password error messages never reveal whether
  an email exists in the system — same generic message for "wrong
  password" and "no such account" (prevents account enumeration)
- Log failed login attempts and rate-limit trips with `logWarning()`
  (context is auto-redacted for password/token/secret — see
  `src/Core/Logger.php`) for later incident review
- Session cookies are hardened centrally in `ensureSessionStarted()`
  (`httponly`, `samesite=Lax`, `secure` when `APP_ENV=production`) —
  don't override cookie params per-controller. `secure` only works if
  production is actually served over HTTPS; deploying prod on plain
  HTTP silently breaks sessions
- Security response headers (`X-Content-Type-Options`,
  `X-Frame-Options`, `Content-Security-Policy: frame-ancestors`,
  `Referrer-Policy`) are sent once for every request via
  `sendSecurityHeaders()` in `public/index.php` — don't duplicate them
  per-controller, extend that one function if a new header is needed
- CSRF token on every state-changing form — render it with
  `csrfField()`, and call `requireCsrf()` (or
  `verifyCsrfToken(input('_csrf'))` directly) at the top of every POST
  handler before touching the Model (`src/Core/functions.php`)
- Rate-limit login and public form submissions to slow brute-force and
  spam — guard with `tooManyAttempts('login', 5, 60)` before
  processing, `hitRateLimit('login')` on a failed attempt, and
  `clearRateLimit('login')` on success (`src/Core/functions.php`)
- Admin-only pages and actions check the role (`role = 'admin'`), not
  just that the user is logged in

## Error handling

- Never use @ to suppress errors
- Log errors to file — never output raw errors in production
- User sees: friendly message. Log sees: full trace

## Тестирование

- `vendor/` и `composer.lock` не лежат в шаблоне — при первом запуске
  тестов в новом проекте, если `vendor/` отсутствует, сначала выполни
  `composer install`, и только потом `composer test`
- PHPUnit — dev-зависимость через Composer, не деплоится на прод
  (`composer install --no-dev` для боевого сервера)
- Юнит-тестами покрывается только логика без обращения к БД и без
  HTTP-контекста (работа с `$_SESSION` в CLI допустима): хелперы
  `src/Core/functions.php` (CSRF, rate-limit, `normalizeUserId`, `e()`,
  `regenerateSession`), сопоставление маршрутов `src/Core/Router.php`,
  расчёты над массивами
- Тесты на Model-функции, которые ходят в БД — отдельная категория
