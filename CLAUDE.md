# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based mock API utility service (`util.devi.tools`) that provides random success/failure responses for testing purposes. It serves as a testing tool for simulating unreliable external services.

## Architecture

- **Single-file PHP application** (`public/index.php`) using PHP 8.3+
- **Docker-based deployment** with nginx via `webdevops/php-nginx:8.3` image
- **Deployment**: GitLab CI pushes to a remote git server; tevun hooks handle container orchestration

### API Endpoints

| Method | Route | Behavior |
|--------|-------|----------|
| GET | `/api/v{n}/authorize` | Randomly returns 200 (authorized) or 403 (unauthorized) |
| POST | `/api/v{n}/notify` | Randomly returns 204 (success) or 504 (timeout error) |

## Commands

Using Makefile:
```bash
make setup     # Create network and start containers
make network   # Create reverse-proxy network (if not exists)
make up        # Start containers
make down      # Stop containers
make logs      # View container logs
make test      # Run tests inside container
make shell     # Open shell in container
```

Using Composer (inside container):
```bash
composer test  # Run tests
```

## Testing

Tests use a custom minimal framework (`tests/framework.php`) with no external dependencies.

```bash
# Run all tests via Docker
make test

# Run tests directly (inside container)
php tests/run.php
```

Test files follow the pattern `tests/*_test.php`.

## Deployment

The project uses tevun hooks for deployment automation:
- `setup.sh`: Copies `.env.stage` and `docker-compose.yml.stage` to production files
- `pre-checkout.sh`: Stops running containers before checkout
- `post-checkout.sh`: Starts containers after checkout

GitLab CI deploys master branch automatically via git push to the configured `DEPLOY_REMOTE`.

## Code Style

- All code, comments, and documentation must be written in English
