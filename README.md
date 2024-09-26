# Kronikl

A model history tracking library for Laravel.

## Installation

Install the package via composer:

```shell
composer require dual/kronikl
```

This will include Kronikl in your project. Now, publish the configuration file and the migration file:

```shell
php artisan vendor:publish --provider="Dual\Kronikl\KroniklServiceProvider"
```

Finally, run the migration:

> NOTE: If you are using UUID primary keys, replace instances of `unsignedBigInteger` with `uuid` before migrating.

```shell
php artisan migrate
```

## Configuration

By default, `create`, `update`, `delete` and `restore` operations performed via Eloquent are observed. This means you do not need to call any other function, simply include the models you want to watch in the `models` key in the configuration file (`config/kronikl.php`).

Options are documented in `config/kronikl.php` and are provided with sane defaults. Feel free to modify as needed.

By default, `password` is defined in the `discards` key for security purposes. Please do not remove this unless you know what you're doing.

## Records

Audit trail records are saved in the `kronikl_logs` table and is automatically created upon every successful `created`, `updated` and `deleted` event monitored by an observer. Records are stored in JSON and can be searched via fuzzy search (using `LIKE` direct in the `record` column), or by using Laravel's [`whereJsonContains()`](https://laravel.com/docs/11.x/queries#json-where-clauses) method for more specific results.

### What does it look like?

The actual record is stored as JSON, so it's easy to do a `json_decode()` on the record and call whatever record you want to use. For example:

```php
<?php

// ... other code here ... //

// Get a trail record (define your own conditions here)
$trail = Kronikl::where('user_id', $user->id)->where('action', 'update')->first();

// Decode the JSON
$result = json_decode($trail->record);

// Output the old and new values
echo "Old value: " . $result->name->old . "<br>";
echo "New value: " . $result->name->new;
```

> On update, it only saves the fields that actually changed (and because we're using observers, calling `update()` with the same data won't record a new entry)

It's clean and coherent, you can modify your spiels to look however you want, since we only store the data and not how it's constructed. In JSON, it looks like the following (an example of a `create` action log):

```json
{ 
   "name":{ 
      "old": "John Smith",
      "new": "Jose Rizal"
   },
   "email":{ 
      "old": "john.smith@example.com",
      "new": "jose.rizal@example.com"
   }
}
```

## Discarding Data

### Global Discards

You can discard a field name globally by setting it in `config/kronikl.php`.

```php
<?php

return [
    /**
     * Specify which fields (columns) to discard in the log for data changes.
     *
     * For security purposes, "passsword" and "remember_token" are included below.
     * Since we are already recording event changes with timestamps, we also included
     * created_at, updated_at, deleted_at and banned_at by default.
     */
    "discards" => [
        "password",
        "remember_token",
        "created_at",
        "updated_at",
        "deleted_at",
        "banned_at"
    ]
];
```

### Model-specific Discards

In addition, if you want to discard a field specific to a model, you may add a `public $discarded` declaration in your model:

```php
<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The keys defined in this array is discared by Kronikl.
     *
     * @var array
     */
    public $discarded = [
        'password'
    ];
}
```

**Never** store sensitive data in plaintext. Sane defaults have been provided (see `config/kronikl.php`), adjust as necessary.

## License

This library is published under the [MIT Open Source license](https://github.com/dualph/kronikl/blob/main/LICENSE).