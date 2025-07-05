
# Laravel Enum Exporter

[![Tests](https://github.com/levintoo/laravel-enum-exporter/actions/workflows/tests.yml/badge.svg)](https://github.com/levintoo/laravel-enum-exporter/actions/workflows/tests.yml)
[![Latest Unstable Version](http://poser.pugx.org/levintoo/laravel-enum-exporter/v/unstable)](https://packagist.org/packages/levintoo/laravel-enum-exporter) 
[![License](http://poser.pugx.org/levintoo/laravel-enum-exporter/license)](https://packagist.org/packages/levintoo/laravel-enum-exporter) 

Effortlessly sync PHP enums to TypeScript for type-safe frontend integration.
This dev-only Laravel package exports your PHP enums into TypeScript files under `resources/js/enums`, letting you use the same enum definitions on the frontend as your server.

### 📦 Installation

```bash
composer require levintoo/laravel-enum-exporter --dev
````

### 🚀 Usage

Export a single enum:

```bash
php artisan export:enum Role # or app/Enums/Role.php
```

Export all enums in `app/Enums`:

```bash
php artisan export:enum --all
```

🗂 **Output Path**
TypeScript files are generated in:

```
resources/js/enums/{kebab-case-enum}.ts
```

### 📝 Example

Given a PHP enum:

```php
/* app/Enums/UserStatus.php */
enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Pending = 'pending';
}
```

The following TypeScript file will be generated:

```ts
/* resources/js/enums/user-status.ts */
export enum UserStatus {
    Active = 'active',
    Inactive = 'inactive',
    Pending = 'pending',
}
```

### ⚠️ Current Status

> This package is **pre-alpha**: APIs and behavior are actively evolving and may change without notice.

### 🛠 Future Plans

| Feature                                     | Status         |
|---------------------------------------------|----------------|
| Publish to Packagist                        | ⏳ Planned      |
| Support enums with methods/properties  | ⏳ Planned      |
| Add option to overwrite existing files      | ⏳ Planned      |
| Allow custom output paths for TS files      | ⏳ Planned      |
| Support multiple case styles (kebab, Pascal) | ⏳ Planned      |
| Support JS enum workarounds                 | ⏳ Planned      |
