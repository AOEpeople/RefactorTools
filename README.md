# Refactoring Tools

This standalone PHP tool provides functionalities to refactor your code automatically.

- Removes "require" and "require_once" statements from PHP class files.
- ...
- ...

## Installation

You can easily install this tool via composer:
```bash
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

## Basic Usage

The usage is easy. Just execute the refactor-tool.php and you will get a list of available commands.
```bash
$ php refactor-tool.php help
```
### Commands / Functionalities

#### autoloading

This command removes all "require" and "require_once" statements from your PHP class files. This is very helpful, if you have just
implemented an autoloader and now want to clean up your code and make sure your autoloading is working correctly.

    Note, that only files with a ".php" file extension and a PHP class definition inside will be touched.

This will show you the help of the autoloading refactoring functionality:
```bash
$ php refactor-tool.php autoloading help
```

Here is an example usage:
```bash
$ php refactor-tool.php autoloading /path/to/my/project
```

##### --restrict

If you want to restrict the removals of requrie statements, you can use the "---restrict" option.
This option requires a valid regular expression. If given, only statements will be removed, that matches against this pattern.
Here is an example usage:
```bash
$ php refactor-tool.php autoloading /path/to/my/project --restrict '~my_pattern\-(.*)~'
```

#### ...

#### ...
