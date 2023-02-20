# Honeypot.
Mitigates spam form submissions using the honeypot method.
## Basic Information:
- **Name:** Honeypot.
- **Machine name:** `honeypot`.
- **Package:** Spam control.
- **Core:** ^9.2 || ^10.

## Services:
1. `honeypot`.
  - **Class:** `Drupal\honeypot\HoneypotService`.
  - **Arguments:** `@current_user`, `@module_handler`, `@config.factory`, `@keyvalue.expirable`, `@page_cache_kill_switch`, `@database`, `@logger.factory`, `@datetime.time`, `@string_translation`, `@cache.default`, `@request_stack`.

## Routes:
1. `honeypot.config`.
  - **Path:** `/admin/config/content/honeypot`.
  - **Handler:** `\Drupal\honeypot\Form\HoneypotSettingsForm`.

## Menu Links:
1. `honeypot.config`: Honeypot configuration.

