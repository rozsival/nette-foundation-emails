#!/usr/bin/env bash

source setup

"$base/vendor/bin/tester" -c "$ini" "$tests"

exit $?
