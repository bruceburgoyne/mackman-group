#!/usr/bin/env bash
# Quick health check for the theme and the Local site.
#
#   scripts/check.sh            lint, JSON, key pages
#   scripts/check.sh /some/url/ ...also load these extra paths
#
# 1. PHP syntax for the theme and scripts (Local's PHP).
# 2. JSON validity for ACF field groups and content files.
# 3. Loads key pages on the Local site and reports each status code.
# 4. Reports any PHP errors written to the log during the run.
#
# Exits non-zero if anything fails.

set -uo pipefail

REPO="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SITE="$HOME/Local Sites/mackmang-mackman-group"
URL="https://mackmang-mackman-group.test"
LOG="$SITE/logs/php/error.log"
FAIL=0

# shellcheck disable=SC1091
source "$SITE/app/.envrc" 2>/dev/null || { echo "Cannot load Local's environment from $SITE/app/.envrc"; exit 1; }

echo "== PHP syntax"
while IFS= read -r -d '' file; do
	out="$(php -l "$file" 2>&1)" || { echo "$out"; FAIL=1; }
done < <(find "$REPO/theme" "$REPO/scripts" -name '*.php' -print0)
[ "$FAIL" -eq 0 ] && echo "ok"

echo "== JSON"
json_fail=0
while IFS= read -r -d '' file; do
	python3 -I -m json.tool "$file" >/dev/null 2>&1 || { echo "invalid: ${file#"$REPO"/}"; json_fail=1; }
done < <(find "$REPO/theme/mackman-group-acf/acf-json" "$REPO/content" -name '*.json' -print0)
[ "$json_fail" -eq 0 ] && echo "ok" || FAIL=1

echo "== Pages"
before=0
[ -f "$LOG" ] && before=$(wc -l < "$LOG")
paths=(
	/
	/our-brands/mackman-research/
	/news/
	/news/page/2/
	/category/news/
	/iso-42001-and-iso-27001-certification/
	/contact-mackman-group/
	/no-such-page/
	"$@"
)
for path in "${paths[@]}"; do
	code=$(curl -sk -o /dev/null -w '%{http_code}' --max-time 20 "$URL$path")
	expected=200
	[ "$path" = "/no-such-page/" ] && expected=404
	if [ "$code" = "$expected" ]; then
		printf '%s  %s\n' "$code" "$path"
	else
		printf '%s  %s   <-- expected %s\n' "$code" "$path" "$expected"
		FAIL=1
	fi
done

echo "== New PHP errors"
if [ -f "$LOG" ]; then
	new=$(tail -n +"$((before + 1))" "$LOG")
	if [ -n "$new" ]; then
		echo "$new" | tail -20
		FAIL=1
	else
		echo "none"
	fi
fi

[ "$FAIL" -eq 0 ] && echo "== All checks passed" || echo "== Some checks failed"
exit "$FAIL"
