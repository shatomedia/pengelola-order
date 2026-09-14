set -eu
base=/tmp/sales-browser-audit
if [ "${GITHUB_ACTIONS:-}" != "true" ]; then echo "Run on GitHub hosted CI only" >&2; exit 2; fi
docker run --user "$(id -u):$(id -g)" --rm --network none --read-only --cap-drop ALL --security-opt no-new-privileges --memory 1g --cpus 1 --tmpfs /tmp --env-file "$base/test.env" --mount "type=bind,source=$base/app,target=/app" --workdir /app --entrypoint php nextcloud:29-apache /app/browser-init.php
docker network create --internal sales-browser-audit-net
docker run --user "$(id -u):$(id -g)" -d --name sales-browser-audit-web --network sales-browser-audit-net -p 127.0.0.1::8000 --read-only --cap-drop ALL --security-opt no-new-privileges --memory 512m --cpus 1 --tmpfs /tmp --env-file "$base/test.env" --mount "type=bind,source=$base/app,target=/app" --workdir /app --entrypoint php nextcloud:29-apache artisan serve --host=0.0.0.0 --port=8000
docker port sales-browser-audit-web 8000 > "$base/address.txt"

for attempt in $(seq 1 30); do
    if curl -fsS "http://$(cat "$base/address.txt")/login" -o /dev/null; then exit 0; fi
    sleep 1
done
exit 1
