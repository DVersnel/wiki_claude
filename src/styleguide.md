<b> Table of contents </b>
- [File structure](#file-structure)
  - [Include Statements](#include-statements)
  - [Class Structure Order](#class-structure-order)
- [Separators](#separators)
- [Indentation](#indentation)
- [Naming conventions](#naming-conventions)
  - [Variables and Functions](#variables-and-functions)
- [Error Handling](#error-handling)
- [Database Conventions \& Security](#database-conventions--security)
  - [Query Structure](#query-structure)
  - [Secutiry](#secutiry)
  - [Column Naming](#column-naming)
- [Documentation](#documentation)
  - [Function Documentation](#function-documentation)
- [Comments](#comments)


## File structure

### Include Statements
- Place all include/require statements at the top of files
- Use relative paths consistently: `include "../interfaces/iArticleModel.php";`

### Class Structure Order
1. Properties (private, protected, public)
2. Constructor
3. Public methods
4. Protected methods  
5. Private methods

## Separators

Between code, try to use separators in a way that corresponds to the level of difference between what's being separated. Maximum separator length should be kept to a maximum length of 100, and indented according to the level of code that's being separated.
The VSCode extension [Comment Divider](https://marketplace.visualstudio.com/items?itemName=stackbreak.comment-divider) can be used to this neatly.

- Functions within the same type are separated with:
  ```php
  // ============================================================================================== //
  ```
  These are also used to separate attributes from methods in class definitions.
- Function categories can be delineated with:
  ```php
  // ========================================== Category ========================================== //
  ```

- Larger banner may be used to serve as clearer separations of concerns, e.g. for separating public, protected and private functions
  ```php
  // ============================================================================================== //
  //                                        Protected methods                                       //
  // ============================================================================================== //
  ```
Separators after a previous function definitions should keep one empty line after the function definition end. Function definitions (or the docstring right above them) should be started directly after a separator line. Example:
```php
function foo() {

}

// ============================================================================================== //
/**
 * Documentation
 */
function bar() {

}
```

## Indentation

- Try to stick to using Allman style indentation, which keeps the opening curly bracket on the same line as the statement it belongs to:
  ``` php
  // Function definitions
  function foo(string $arg1, int $arg2) : array|bool 
  {
    // Do something
  }

  // Loops, conditionals
  while (x == y) 
  {
    foo();
    bar();
  }
  ```
- Tabs are 4 spaces long;


## Naming conventions

### Variables and Functions
- Use camelCase for variables, function names: `$articleId`, `getUserById()`.
- Use PascalCase for class names: `ArticleModel`. Interfaces should be prefixed with a lowercase i: `iArticleModel`;
- Use descriptive names that clearly indicate purpose

## Error Handling

- Use return types `array|bool` or `int|bool` for methods that can fail
- Return `false` on failure, actual data on success
- Consider using exceptions for critical errors

## Database Conventions & Security

### Query Structure
- <b>Use prepared statements for all user input</b>
- Use meaningful table aliases: `SELECT u.name FROM users u`
- Capitalize SQL keywords: `SELECT`, `FROM`, `WHERE`

### Security
- Always validate and sanitize user input
- Escape output when displaying user content
- Never expose sensitive information in error messages

### Column Naming
- Use camelCase for consistency with PHP: `userId`, `createdAt`
- Avoid abbreviations unless widely understood

## Documentation

### Function Documentation
- Use PHPDoc format for all public methods
- Include parameter types and return types
- Add brief description of functionality

Example:
```php
/**
 * Retrieves user information by email address
 * @param string $email The user's email address
 * @return array|bool User data array or false if not found
 */
public function getUserByEmail($email) : array|bool 
{
    // Implementation
}
```

## Comments

- Use single-line comments (`//`) for brief explanations
- Use multi-line comments (`/* */`) for longer explanations
- Avoid obvious comments; focus on explaining "why" not "what"