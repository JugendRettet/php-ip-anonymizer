# IP address anonymizer for PHP

This is a one-file PHP script to anonymize an IP address on a line given
through STDIN.
You can for example use it as a pipe for web server logs, so that no private
IP addresses are recorded.

Both IPv4 and IPv6 addresses are supported. Addresses are anonymized to their network ID.

The default settings anonymize an IP address to a /24 subnet (IPv4) or a /48 subnet (IPv6), but these can be customized.

For instance, the IPv4 address `192.168.178.123` is anonymized by default to `192.168.178.0`.

The IPv6 address `2001:0db8:85a3:08d3:1319:8a2e:0370:7347` is anonymized by default to `2001:0db8:85a3::`.

The IP address is expected to occur on the beginning of the line. A delimiter
can can be given as first argument. The output will be written to a file
when given as second argument.

## Example

```
$ log='2001:0db8:85a3:08d3:1319:8a2e:0370:7347 - - [16/May/2018:06:30:01 +0200] "HEAD / HTTP/1.1" 301 235 "-"
192.168.2.123 - - [16/May/2018:06:30:02 +0200] "HEAD / HTTP/1.1" 301 235 "-"'
$ echo -n "${log}" | ./anonymizer.php ' '; echo
> 2001:db8:85a3:: - - [16/May/2018:06:30:01 +0200] "HEAD / HTTP/1.1" 301 235 "-"
> 192.168.2.0 - - [16/May/2018:06:30:02 +0200] "HEAD / HTTP/1.1" 301 235 "-"
```

## License

This library is licensed under the MIT License. See the LICENSE file for the full license.

