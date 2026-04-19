# SiteAgent for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**SiteAgent** is a lightweight, cryptographically secure enforcement SDK for Laravel applications. It is the **Core Website Manager** to remotely manage the website status.

## 🚀 Installation

Install the package via Composer:

```bash
composer require obrainwave/siteagent-laravel
```

## ⚙️ Configuration

Initialize the SDK with the built-in installation command:

```bash
php artisan siteagent:install
```

This command will:
1. Publish the `siteagent.php` config file.
2. Publish the customizable 503 suspension views.
3. Initialize the local `siteagent.state.json` file.

Alternatively, you can manually publish specific assets:

```bash
php artisan vendor:publish --tag="siteagent-config"
php artisan vendor:publish --tag="siteagent-views"
```

Then, configure your credentials in your `.env` file:

```env
SITE_AGENT_API_KEY=your_public_key
SITE_AGENT_SECRET=your_signing_secret
SITE_AGENT_ENABLED=true
```

## 🛠 Integration

### 1. Automated Setup
Add the SiteAgent middleware by running the following command:

```bash
php artisan siteagent:install
```

The command will automatically detect your Laravel version and register the `EnforceSiteAgent` middleware in the global stack (`bootstrap/app.php` for Laravel 11+, or `app/Http/Kernel.php` for earlier versions).

### 2. Manual Fallback
If you prefer manual registration, add the `EnforceSiteAgent` middleware to your global middleware stack:

#### Laravel 11+:
```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\ZuqoLab\SiteAgent\Http\Middleware\EnforceSiteAgent::class);
})
```

#### Laravel 10 and below:
```php
// app/Http/Kernel.php
protected $middleware = [
    // ...
    \ZuqoLab\SiteAgent\Http\Middleware\EnforceSiteAgent::class,
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
