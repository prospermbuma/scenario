Unit Testing

# Setup and Teardown:

The setUp() method creates a mock database connection and the required users table before each test.
The tearDown() method cleans up by dropping the users table and closing the database connection after each test.

# Unit Tests:

testTableCreation: Verifies that the users table is created successfully.

testUserRegistration: Tests that a user is inserted into the database successfully.

testDuplicateEmailRegistration: Checks that duplicate email registration is handled (throws an exception in this case).

testEmptyFields: Validates that empty fields are detected.

testInvalidEmail: Ensures that invalid email addresses are identified.

# PHPUnit Integration:

Save the test file as UserRegistrationTest.php.
Run the tests using phpunit UserRegistrationTest.php.
