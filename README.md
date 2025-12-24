# util.devi.tools

A mock API utility service that provides random success/failure responses for testing unreliable external services.

## Purpose

This tool simulates flaky third-party APIs, helping developers test:
- Retry mechanisms
- Error handling
- Circuit breakers
- Fallback strategies

## API Endpoints

### Authorization Check
```
GET /api/v{n}/authorize
```
Randomly returns:
- `200` with `{ "status": "success", "data": { "authorization": true } }`
- `403` with `{ "status": "fail", "data": { "authorization": false } }`

### Notification
```
POST /api/v{n}/notify
```
Randomly returns:
- `204` (empty body)
- `504` with `{ "status": "error", "message": "The service is not available, try again later" }`

All responses include a fun `X-HTTP-Status-Cat` header linking to [http.cat](https://http.cat).

## Local Development

```bash
make up      # Start containers
make down    # Stop containers
make logs    # View logs
make shell   # Open shell in container
```

The service runs on the `reverse-proxy` network and is accessible at `util.devi.tools` when configured with a local reverse proxy.

## Testing

```bash
make test    # Run tests inside container
```

## Requirements

- Docker
- Docker Compose
- Make
- External `reverse-proxy` network

## Tech Stack

- PHP 8.3
- Nginx (via webdevops/php-nginx image)
- Docker
