# Attendance App

## How to Run Tests

To run the tests using PHPUnit, follow these steps:

1. Install the dependencies using Composer:
   ```bash
   composer install
   ```

2. Run the tests:
   ```bash
   composer test
   ```

3. View the test coverage report:
   Open the `coverage/index.html` file in your web browser to view the test coverage report.

## Troubleshooting

If you encounter the error "Command 'test' is not defined", make sure that you have added the following script to your `composer.json` file:
```json
"scripts": {
    "test": "phpunit"
}
```

## Test Results

When running the tests, you might see output like this:
```
....EE
```
This indicates that some tests have failed. The `E` characters represent errors in the tests. You should investigate and fix these errors to ensure all tests pass successfully.

## Fixing Code to Ensure Tests Run Successfully

If your tests are showing errors (indicated by `E`), you need to fix the code to ensure that all tests pass successfully. Here are some steps to help you debug and fix the errors:

1. Check the error messages: PHPUnit will provide detailed error messages indicating what went wrong. Read these messages carefully to understand the issue.

2. Review the test cases: Look at the test cases that are failing and understand what they are testing. Make sure that the code being tested meets the expected behavior.

3. Fix the code: Based on the error messages and the test cases, make the necessary changes to the code to fix the issues.

4. Run the tests again: After making the changes, run the tests again to see if the errors are resolved. Repeat the process until all tests pass successfully.

## Where to Check Errors

You can check the errors in the PHPUnit output. The detailed error messages will help you identify the issues in your code. Additionally, you can check the `coverage/index.html` file for the test coverage report, which will show you which parts of your code are not covered by tests.

## Coverage Report Path

The coverage report is generated in the `coverage` directory. You can find the `index.html` file inside this directory to view the test coverage report.

## Note

If the coverage report is not generated, make sure that you have configured the `phpunit.xml` file correctly. The `phpunit.xml` file should include the following configuration to generate the coverage report:
```xml
<coverage processUncoveredFiles="true">
    <include>
        <directory suffix=".php">./</directory>
    </include>
    <report>
        <html outputDirectory="./coverage"/>
    </report>
</coverage>
```

## What to Install

To run the tests and generate the coverage report, you need to install the following:

1. [Composer](https://getcomposer.org/): A dependency manager for PHP.
2. [PHPUnit](https://phpunit.de/): A testing framework for PHP. This will be installed automatically when you run `composer install`.
3. [Xdebug](https://xdebug.org/): A PHP extension for debugging and code coverage analysis. You can install it by following the instructions on the Xdebug website.

## How to Run Xdebug

To run Xdebug in this case, follow these steps:

1. Install Xdebug by following the instructions on the [Xdebug website](https://xdebug.org/docs/install).
2. Enable Xdebug in your `php.ini` file by adding the following lines:
   ```ini
   zend_extension=xdebug.so
   xdebug.mode=coverage
   xdebug.start_with_request=yes
   ```
3. Restart your web server or PHP-FPM service to apply the changes.
4. Run the tests using PHPUnit as described in the "How to Run Tests" section. The code coverage report will be generated in the `coverage` directory.
