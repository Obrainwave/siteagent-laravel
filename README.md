# SiteAgent for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**SiteAgent** is a lightweight, cryptographically secure enforcement SDK for Laravel applications. It allows the **ZuqoLab Control Manager** to remotely activate or suspend your application based on subscription status.

## 🚀 Installation

Install the package via Composer:

```bash
composer require obrainwave/siteagent-laravel
```

## ⚙️ Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="ZuqoLab\SiteAgent\SiteAgentServiceProvider"
```

Configure your credentials in your `.env` file:

```env
SITE_AGENT_API_KEY=your_public_key
SITE_AGENT_SECRET=your_signing_secret
SITE_AGENT_ENABLED=true
```

## 🛠 Integration

### 1. Register the Middleware
Add the `EnforceSiteAgent` middleware to your global middleware stack or specific route groups in `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \ZuqoLab\SiteAgent\Http\Middleware\EnforceSiteAgent::class,
    ],
];
```

### 2. Automated Enforcement
Once integrated, the SDK automatically registers a hidden control endpoint at:
`POST /api/system/control`

When the Control Manager sends a `suspend` command, the middleware will intercept all incoming requests and serve a premium **503 Service Unavailable** page.

## 🔒 Security
All remote commands must be signed using **HMAC-SHA256**. The SDK automatically validates:
- **API Key**: Ensures the command is intended for this site.
- **Signature**: Prevents tampering with command payloads.
- **Timestamp**: Rejects requests older than 5 minutes to prevent replay attacks.

## 📄 License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
