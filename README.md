# Introduction
[![Latest Version on Packagist](https://img.shields.io/packagist/v/matraux/filesystem.svg)](https://packagist.org/packages/matraux/filesystem)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg)](https://php.net)
[![Security Policy](https://img.shields.io/badge/Security-Policy-blue)](./.github/SECURITY.md)
[![Contributing](https://img.shields.io/badge/Contributing-Disabled-lightgrey?logo=github)](CONTRIBUTING.md)
[![QA Status](https://github.com/matraux/filesystem/actions/workflows/qa.yml/badge.svg)](https://github.com/matraux/filesystem/actions/workflows/qa.yml)
[![Issues](https://img.shields.io/github/issues/matraux/filesystem)](https://github.com/matraux/filesystem/issues)
[![Last Commit](https://img.shields.io/github/last-commit/matraux/filesystem)](https://github.com/matraux/filesystem/commits)


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
| 1.1.1 | PHP 8.4 | Property accessor
| 1.1.2 | PHP 8.4 | Quality Assurance

# Usage
See [File](./docs/File.md) for advance instruction.
```php
use Matraux\FileSystem\File\File;

$file = File::fromPath('C:\Users\MATRAUX\Webs\Resources\FileSystem\log.txt');
echo $file->name;  // "log.txt"
```

See [Folder](./docs/Folder.md) for advance instruction.
```php
use Matraux\FileSystem\Folder\Folder;

$folder = Folder::create()->addPath('backup');
echo $folder->absolute; // "C:\Users\MATRAUX\Webs\Resources\FileSystem\backup"
```


# Development
See [Development](./docs/Development.md) for debug, test instructions, static analysis, and coding standards.


# Support
For bug reports and feature requests, please use the [issue tracker](https://github.com/matraux/filesystem/issues).