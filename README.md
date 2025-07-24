# MATRAUX File System
[![Latest Version on Packagist](https://img.shields.io/packagist/v/matraux/filesystem.svg?logo=packagist&logoColor=white)](https://packagist.org/packages/matraux/filesystem)
[![Last release](https://img.shields.io/github/v/release/matraux/filesystem?display_name=tag&logo=github&logoColor=white)](https://github.com/matraux/filesystem/releases)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?logo=open-source-initiative&logoColor=white)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg?logo=php&logoColor=white)](https://php.net)
[![Security Policy](https://img.shields.io/badge/Security-Policy-blue?logo=bitwarden&logoColor=white)](./.github/SECURITY.md)
[![Contributing](https://img.shields.io/badge/Contributing-Disabled-lightgrey?logo=github&logoColor=white)](CONTRIBUTING.md)
[![QA Status](https://img.shields.io/github/actions/workflow/status/matraux/filesystem/qa.yml?label=Quality+Assurance&logo=checkmarx&logoColor=white)](https://github.com/matraux/filesystem/actions/workflows/qa.yml)
[![Issues](https://img.shields.io/github/issues/matraux/filesystem?logo=github&logoColor=white)](https://github.com/matraux/filesystem/issues)
[![Last Commit](https://img.shields.io/github/last-commit/matraux/filesystem?logo=git&logoColor=white)](https://github.com/matraux/filesystem/commits)

<br>

## Introduction
A PHP 8.4+ library that simplifies file and folder operations using an object-oriented API. Supports renaming, moving, streaming, and deletion with optional integration for PSR-7 and Nette\Http\FileUpload.

<br>

## Features
- File and folder manipulation with fluent API
- Relative and absolute path resolution
- File iteration via chunks (stream-style)
- Automatic cleanup of temporary files
- PSR-7 stream support `Psr\Http\Message\StreamInterface`
- Nette FileUpload support `Nette\Http\FileUpload`
- Easy extension via inheritance

<br>

## Installation
```bash
composer require matraux/filesystem
```

<br>

## Requirements
| version | PHP | Note
|----|---|---
| 1.0.0 | 8.3+ | Initial commit
| 1.0.9 | 8.3+ | Cache optimization
| 1.1.1 | 8.4+ | Property accessor
| 1.1.2 | 8.4+ | Quality Assurance
| 1.1.4 | 8.4+ | Docs update
| 1.2.0 | 8.4+ | Exception handling refactor

<br>

## Examples
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

<br>

## Development
See [Development](./docs/Development.md) for debug, test instructions, static analysis, and coding standards.

<br>

## Support
For bug reports and feature requests, please use the [issue tracker](https://github.com/matraux/filesystem/issues).