#!/bin/bash

srclang="en_GB"
langs="cs_CZ"
dir=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd $dir

tx pull

xgettext --language=php --add-location --output="$dir/module/Tohu/language/$srclang.po" --from-code=UTF-8 --keyword=translate $(find "$dir/" -name '*.phtml') $(find "$dir/module/" -name '*.php')

for lang in $langs
do
    msgfmt "$dir/module/Tohu/language/$lang.po" -o "$dir/module/Tohu/language/$lang.mo"
done;

tx push -s

