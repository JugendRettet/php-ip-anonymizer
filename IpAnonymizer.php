#!/usr/bin/env php
<?php

if (isset($argv[1])) {
  $delimiter = $argv[1];
}

if (isset($argv[2])) {
  $file = $argv[2];
}


class IpAnonymizer {
    /**
     * @var string IPv4 netmask used to anonymize IPv4 address.
     */
    public $ipv4NetMask = "255.255.255.0";

    /**
     * @var string IPv6 netmask used to anonymize IPv6 address.
     */
    public $ipv6NetMask = "ffff:ffff:ffff:0000:0000:0000:0000:0000";

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param $address string IP address that must be anonymized
     * @return string The anonymized IP address. Returns an empty string when the IP address is invalid.
     */
    public static function anonymizeIp($address) {
        $anonymizer = new IpAnonymizer();
        return $anonymizer->anonymize($address);
    }

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param $address string IP address that must be anonymized
     * @return string The anonymized IP address. Returns an empty string when the IP address is invalid.
     */
    public function anonymize($address) {
        $packedAddress = inet_pton($address);

        if (strlen($packedAddress) == 4) {
            return $this->anonymizeIPv4($address);
        } elseif (strlen($packedAddress) == 16) {
            return $this->anonymizeIPv6($address);
        } else {
            return "";
        }
    }

    /**
     * Anonymize an IPv4 address
     * @param $address string IPv4 address
     * @return string Anonymized address
     */
    public function anonymizeIPv4($address) {
        return inet_ntop(inet_pton($address) & inet_pton($this->ipv4NetMask));
    }

    /**
     * Anonymize an IPv6 address
     * @param $address string IPv6 address
     * @return string Anonymized address
     */
    public function anonymizeIPv6($address) {
        return inet_ntop(inet_pton($address) & inet_pton($this->ipv6NetMask));
    }
}

$ipAnonymizer = new IpAnonymizer();

/* read line for line from stdin */
while($line = fgets(STDIN)){
  /* whether a delimiter was given */
  if (isset($delimiter)) {
    $pieces = explode($delimiter, $line, 2);
    $ip = $pieces[0];
    /* whether there is anything else on the line after the delimiter */
    if (isset($pieces[1])) {
      /* put back in the delimiter, so that only the IP will have changed */
      $rest = "$delimiter$pieces[1]";
    }
  /* if not, the whole line is considered an IP */
  } else {
    $ip = $line;
  }
  /* whether the given string is a valid IP */
  /* if yes, anonymize */
  if (filter_var($ip, FILTER_VALIDATE_IP)) {
    $anonip = $ipAnonymizer->anonymize($ip);
    $anonline = $anonip;
    if (isset($rest)) {
      $anonline .= $rest;
    }
  /* if not, just print the line */
  } else {
    $anonline = $line;
  }

  if (isset($file)) {
    file_put_contents($file, $anonline, FILE_APPEND);
  } else {
    print "$anonline";
  }
}

?>
