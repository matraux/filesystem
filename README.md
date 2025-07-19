# Introduction
[![View on GitLab](https://img.shields.io/badge/Primary%20Repo-GitLab-orange?logo=gitlab)](https://gitlab.com/matraux/filesystem)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/matraux/filesystem.svg)](https://packagist.org/packages/matraux/filesystem)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg)](https://php.net)

A PHP 8.4+ library that simplifies file and folder operations using an object-oriented API. Supports renaming, moving, streaming, and deletion with optional integration for PSR-7 and Nette\Http\FileUpload.

# Features
- File and folder manipulation with fluent API
- Relative and absolute path resolution
- File iteration via chunks (stream-style)
- Automatic cleanup of temporary files
- PSR-7 stream support `Psr\Http\Message\StreamInterface`
- Nette FileUpload support `Nette\Http\FileUpload`
- Easy extension via inheritance

# Installation
```bash
composer require matraux/filesystem
```

| version | PHP | Note
|----|---|---
| 1.0.0 | PHP 8.3 | Initial commit
| 1.0.9 | PHP 8.3 | Cache optimization
| 1.1.1 | PHP 8.4 | Property accessor PHP 8.4

# Usage
See [File](./doc/File.md) for advance instruction.
```php
use Matraux\FileSystem\File\File;

$file = File::fromPath('C:\Users\MATRAUX\Webs\Resources\FileSystem\log.txt');
echo $file->name;  // "log.txt"
```

See [Folder](./doc/Folder.md) for advance instruction.
```php
use Matraux\FileSystem\Folder\Folder;

$folder = Folder::create()->addPath('backup');
echo $folder->absolute; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\backup"
```

# Development
See [Development](./doc/Development.md) for debug, test instructions, static analysis, and coding standards.

# Support
For bug reports and feature requests, please use the [issue tracker](https://gitlab.com/matraux/filesystem/-/issues).