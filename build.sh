#!/usr/bin/env bash
# Builds wp-fix-plugin-does-not-exist-notices-X.Y.Z.zip with a
# wp-fix-plugin-does-not-exist-notices/ folder inside, leaving out the files
# listed in .distignore. X.Y.Z is the Version: header of the main file.
# Builds only: it does not tag, publish or upload anything.
set -euo pipefail

main() {
	local root slug version build_dir zip_file
	root="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
	slug="wp-fix-plugin-does-not-exist-notices"
	version="$(sed -n 's/^ \* Version:[[:space:]]*//p' "$root/$slug.php" | tr -d '[:space:]')"

	if [[ -z "$version" ]]; then
		printf 'Could not read Version: from %s.php\n' "$slug" >&2
		return 1
	fi

	build_dir="$root/build"
	zip_file="$root/$slug-$version.zip"

	rm -rf "$build_dir" "$zip_file"
	mkdir -p "$build_dir/$slug"
	rsync -a --exclude-from="$root/.distignore" "$root/" "$build_dir/$slug/"

	(cd "$build_dir" && zip -qr "$zip_file" "$slug")
	rm -rf "$build_dir"

	printf 'Built %s\n' "$zip_file"
	return 0
}

main "$@"
