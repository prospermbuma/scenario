# Setup and Teardown:

The setUp() method creates a mock database connection and the required users table before each test.
The tearDown() method cleans up by dropping the users table and closing the database connection after each test.

# Unit Tests:

01 - testUserRegistration: Tests that a user is inserted into the database successfully.

02 - testDuplicateEmailRegistration: Checks that duplicate email registration is handled (throws an exception in this case).

03- testEmptyFields: Validates that empty fields are detected.

04 - testInvalidEmail: Ensures that invalid email addresses are identified.

05 - testPasswordMismatch: Ensures password one and two are identical.

# PHPUnit Integration:

Save the test file as UserRegistrationTest.php.

Run the tests using phpunit --testdox UserRegistrationTest.php if PHPUnit has been globally installed.

Run the tests using ./vendor/bin/phpunit --testdox ./test_cases/automated/UserRegistrationTest.php if PHPUnit has been locally installed in vendor directory via composer.

NOTE:

--testdox displays the messages accompanging the test cases.

--filter - Is used to test one case at a time. (Example: phpunit --testdox --filter UserRegistrationTest.php)