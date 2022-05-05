#!/bin/bash
# This script makes links between different dialects

ROOT_DIR="`dirname $0`/.."
LANGUAGE_DIR="i18n"
TRANSLATION_SRC_DIR="$ROOT_DIR/$LANGUAGE_DIR"
TRANSLATION_EXT="csv"
TRANLATION_PREFIX=""
DEBUG=0

TRANS_LOCALES_DEFINITIONS="
en_US=en_IE,en_GB
es_ES
pt_PT
it_IT
de_DE=de_AT
nl_NL=nl_BE
fr_FR=fr_BE,fr_LU
"

# {{{ function quit
#
quit() {
    echo
    echo -e "ERROR: $@"
    usage
    exit 1
}
export -f quit
# }}}
# {{{ function usage
#
usage() {
    echo
    echo "This script compiles makes links between different dialects"
    echo
    echo "USAGE: $0 [OPTIONS]"
    echo
    echo "WHERE OPTIONS ARE:"
    echo
    echo "   -h|--help    print this message and exit without error"
    echo "   -d|--debug   debug (very verbose) mode"
    echo
}
export -f usage
# }}}
# {{{ function debug
#
debug() {
    [[ $DEBUG -eq 1 ]] && echo -e "DEBUG: $@"
}
export -f debug
# }}}
# {{{ function make_links
#
make_links() {
    debug "Running make_links('$1', '$2')"
    local locale="$1"
    local locale_links="${2//,/ }"
    local locale_file="$TRANSLATION_SRC_DIR/$TRANLATION_PREFIX$locale.$TRANSLATION_EXT"
    [[ "x$locale" == "x$locale_links" || -z "$locale_links" ]] && debug "Bad given links" && return 1
    [[ ! -e $locale_file ]] && debug "'$locale_file': file not found" && return 1

    debug "Linking $locale_file to $locale_links"
    for locale_link in $locale_links ; do
        local link_file="$TRANSLATION_SRC_DIR/$TRANLATION_PREFIX$locale_link.$TRANSLATION_EXT"
        [[ -e $link_file ]] && debug "rm $link_file" && rm $link_file
        debug "Linking $link_file" && ln -s `basename $locale_file` $link_file
    done
}
export -f make_links
# }}}

#{{{ GETTING ARGS
while [[ ! -z "$1" ]] ; do
    case $1 in
        -h|--help) usage ; exit ;;
        -d|--debug) DEBUG=1 ;;
        *) quit "'$1': unknown argument" ;;
    esac
    shift
done
# }}}

[[ ! -d "$TRANSLATION_SRC_DIR" ]] && quit "$TRANSLATION_SRC_DIR not such directory"

for locale_def in $TRANS_LOCALES_DEFINITIONS ; do
    locale=${locale_def%%=*}
    locale_links="${locale_def##*=}"
    make_links $locale $locale_links && debug "OK" || debug "ERROR"
done


exit
