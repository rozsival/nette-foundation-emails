#!/usr/bin/env bash

source setup

"$base/vendor/bin/tester" -c "$ini" "$tests" --coverage "$tests/temp/coverage.html" --coverage-src "$base/src"

exit $?