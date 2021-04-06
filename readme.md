# Foxentry scraper

## Dependencies
- PHP-CLI: `8.0`
- ELASTIC: `7.12.0`
- NodeJS: `v12.21.0`

## Installation

### Elastic
1. Configure connection in `.env` file.

### PHP scraper
1. Install dependencies via `composer i` or `./docker.sh composer i`.
1. Execute PHP app `php ./bin/console.php`

### NodeJS scraper
1. Install dependencies `yarn i`
1. Execute NodeJS app `yarn start` or `node ./bin/console.js`

### Elastic in docker-compose (optional)
1. Build docker container: `docker-compose -f docker-compose.dev.yml up -d`
2. If elastic containers failing, you have to do this:
    1. Insert `vm.max_map_count = 262144` into the `/etc/sysctl.conf`
    2. Reload settings `sysctl --system`