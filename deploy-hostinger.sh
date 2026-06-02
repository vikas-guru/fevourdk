#!/usr/bin/env bash
set -Eeuo pipefail

# Fevourd-K Hostinger FTP deployment.
# Keep this file local/private because it contains production credentials.

APP_URL="${APP_URL:-https://fevourdk.online}"
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-u794352432_fevoud}"
DB_USERNAME="${DB_USERNAME:-u794352432_fevoud}"
DB_PASSWORD="${DB_PASSWORD:-u794352432_fevouD}"

FTP_HOST="${FTP_HOST:-89.117.139.88}"
FTP_PORT="${FTP_PORT:-21}"
FTP_USERNAME="${FTP_USERNAME:-u794352432.fevourdk.online}"
FTP_PASSWORD="${FTP_PASSWORD:-Daiva@Vikas2026}"
# This FTP login lands directly in the domain web root. Override to public_html
# only if your FTP account starts one level above it.
FTP_REMOTE_DIR="${FTP_REMOTE_DIR:-}"
REMOTE_APP_DIR="${REMOTE_APP_DIR:-laravel_app}"
UPLOAD_PARALLELISM="${UPLOAD_PARALLELISM:-10}"

# Set RUN_REMOTE_MIGRATIONS=1 when the uploaded site should run migrations once.
# This creates a temporary protected PHP runner, calls it, then deletes it.
RUN_REMOTE_MIGRATIONS="${RUN_REMOTE_MIGRATIONS:-0}"
RUN_REMOTE_SEEDERS="${RUN_REMOTE_SEEDERS:-0}"
REMOTE_MIGRATION_TOKEN="${REMOTE_MIGRATION_TOKEN:-$(date +%s)-$RANDOM-$RANDOM}"

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DEPLOY_DIR="$ROOT_DIR/.deploy"
STAGE_DIR="$DEPLOY_DIR/hostinger-public_html"
APP_STAGE_DIR="$STAGE_DIR/$REMOTE_APP_DIR"
ARCHIVE_NAME="hostinger-deploy-package.zip"
ARCHIVE_PATH="$DEPLOY_DIR/$ARCHIVE_NAME"
RUNNER_NAME="hostinger-deploy-runner.php"

log() {
  printf '\n[%s] %s\n' "$(date '+%H:%M:%S')" "$*"
}

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    printf 'Missing required command: %s\n' "$1" >&2
    exit 1
  fi
}

env_value() {
  local key="$1"
  if [[ -f "$ROOT_DIR/.env" ]]; then
    awk -F= -v key="$key" '$1 == key { sub(/^[^=]*=/, ""); print; exit }' "$ROOT_DIR/.env" | sed 's/^"//; s/"$//'
  fi
}

urlencode_path() {
  php -r '
    $path = $argv[1];
    $parts = array_map("rawurlencode", explode("/", $path));
    echo implode("/", $parts);
  ' "$1"
}

ftp_upload_file() {
  local file="$1"
  local rel="${file#$STAGE_DIR/}"
  local encoded_rel
  local remote_rel
  encoded_rel="$(urlencode_path "$rel")"
  if [[ -n "$FTP_REMOTE_DIR" ]]; then
    remote_rel="$FTP_REMOTE_DIR/$encoded_rel"
  else
    remote_rel="$encoded_rel"
  fi

  curl --silent --show-error --fail --ftp-create-dirs \
    --ftp-method nocwd \
    --user "$FTP_USERNAME:$FTP_PASSWORD" \
    --upload-file "$file" \
    "ftp://$FTP_HOST:$FTP_PORT/$remote_rel" >/dev/null
}

ftp_upload_local_file() {
  local file="$1"
  local rel="$2"
  local encoded_rel
  local remote_rel
  encoded_rel="$(urlencode_path "$rel")"
  if [[ -n "$FTP_REMOTE_DIR" ]]; then
    remote_rel="$FTP_REMOTE_DIR/$encoded_rel"
  else
    remote_rel="$encoded_rel"
  fi

  curl --silent --show-error --fail --ftp-create-dirs \
    --ftp-method nocwd \
    --user "$FTP_USERNAME:$FTP_PASSWORD" \
    --upload-file "$file" \
    "ftp://$FTP_HOST:$FTP_PORT/$remote_rel" >/dev/null
}

ftp_delete_file() {
  local rel="$1"
  local encoded_rel
  local remote_rel
  encoded_rel="$(urlencode_path "$rel")"
  if [[ -n "$FTP_REMOTE_DIR" ]]; then
    remote_rel="$FTP_REMOTE_DIR/$encoded_rel"
  else
    remote_rel="$encoded_rel"
  fi

  curl --silent --show-error --fail \
    --user "$FTP_USERNAME:$FTP_PASSWORD" \
    --quote "DELE /$remote_rel" \
    "ftp://$FTP_HOST:$FTP_PORT/" >/dev/null || true
}

write_env_file() {
  local app_key
  app_key="$(env_value APP_KEY)"
  if [[ -z "$app_key" ]]; then
    app_key="base64:$(openssl rand -base64 32)"
  fi

  cat > "$APP_STAGE_DIR/.env" <<EOF
APP_NAME="Fevourd-K"
APP_ENV=$APP_ENV
APP_KEY=$app_key
APP_DEBUG=$APP_DEBUG
APP_URL=$APP_URL

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=$DB_CONNECTION
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
CACHE_STORE=file

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@fevourdk.online"
MAIL_FROM_NAME="Fevourd-K"

VITE_APP_NAME="Fevourd-K"

OTP_PILOT_MODE=true
OTP_PILOT_CODE=123456
EOF
}

write_migration_runner() {
  cat > "$STAGE_DIR/$RUNNER_NAME" <<'PHP'
<?php

declare(strict_types=1);

$expectedToken = getenv('HOSTINGER_DEPLOY_TOKEN') ?: '__TOKEN__';
$providedToken = $_GET['token'] ?? '';

if (!hash_equals($expectedToken, $providedToken)) {
    http_response_code(404);
    exit('Not found');
}

set_time_limit(300);

$archive = __DIR__.'/__ARCHIVE_NAME__';

if (!class_exists(ZipArchive::class)) {
    http_response_code(500);
    exit('PHP ZipArchive extension is not enabled on this hosting account.');
}

if (!is_file($archive)) {
    http_response_code(500);
    exit('Deployment archive not found.');
}

$zip = new ZipArchive();
if ($zip->open($archive) !== true) {
    http_response_code(500);
    exit('Unable to open deployment archive.');
}

if (!$zip->extractTo(__DIR__)) {
    $zip->close();
    http_response_code(500);
    exit('Unable to extract deployment archive.');
}

$zip->close();

chdir(__DIR__.'/__REMOTE_APP_DIR__');

require __DIR__.'/__REMOTE_APP_DIR__/vendor/autoload.php';
$app = require __DIR__.'/__REMOTE_APP_DIR__/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = [];

if (($_GET['migrate'] ?? '0') === '1') {
    $commands[] = ['migrate', ['--force' => true]];
}

if (($_GET['seed'] ?? '0') === '1') {
    $commands[] = ['db:seed', ['--force' => true]];
}

$output = [];

foreach ($commands as [$command, $parameters]) {
    try {
        Artisan::call($command, $parameters);
        $output[$command] = trim(Artisan::output());
    } catch (Throwable $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'ok' => false,
            'failed_command' => $command,
            'message' => $e->getMessage(),
            'output' => $output,
        ], JSON_PRETTY_PRINT);
        exit;
    }
}

@unlink($archive);
@unlink(__FILE__);

header('Content-Type: application/json');
echo json_encode(['ok' => true, 'output' => $output], JSON_PRETTY_PRINT);
PHP

  perl -0pi -e "s/__TOKEN__/$REMOTE_MIGRATION_TOKEN/g" "$STAGE_DIR/$RUNNER_NAME"
  perl -0pi -e "s#__REMOTE_APP_DIR__#$REMOTE_APP_DIR#g" "$STAGE_DIR/$RUNNER_NAME"
  perl -0pi -e "s#__ARCHIVE_NAME__#$ARCHIVE_NAME#g" "$STAGE_DIR/$RUNNER_NAME"
}

prepare_stage() {
  log "Preparing production dependencies"
  composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
  mkdir -p "$DEPLOY_DIR/npm-cache"
  npm ci --cache "$DEPLOY_DIR/npm-cache" --prefer-offline
  npm run build

  log "Creating Hostinger public_html staging tree"
  rm -rf "$STAGE_DIR"
  mkdir -p "$STAGE_DIR" "$APP_STAGE_DIR"

  rsync -a "$ROOT_DIR/" "$APP_STAGE_DIR/" \
    --exclude '.deploy' \
    --exclude '.git' \
    --exclude '.env' \
    --exclude '.env.backup' \
    --exclude '.env.production' \
    --exclude '.DS_Store' \
    --exclude 'node_modules' \
    --exclude '.playwright-mcp' \
    --exclude '*.png' \
    --exclude '*.zip' \
    --exclude 'deploy-hostinger.sh' \
    --exclude 'public' \
    --exclude 'agentdb.rvf*' \
    --exclude 'ruvector.db' \
    --exclude 'storage/logs/*'

  rsync -a "$ROOT_DIR/public/" "$STAGE_DIR/" \
    --exclude 'storage' \
    --exclude 'hot'
  mkdir -p "$APP_STAGE_DIR/public"
  rsync -a "$ROOT_DIR/public/build/" "$APP_STAGE_DIR/public/build/"
  cp "$ROOT_DIR/public/.htaccess" "$STAGE_DIR/.htaccess"

  perl -0pi -e "s#__DIR__\\s*\\.\\s*'/\\.\\./storage/framework/maintenance.php'#__DIR__.'/$REMOTE_APP_DIR/storage/framework/maintenance.php'#g; s#__DIR__\\s*\\.\\s*'/\\.\\./vendor/autoload.php'#__DIR__.'/$REMOTE_APP_DIR/vendor/autoload.php'#g; s#__DIR__\\s*\\.\\s*'/\\.\\./bootstrap/app.php'#__DIR__.'/$REMOTE_APP_DIR/bootstrap/app.php'#g" "$STAGE_DIR/index.php"

  cat >> "$STAGE_DIR/.htaccess" <<EOF

# Keep Laravel internals private when the FTP root is also the web root.
RedirectMatch 404 ^/$REMOTE_APP_DIR(/|$)
RedirectMatch 404 ^/(app|bootstrap|config|database|resources|routes|storage|vendor)(/|$)
RedirectMatch 404 ^/(artisan|composer\.(json|lock)|package(-lock)?\.json|vite\.config\.js|tailwind\.config\.js|postcss\.config\.js|phpunit\.xml)$
EOF

  mkdir -p "$APP_STAGE_DIR/storage/app/public" "$APP_STAGE_DIR/storage/framework/cache/data" "$APP_STAGE_DIR/storage/framework/sessions" "$APP_STAGE_DIR/storage/framework/views" "$APP_STAGE_DIR/storage/logs"
  touch "$APP_STAGE_DIR/storage/logs/.gitignore"
  rm -f "$APP_STAGE_DIR/bootstrap/cache/config.php" "$APP_STAGE_DIR/bootstrap/cache/routes-v7.php" "$APP_STAGE_DIR/bootstrap/cache/events.php"

  write_env_file

  write_migration_runner

  log "Creating deployment archive"
  rm -f "$ARCHIVE_PATH"
  (cd "$STAGE_DIR" && zip -qry "$ARCHIVE_PATH" . -x "$RUNNER_NAME")
}

upload_stage() {
  local target_label="/"
  if [[ -n "$FTP_REMOTE_DIR" ]]; then
    target_label="/$FTP_REMOTE_DIR"
  fi

  log "Uploading to Hostinger FTP $FTP_HOST:$FTP_PORT$target_label"

  log "Uploading archive and protected extractor with curl"
  ftp_upload_local_file "$ARCHIVE_PATH" "$ARCHIVE_NAME"
  ftp_upload_local_file "$STAGE_DIR/$RUNNER_NAME" "$RUNNER_NAME"

  printf '\n'
}

run_remote_migrations() {
  log "Calling protected remote extractor"
  local seed_flag="0"
  local migrate_flag="0"
  if [[ "$RUN_REMOTE_MIGRATIONS" == "1" ]]; then
    migrate_flag="1"
  fi
  if [[ "$RUN_REMOTE_SEEDERS" == "1" ]]; then
    seed_flag="1"
  fi

  curl --silent --show-error --fail \
    "$APP_URL/$RUNNER_NAME?token=$(php -r 'echo rawurlencode($argv[1]);' "$REMOTE_MIGRATION_TOKEN")&migrate=$migrate_flag&seed=$seed_flag"

  printf '\n'
}

main() {
  require_cmd composer
  require_cmd npm
  require_cmd php
  require_cmd rsync
  require_cmd curl
  require_cmd openssl
  require_cmd zip

  prepare_stage
  upload_stage
  run_remote_migrations

  log "Deploy complete: $APP_URL"
}

main "$@"
