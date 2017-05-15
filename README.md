# Host Getter
This project serves as a library to allow a simple interface for getting host information from a domain.
[![Build Status](https://travis-ci.org/corycollier/host-getter.svg?branch=master)](https://travis-ci.org/corycollier/host-getter)

## Usage
```php
<?php
require 'vendor/autoload.php'

$query = new HostGetter\Query();
$result = $query->find('example.com');
print_r($result);
```
