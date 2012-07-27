#!/bin/bash
cd '/var/www/files/dropbox/42/links'
cur_time=$(date +%s)
mkdir -p "../trash/links/$(date +%Y)/$(date +%U)"
for file in *; do
	expire_time=$(tail -1 "$file")
	if [ "$cur_time" -gt "$expire_time" ]; then
		mv "$file" "../trash/links/$(date +%Y)/$(date +%U)"
	fi
done
