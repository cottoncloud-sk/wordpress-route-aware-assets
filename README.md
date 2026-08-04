# Route-Aware WordPress Assets: A Minimal Conditional Enqueue/Dequeue Pattern

This repository contains a small, anonymized WordPress theme pattern and an executable contract test. It demonstrates two separate decisions:

- enqueue a route-specific stylesheet only when its route owns the UI;
- dequeue selected core block styles only when a custom template owns the complete rendered markup.

The example is illustrative code, not CottonCloud production source.

## Files

- `route-aware-assets-example.php` — conditional enqueue/dequeue pattern with explicit admin, account and checkout boundaries.
- `route-aware-assets-example-test.php` — dependency-free PHP contract covering owned routes, a normal content route, a protected transactional route and an admin request.

## Run the contract

```bash
php -l route-aware-assets-example.php
php -l route-aware-assets-example-test.php
php route-aware-assets-example-test.php
```

Expected final line:

```text
route-aware asset contract: PASS
```

## Integration boundaries

Review every handle against the final HTML your theme renders. Do not dequeue block styles on routes that still contain block markup. Preserve admin, account, checkout and other personalized or transactional routes unless their full rendering contract is explicitly tested.

File modification time is used only as a local cache-busting example. Replace it with the versioning strategy appropriate for your release process.

This pattern reflects the route ownership checks used in [custom WordPress development](https://cottoncloud.sk/wordpress-vyvoj/).

## Claim limits

The contract proves only which mocked routes enqueue or dequeue the named handles. It does not prove a universal speed gain, production byte savings, compatibility with every plugin, ranking improvement or business impact.

## Disclosure

Prepared by CottonCloud from a manually verified, anonymized implementation pattern. AI assisted with documentation drafting; the PHP sample and contract were reviewed and executed before publication.
