# Sylius Fortis Plugin

## Dev Notes

If you would like to point Composer to a local copy of the SDK, replace `repositories` in `composer.json` with the following:

```json
{
  "repositories": [
    { "type": "path", "url": "../PHP_GENERIC_LIB_V2", "options": { "symlink": false } }
  ]
}
```

## Running tests

- Unit tests (no env):  
  `composer test`

- Integration (Fortis sandbox):  
  Add to `.env.local`:

```bash
FORTIS_RUN_LIVE=1
FORTIS_DEVELOPER_ID=...
FORTIS_USER_ID=...
FORTIS_USER_API_KEY=...
FORTIS_LOCATION_ID=...
```

optional for token flow:
```bash
FORTIS_TEST_TOKEN=... # use scripts/mint-fortis-token.php
```
