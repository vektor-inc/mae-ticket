#!/usr/bin/env bash

set -ex
export WP_PLUGIN=$(curl "https://api.wordpress.org/plugins/info/1.0/woocommerce.json" | jq -r .download_link)
curl -s $WP_PLUGIN -o plugin.zip
unzip plugin.zip -d .plugin
rm -f plugin.zip
