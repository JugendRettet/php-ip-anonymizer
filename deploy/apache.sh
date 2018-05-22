#!/bin/sh

# Caution:
# This assumes that
# - a log format 'common' is configured in apache's config

set -eu 

install IpAnonymizer.php /usr/local/bin/

# all config files, no directory
FILES="$( find /etc/apache2/sites-enabled/ ! -type d )"

echo "${FILES}" | while read file; do

  # is there a CustomLog yet?
  if fgrep CustomLog "${file}">/dev/null; then
    sed \
      -e 's/CustomLog/CustomLog "|\/usr\/local\/bin\/IpAnonymizer.php \x27 \x27/g' \
      -e 's/ combined/" common/g' \
      "${file}" > "${file}".tmp
    # don't destroy symlinks via 'sed -i'
    cat "${file}".tmp > "${file}"
    rm "${file}".tmp
  else
    echo "No CustomLog option yet in "${file}". Please add:"
    echo 'CustomLog "|/usr/local/bin/IpAnonymizer.php ' ' ${APACHE_LOG_DIR}/access.log common"'
    echo
  fi

  if fgrep ErrorLog "${file}">/dev/null; then
    sed \
      -e 's/ErrorLog/ErrorLog "|\/usr\/local\/bin\/IpAnonymizer.php \x27 \x27/g' \
      -e '/ErrorLog/ s/$/"/g' \
      "${file}" > "${file}".tmp
    cat "${file}".tmp > "${file}"
    rm "${file}".tmp
  else
    echo "No ErrorLog option yet in "${file}". Please add:"
    echo 'ErrorLog "|/usr/local/bin/IpAnonymizer.php ' ' ${APACHE_LOG_DIR}/error.log"'
    echo
  fi

done

