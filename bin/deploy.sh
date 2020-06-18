#!/usr/bin/env bash

set -e

##
## https://deliciousbrains.com/deploying-wordpress-plugins-travis/
## https://github.com/marketplace/actions/wordpress-plugin-svn-deploy
## https://github.com/10up/action-wordpress-plugin-deploy/
##

if [[ -z "$TRAVIS" ]]; then
	echo "Script is only to be run by Travis CI" 1>&2
	exit 1
fi

if [[ -z "$WP_ORG_USERNAME" ]]; then
	echo "WordPress.org username not set" 1>&2
	exit 1
fi

if [[ -z "$WP_ORG_PASSWORD" ]]; then
	echo "WordPress.org password not set" 1>&2
	exit 1
fi

if [[ -z "$WP_ORG_PLUGIN" ]]; then
	echo "WordPress.org plugin not set" 1>&2
	exit 1
fi

if [[ -z "$TRAVIS_BRANCH" || "$TRAVIS_BRANCH" != "master" ]]; then
	echo "Build branch is required and must be 'master'" 1>&2
	exit 0
fi

if [[ -z "$TRAVIS_BUILD_DIR" ]]; then
	echo "Travis build dir is not set" 1>&2
	exit 0
fi

PLUGIN_BUILD_PATH="/tmp/deploy"

echo "ℹ︎ COMMIT is $TRAVIS_COMMIT"
echo "ℹ︎ BUILD PATH is $PLUGIN_BUILD_PATH"

mkdir -p "$PLUGIN_BUILD_PATH"
cd "$PLUGIN_BUILD_PATH"

SVN_URL="https://plugins.svn.wordpress.org/$WP_ORG_PLUGIN/"
SVN_DIR="$PLUGIN_BUILD_PATH/svn-$WP_ORG_PLUGIN"

# Checkout the SVN repo
echo "➤ Checking out .org/$WP_ORG_PLUGIN repository to $SVN_DIR..."
svn checkout --depth immediates "$SVN_URL" "$SVN_DIR"
cd "$SVN_DIR"
svn update --set-depth infinity assets
svn update --set-depth infinity trunk

echo "➤ Copying files..."
echo "ℹ︎ Using .distignore"
# Copy from current branch to /trunk, excluding dotorg assets
# The --delete flag will delete anything in destination that no longer exists in source
rsync -rc --exclude-from="$TRAVIS_BUILD_DIR/.distignore" "$TRAVIS_BUILD_DIR/" trunk/ --delete --delete-excluded

# Add everything and commit to SVN
# The force flag ensures we recurse into subdirectories even if they are already added
# Suppress stdout in favor of svn status later for readability
echo "➤ Preparing files..."
svn add . --force > /dev/null

# SVN delete all deleted files
# Also suppress stdout here
svn status | grep '^\!' | sed 's/! *//' | xargs -I% svn rm %@ > /dev/null

# Fix screenshots getting force downloaded when clicking them
# https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/
svn propset svn:mime-type image/png assets/*.png || true
svn propset svn:mime-type image/jpeg assets/*.jpg || true

svn status

# Commit to SVN
echo "➤ Committing files..."
svn commit -m "Update to commit $TRAVIS_COMMIT from GitHub" --no-auth-cache --non-interactive  --username "$WP_ORG_USERNAME" --password "$WP_ORG_PASSWORD"

echo "✓ Plugin deployed!"
