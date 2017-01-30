#!/bin/bash

langs="cs_CZ en_EN"
dir=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd $dir/..

for lang in $langs
do
    tx pull -l $lang
    xgettext --language=php --add-location --join-existing --output="$dir/module/Tohu/language/$lang.po" --from-code=UTF-8 --keyword=translate $(find "$dir/" -name '*.phtml') $(find "$dir/module/" -name '*.php')
    msgfmt "$dir/module/Tohu/language/$lang.po" -o "$dir/module/Tohu/language/$lang.mo"
done;

tx push -s

