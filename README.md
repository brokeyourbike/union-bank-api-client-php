# union-bank-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/union-bank-api-client-php)](https://github.com/brokeyourbike/union-bank-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/union-bank-api-client/downloads)](https://packagist.org/packages/brokeyourbike/union-bank-api-client)
[![License: MPL-2.0](https://img.shields.io/badge/license-MPL--2.0-purple.svg)](https://github.com/brokeyourbike/union-bank-api-client-php/blob/main/LICENSE)
[![tests](https://github.com/brokeyourbike/union-bank-api-client-php/actions/workflows/tests.yml/badge.svg)](https://github.com/brokeyourbike/union-bank-api-client-php/actions/workflows/tests.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/763d6f7cfcf9c1c43056/maintainability)](https://codeclimate.com/github/brokeyourbike/union-bank-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/763d6f7cfcf9c1c43056/test_coverage)](https://codeclimate.com/github/brokeyourbike/union-bank-api-client-php/test_coverage)

Union Bank API Client for PHP

## Installation

```bash
composer require brokeyourbike/union-bank-api-client
```

## Usage

```php
use BrokeYourBike\UnionBank\Client;
use BrokeYourBike\UnionBank\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);
assert($cache instanceof \Psr\SimpleCache\CacheInterface);

$apiClient = new Client($config, $httpClient, $cache);
$apiClient->fetchToken();
```

## Authors

- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/union-bank-api-client-php/blob/main/LICENSE)
