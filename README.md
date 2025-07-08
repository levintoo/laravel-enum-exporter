
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

**Export a single enum (TypeScript):**

```bash
php artisan export:enum Role
# or
php artisan export:enum app/Enums/Role.php
```

**Export all enums in `app/Enums` (TypeScript):**

```bash
php artisan export:enum --all
```

**Export enums as JavaScript workarounds (instead of TypeScript):**

```bash
php artisan export:enum Role --js
# or
php artisan export:enum --all --js
```

🗂 **Output Path**
TypeScript files are generated in:

* **TypeScript:**
  `resources/js/enums/{kebab-case-enum}.ts`
* **JavaScript:**
  `resources/js/enums/{kebab-case-enum}.js`

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
or The following Javascript file will be generated:

```js
/* resources/js/enums/user-status.js */
const UserStatus = {
    Active: { name: 'Active', value: 'active' },
    Inactive: { name: 'Inactive', value: 'inactive' },
    Pending: { name: 'Pending', value: 'pending' },

    cases() {
        return Object.values(this).filter(e => e?.value !== undefined);
    },

    from(slug) {
        return this.cases().find(
            e => e.name.toLowerCase() === slug
        ) ?? null;
    },

    get(value) {
        return this.cases().find(
            e => e.value === value
        ) ?? null;
    }
};

export default UserStatus;
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
