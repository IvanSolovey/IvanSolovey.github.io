#!/bin/bash
set -e

[ -d node_modules ] || npm install

node scripts/generate-og.js

git add .

if ! git diff --cached --quiet; then
  MSG="${1:-update}"
  git commit -m "$MSG"
fi

git push
