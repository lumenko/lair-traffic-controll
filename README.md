Lair Traffic Control
====================

<p align="center">
  <a href="https://packagist.org/packages/lumenko/lair-traffic-control"><img src="https://img.shields.io/packagist/v/lumenko/lair-traffic-control.svg?style=flat-square" alt="Packagist"></a>
  <a href="https://packagist.org/packages/lumenko/lair-traffic-control"><img src="https://img.shields.io/packagist/php-v/lumenko/lair-traffic-control.svg?style=flat-square" alt="PHP"></a>
  <a href="https://github.com/lumenko/lair-traffic-control/actions"><img src="https://img.shields.io/github/actions/workflow/status/lumenko/lair-traffic-control/ci.yml?branch=main&style=flat-square" alt="CI"></a>
  <a href="https://packagist.org/packages/lumenko/lair-traffic-control"><img src="https://img.shields.io/packagist/dt/lumenko/lair-traffic-control.svg?style=flat-square" alt="Downloads"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="License"></a>
</p>

Realtime HTTP & DB traffic monitor for Laravel packages. Designed to provide a lightweight, CLI-first view into request counts, errors, 404s and the last route. Intended for developers who want an easy, low-overhead way to observe traffic during local development, staging, or for small production use when paired with Redis.

Table of contents
- Features
- Requirements
- Installation
- Configuration
- Publishing resources & migrations
- Usage (CLI monitor)
- Cache keys & integration notes
- Development & testing
- Contributing
- Security
- Changelog
- License
- Maintainers & contact
- Roadmap

Features
- CLI live monitor: php artisan lair-traffic-control:monitor (with --clear).
- Simple cache-backed storage (configurable store).
- Publishes config, migrations, views, translations.
- Testbench-ready scripts for local development.

Requirements
- PHP ^8.3
- illuminate/support ^12.0 || ^13.0
- A cache store suitable for traffic metrics (Redis recommended for production workloads; database driver is supported but not recommended under heavy traffic).

Installation
1. Require the package via Composer:
   ```bash
   composer require lumenko/lair-traffic-control
   ```

2. (Optional) If your app does not have package auto-discovery disabled, the service provider and facade will be discovered automatically. Otherwise register:
   - Service provider: LairTrafficControl\LairTrafficControl\LairTrafficControlServiceProvider
   - Facade alias: LairTrafficControl => LairTrafficControl\LairTrafficControl\Facades\LairTrafficControl

Configuration
Publish the package configuration and adjust environment variables:
```bash
php artisan vendor:publish --tag="lair-traffic-control"
```

Environment variables (from config/lair-traffic-control.php)
- LAIR_TRAFFIC_STORE (default: database) — cache store used for metrics. Use redis for high throughput.
- LAIR_TRAFFIC_ENABLED (default: true) — master switch to enable/disable traffic monitoring.
- LAIR_TRAFFIC_TRACK_QUERIES (default: false) — whether to track DB queries (if integrated).
- LAIR_TRAFFIC_SLOW_QUERY_MS (default: 50) — threshold (ms) to count a slow query.
- cache_prefix (default: lair_traffic_) — prefix applied to stored keys.

Publishing resources & migrations
Publish everything:
```bash
php artisan vendor:publish --tag="lair-traffic-control"
```

Publish individual resources:
- Config:
  ```bash
  php artisan vendor:publish --tag="lair-traffic-control-config"
  ```
- Migrations:
  ```bash
  php artisan vendor:publish --tag="lair-traffic-control-migrations"
  php artisan migrate
  ```
- Views:
  ```bash
  php artisan vendor:publish --tag="lair-traffic-control-views"
  ```
- Translations:
  ```bash
  php artisan vendor:publish --tag="lair-traffic-control-lang"
  ```
- Public assets:
  ```bash
  php artisan vendor:publish --tag="lair-traffic-control-assets"
  ```

Usage
CLI monitor
```bash
# Run the live monitor (updates every second)
php artisan lair-traffic-control:monitor

# Clear stored history
php artisan lair-traffic-control:monitor --clear
```

The monitor displays a simple table of metrics, for example:
Metrics | Value
---|---
Total Requests | 123
Errors (5xx) | 2
Not Found (404) | 0
Last route | GET /api/users

Notes about how metrics are populated
- The monitor reads metrics from a cache store configured as "traffic_store" by the service provider. By default the package registers a cache store named traffic_store backed by the database driver and table lair_traffic_control_monitor (see service provider).
- The package itself provides storage, publishing, and a monitor CLI. To actually populate metrics you must integrate metrics collection into your app (middleware, listeners, exception handlers, DB query listeners, etc.). The package includes configuration keys and conventions:
  - Cache keys referenced by the monitor:
    - traffic_total_requests
    - traffic_total_errors
    - traffic_total_404s
    - route (last route string)
  - Cache prefix is configurable via config('lair-traffic-control.cache_prefix') (default lair_traffic_). Use this when writing/reading keys so they match the monitor.

If you want, I can add example middleware or listeners that increment these cache keys (e.g., increment traffic_total_requests on each request, increment traffic_total_errors on exception, set route to the route name/URI, etc.). Tell me whether you want a middleware, an event subscriber, or an HTTP kernel modification and I’ll produce it.

Development
This package uses Orchestra Testbench and provides convenient composer scripts in composer.json.

Useful scripts:
- Build the workbench: composer run-script build
- Serve the testbench app: composer run-script serve
- Lint: composer run-script lint
- Analyse (phpstan): composer run-script analyse
- Run tests:
  ```bash
  # run unit tests (parallel)
  composer test:unit

  # run static type coverage test (Pest)
  composer test:types

  # full test suite (analyse, lint check, type tests, unit tests)
  composer test
  ```

Local development (workbench)
1. Prepare the package:
   ```bash
   composer run-script prepare
   ```
2. Build and serve (spins up testbench):
   ```bash
   composer run-script serve
   ```

Testing
- The package uses Pest + Orchestra Testbench for tests. See tests/ for examples.
- Ensure dependencies are installed:
  ```bash
  composer install
  composer test
  ```

Contributing
Thank you for considering contributing! Please read .github/CONTRIBUTING.md for:
- How to open issues
- Branching & commit message conventions
- How to run tests locally
- Setting up the workbench

We also welcome pull requests—open PRs against main and follow the contribution guidelines.

Security
If you discover a security vulnerability, please follow our security policy in .github/SECURITY.md.

Changelog
See CHANGELOG.md for detailed release notes and migration guidance.

License
Lair Traffic Control is released under the MIT License. See LICENSE.md for details.

Authors & Maintainers
- lumenko — primary author/maintainer
- See contributors list on GitHub for others.

Roadmap (short)
- Add example middleware for automatic metric collection (+ PR).
- Add optional web UI (small SPA) to visualize metrics in browser.
- Improve DB-backed storage schema and retention policies.
- Add Prometheus exporter integration.

Troubleshooting
- High traffic on database cache store: switch LAIR_TRAFFIC_STORE to redis in .env:
  ```env
  LAIR_TRAFFIC_STORE=redis
  ```
- Monitor shows zeros: ensure your application increments the expected cache keys (traffic_total_requests, traffic_total_errors, traffic_total_404s, route) with the configured cache store and prefix.

Need me to:
- create a dedicated middleware/listener example that integrates with this package and increments the required cache keys, or
- open a branch and create README.md directly in the repo with this content?

If you want my help adding the middleware or creating a PR with this README.md, tell me which and I’ll create the file or the code changes.