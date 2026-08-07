#!/usr/bin/env bash
#
# Runs the test suite.
#
#   ./tests/run.sh
#
# PHP defaults to whatever is on PATH; override with PP_PHP.

set -uo pipefail

cd "$(dirname "$0")"

PHP="${PP_PHP:-php}"

if ! command -v "$PHP" >/dev/null 2>&1; then
	echo "PHP not found. Set PP_PHP to your binary, e.g."
	echo "  PP_PHP=/Applications/MAMP/bin/php/php8.3.14/bin/php ./tests/run.sh"
	exit 1
fi

failed=0

run() {
	local label="$1"
	shift

	if "$PHP" "$@" >/tmp/ppl-test-out.txt 2>&1; then
		printf '  PASS  %-34s %s\n' "$label" "$(grep -E '^passed:' /tmp/ppl-test-out.txt | tail -1)"
	else
		failed=1
		printf '  FAIL  %s\n' "$label"
		cat /tmp/ppl-test-out.txt
	fi
}

echo "Unit (no WordPress required)"
run "catalogue" unit-catalogue.php
run "coexistence" unit-coexistence.php

echo
if [ "$failed" -eq 0 ]; then
	echo "All suites passed."
else
	echo "Some suites failed."
fi

exit "$failed"
