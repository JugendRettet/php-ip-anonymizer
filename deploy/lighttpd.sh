#!/bin/sh

set -eu

CONFIG='/etc/lighttpd/conf.d/access_log.conf'

install IpAnonymizer.php /usr/local/bin/

sed -i 's/accesslog.filename/# GDPR accesslog.filename/g' "${CONFIG}"
sed -i 's/accesslog.format/# GDPR accesslog.format/g' "${CONFIG}"

echo '
# GDPR
' >> "${CONFIG}"

cat >> "${CONFIG}" <<__EOF__
accesslog.filename  = "|/usr/local/bin/IpAnonymizer.php ' ' /var/log/lighttpd/access.log"
accesslog.format = "%h %l %u %t \"%r\" %>s %O"
__EOF__

