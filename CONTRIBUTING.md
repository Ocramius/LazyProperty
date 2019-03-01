# Contributing

 * The project will follow strict [object calisthenics](http://www.slideshare.net/guilhermeblanco/object-calisthenics-applied-to-php)
 * Any contribution must provide tests for additional introduced conditions
 * Any un-confirmed issue needs a failing test case before being accepted
 * Pull requests must be sent from a new hotfix/feature branch, not from `master`.

## Installation

To install the project and run the tests, you need to clone it first:

```sh
$ git clone git://github.com/Ocramius/LazyProperty.git
```

You will then need to run a composer installation:

```sh
$ cd LazyProperty
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar update
```

## Testing

The PHPUnit version to be used is the one installed as a dev- dependency via composer:

```sh
$ ./vendor/bin/phpunit
```

Accepted coverage for new contributions is 80%. Any contribution not satisfying this requirement 
won't be merged.

## Code Style

The project follows the [Doctrine Coding Standard](https://github.com/doctrine/coding-standard). A configuration for php_codesniffer is shipped.
Please execute the checker command and make sure you meet the requirements:

```sh
$ ./vendor/bin/phpcs
```

On Mac, you might need to add `--parallel=1` due to a known [bug](https://github.com/squizlabs/PHP_CodeSniffer/issues/2304).

Disabling specific rules for parts of files where it's clearly a false positive is acceptable in some cases.
