# IP address anonymizer for PHP

This is a library for PHP to anonymize IP addresses. This makes it easier to respect user privacy, and it makes it more
difficult to identify an end user by his IP address. Anonymizing IP addresses can be useful for a lot of cases where the
exact IP address is not important, for example in a statistical analysis.

This library supports both IPv4 and IPv6 addresses. Addresses are anonymized to their network ID.

The default settings anonymize an IP address to a /24 subnet (IPv4) or a /64 subnet (IPv6), but these can be customized.

For instance, the IP address `192.168.178.123` is anonymized by default to `192.168.178.0`.

The IP address `192.168.178.123` is anonymized by default to `192.168.178.0`.

## Example

```php
<?php
use geertw\IpAnonymizer\IpAnonymizer;
require 'vendor/autoload.php';

$ipAnonymizer = new IpAnonymizer();

var_dump($ipAnonymizer->anonymize('127.0.0.1'));
// returns 127.0.0.0

var_dump($ipAnonymizer->anonymize('192.168.178.123'));
// returns 192.168.178.0

var_dump($ipAnonymizer->anonymize('8.8.8.8'));
// returns 8.8.8.0

var_dump($ipAnonymizer->anonymize('::1'));
// returns ::

var_dump($ipAnonymizer->anonymize('::127.0.0.1'));
// returns ::

var_dump($ipAnonymizer->anonymize('2a03:2880:2110:df07:face:b00c::1'));
// returns 2a03:2880:2110:df07::

var_dump($ipAnonymizer->anonymize('2610:28:3090:3001:dead:beef:cafe:fed3'));
// returns 2610:28:3090:3001::
```