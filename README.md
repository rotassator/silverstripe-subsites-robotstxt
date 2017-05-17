# SilverStripe Subsites Robots.txt Module

Generate custom `robots.txt` for each subsite.

This module aims to prevent indexing of subsite-specific folder assets that belong to _other_ subsites. It creates a `robots.txt` file with `Disallow` rules for folders belonging to other subsites (ie. not folders that are common or for the current subsite).

## Installation

```
composer require rotassator/silverstripe-subsites-robotstxt
```

### Live mode

Set the site to *live* mode to see subsite-specific `robots.txt`. On `dev` or `test` environments, robots are disallowed for all files.

See [Environment management](https://docs.silverstripe.org/en/3/getting_started/environment_management/) documentation for more details.


## Example `robots.txt` for live site

For `example1.com` subsite:
```
# robots.txt for Example 1

User-agent: *
Disallow: assets/example2/
Disallow: assets/example2-documents/
```

For `example2.com` subsite:
```
# robots.txt for Example 2

User-agent: *
Disallow: assets/example1/
```

### Example for non-live site

```
# robots.txt for Example 1

User-agent: *
Disallow: /
```

