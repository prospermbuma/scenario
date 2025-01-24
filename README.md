Unit Testing

# Install Composer

curl -sS https://getcomposer.org/installer | php

# Install PHPUnit via Composer:

composer require --dev phpunit/phpunit

# Verify Installation: Check the PHPUnit version:

./vendor/bin/phpunit --version

# Run PHPUnit: To execute your tests:

./vendor/bin/phpunit --testdox ./test_cases/automated/UserRegistrationTest.php
