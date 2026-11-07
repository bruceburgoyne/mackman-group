#!/usr/bin/env bash
# Preview Local pages in a browser that does not trust Local's certificate.
#
# Saves a copy of each page from the Local site, rewrites its links to
# http://localhost:8766, and serves the copies with the site's own
# wp-content (theme CSS, JavaScript and uploads), so pages render exactly
# as on the site. Not needed once Local's certificate is trusted.
#
#   scripts/preview.sh                 refresh the default pages
#   scripts/preview.sh name=/path/ ... refresh these pages as name.html
#   scripts/preview.sh --serve         refresh, then serve on port 8766
#
# Then open http://localhost:8766/home.html (or research.html, news.html...).

set -euo pipefail

REPO="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SITE="$HOME/Local Sites/mackmang-mackman-group/app/public"
URL="https://mackmang-mackman-group.test"
OUT="$REPO/.preview"
PORT=8766
SERVE=0

pages=()
for arg in "$@"; do
	if [ "$arg" = "--serve" ]; then
		SERVE=1
	else
		pages+=("$arg")
	fi
done
if [ ${#pages[@]} -eq 0 ]; then
	pages=(
		home=/
		research=/our-brands/mackman-research/
		news=/news/
		topic=/category/news/
		article=/iso-42001-and-iso-27001-certification/
	)
fi

mkdir -p "$OUT"
[ -e "$OUT/wp-content" ] || ln -s "$SITE/wp-content" "$OUT/wp-content"
[ -e "$OUT/wp-includes" ] || ln -s "$SITE/wp-includes" "$OUT/wp-includes"

for page in "${pages[@]}"; do
	name="${page%%=*}"
	path="${page#*=}"
	curl -sk --max-time 20 "$URL$path" \
		| sed -e "s#$URL#http://localhost:$PORT#g" \
		      -e "s#https:\\\\/\\\\/mackmang-mackman-group.test#http:\\\\/\\\\/localhost:$PORT#g" \
		> "$OUT/$name.html"
	echo "http://localhost:$PORT/$name.html"
done

if [ "$SERVE" -eq 1 ]; then
	if curl -s -o /dev/null --max-time 2 "http://localhost:$PORT/"; then
		echo "Already serving on port $PORT."
	else
		echo "Serving $OUT on http://localhost:$PORT (Ctrl+C to stop)"
		cd "$OUT" && exec python3 -m http.server "$PORT" --bind 127.0.0.1
	fi
fi
