#!/usr/bin/env bash
# Prepare test-only dependencies. No production vendor or Composer hooks are used.
set -euo pipefail
BASE=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)
DEST="$BASE/.test-deps"
mkdir -p "$DEST"
cp "$BASE/composer.json" "$BASE/composer.lock" "$DEST/"
curl -fsSL --max-time 60 https://getcomposer.org/download/latest-stable/composer.phar -o "$DEST/composer.phar"
curl -fsSL --max-time 30 https://getcomposer.org/download/latest-stable/composer.phar.sha256 -o "$DEST/composer.sha256"
python3 - "$DEST" <<'PY'
from pathlib import Path
import hashlib,sys
p=Path(sys.argv[1])
if hashlib.sha256((p/'composer.phar').read_bytes()).hexdigest() != (p/'composer.sha256').read_text().strip():
    raise SystemExit('Composer checksum mismatch')
PY
IMAGE=$(docker image inspect nextcloud:29-apache --format '{{.Id}}')
docker run --rm --memory 1g --cpus 1 --cap-drop ALL --security-opt no-new-privileges \
  --user "$(id -u):$(id -g)" --env COMPOSER_HOME=/tmp/composer \
  --mount "type=bind,source=$DEST,target=/app" --workdir /app --entrypoint php \
  "$IMAGE" composer.phar install --no-interaction --no-scripts --no-plugins --prefer-dist
