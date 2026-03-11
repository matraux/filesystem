**[Back](../README.md)**


# Development
This package includes several development tools configured via Composer scripts.

<br>

## Composer scripts
```bash
composer codefixer   # Run PHP Code Style Fixer (phpcbf)
composer codesniffer # Run PHP CodeSniffer (phpcs)
composer phpstan     # Run static analysis (PHPStan)
composer tests:build # Build unit tests
composer tests:build # Run unit tests
```

<br>

## PHPStan Static analysis
The project uses PHPStan for static analysis.

- `phpstan.core.neon` - primary config used by the command line
- `phpstan.neon.dist`- template config for editor integrations
- `phpstan.neon` - optional local override (copied from `.dist`)

To run PHPStan:
```bash
composer phpstan
```

<br>

## Tests
To run all tests:
```bash
composer tests:build
composer tests:run
```