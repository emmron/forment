# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Formet is a developer-friendly form backend (like Getform/Formspree) that works with raw HTML - no JavaScript required. Point any HTML form's `action` attribute to a Formet endpoint and submissions are captured, stored, and optionally forwarded via email/webhooks/Slack/Discord.

The tricky part: making form handling as simple as adding an email address while supporting advanced features like file uploads, CAPTCHA, custom SMTP, and rate limiting.

## Common Commands

```bash
# Development server
php artisan serve

# Run all tests
php artisan test

# Run a single test file
php artisan test tests/Feature/FormTest.php

# Run a specific test method
php artisan test --filter=test_method_name

# Run migrations
php artisan migrate

# Code formatting (Laravel Pint)
./vendor/bin/pint

# View logs in real-time
php artisan pail
```

## Architecture

### Core Flow

Two ways to use Formet:

**Email as Endpoint (Zero Config)**
1. Use email directly as endpoint: `action="/f/you@email.com"`
2. Form auto-created on first submission, notifications sent to that email
3. No signup required - just works

**Dashboard Endpoints**
1. User creates a form in dashboard → gets unique endpoint like `/f/abc123xyz`
2. User adds `action="https://formet.io/f/abc123xyz"` to their HTML form
3. Configure webhooks, Slack/Discord, CAPTCHA, file uploads, etc.
4. Submission stored, notifications dispatched to queue

### Key Models

- **Form** (`app/Models/Form.php`): All form configuration - notifications, CAPTCHA, webhooks, SMTP, file uploads, rate limits
- **Submission** (`app/Models/Submission.php`): Form data as JSON, auto-detects email/name fields for autoresponders

### Notification Jobs (`app/Jobs/`)

All queued with retry logic:
- `SendEmailNotification` - Notifies form owner
- `SendAutoresponder` - Replies to submitter
- `SendWebhook` - Generic webhook (3 retries, exponential backoff)
- `SendSlackNotification` / `SendDiscordNotification`

### API

Routes in `routes/api.php` authenticated via per-form API keys using `api.key` middleware.

### Security

- CAPTCHA: reCAPTCHA v3 / hCaptcha via `CaptchaService`
- Rate limiting: Per-form, per-IP
- Domain restrictions: Validates Origin/Referer headers
- File uploads: MIME type validation, size limits
- Honeypot: `_honeypot` field auto-flags spam

### Database

SQLite default. Sensitive fields (`smtp_password`, `captcha_secret_key`) use Laravel's `encrypted` cast.
