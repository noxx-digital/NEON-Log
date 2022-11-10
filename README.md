# NEON-Log

Loggin Class.

## Installation

```
composer require noxx/neon-log
```

## Usage

```php

require __DIR__ . '/../vendor/autoload.php';

use Neon\Util\Log;

$log = new Log( '/tmp/service.log' );
$log->info('Hello!');

```
