#!/bin/sh
cd /srv/www/pokl.cz
git reset --hard
echo -n "" > ./site/data/comments.json && chmod o+w ./site/data/comments.json
