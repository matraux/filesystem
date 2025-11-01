**[Back](../README.md)**


# Development
This package includes several development tools configured via Composer scripts.

<br>

## Composer scripts
```bash
composer codefixer   # Run PHP Code Style Fixer (phpcbf)
composer codesniffer # Run PHP CodeSniffer (phpcs)
composer stan        # Run static analysis (PHPStan)
composer test        # Run unit tests (Tester)
composer finalize    # Run PHPStan and tests
```

<br>

## Tracy debugging
Local IDE integration for Nette Tracy is supported via optional config files.
- `editor.tracy.neon.dist` - template config for editor integrations
- `editor.tracy.neon` - optional local override (copied from `.dist`)

These files can be used to configure custom Tracy editor mapping.

<br>

## PHPStan Static analysis
The project uses PHPStan for static analysis.

- `phpstan.neon` - primary config used by the command line
- `editor.phpstan.neon.dist`- template config for editor integrations
- `editor.phpstan.neon` - optional local override (copied from `.dist`)

To run PHPStan:
```bash
composer stan
```

<br>

## Tests
To run all tests:
```bash
composer test
```