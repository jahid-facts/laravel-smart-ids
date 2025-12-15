# Laravel ID Generator

A **100% safe, atomic, collision-free ID generator for Laravel**.

This package generates **human-readable sequential IDs** such as:

* Invoice numbers
* Booking IDs
* Order numbers
* Membership numbers

Designed to work safely under **high concurrency** without race conditions.

---

## Author

**Jahid**

---

## Why This Package Exists

Common approaches like `MAX(id) + 1` or `orderBy()->first()` are **not safe** under concurrent requests and will eventually generate **duplicate IDs**.

This package solves that problem by:

* Using **atomic database increments**
* Ensuring **database-level consistency**
* Supporting **date-based sequences**

---

## Key Features

* ✅ Atomic & concurrency-safe
* ✅ No race conditions
* ✅ Works with existing data
* ✅ Daily sequence reset
* ✅ Supports multiple models & columns
* ✅ Simple helper function
* ✅ Laravel 10 / 11 / 12 compatible
* ✅ MySQL / MariaDB compatible

---

## Installation

### Step 1: Register Local Package

Edit your **root `composer.json`** and add:

```json
"repositories": [
  {
    "type": "path",
    "url": "packages/id-generator/laravel"
  }
]
```

---

### Step 2: Require the Package

Add the package to `require`:

```json
"require": {
  "id-generator/laravel": "*"
}
```

Then run:

```bash
composer update
composer dump-autoload
```

---

## Database Setup

Run the migration:

```bash
php artisan migrate
```

This creates the `id_sequences` table which safely stores sequence counters.

---

## Usage

### Helper Function

```php
idgen();
```

---

### Generate Invoice Number

```php
use App\Models\Payment;

static::creating(function ($payment) {
    if (!$payment->invoice_number) {
        $payment->invoice_number = idgen()->daily(
            Payment::class,
            'INV',
            'invoice_number'
        );
    }
});
```

**Output example:**

```
INV-15122025-00001
```

---

### Generate Booking ID (Based on Booking Date)

```php
use App\Models\Booking;

static::creating(function ($booking) {
    if (!$booking->booking_id) {
        $booking->booking_id = idgen()->daily(
            Booking::class,
            'BK',
            'booking_id',
            $booking->booking_date
        );
    }
});
```

**Output example:**

```
BK-20122025-00002
```

---

## ID Format

```
PREFIX-DDMMYYYY-XXXXX
```

| Part     | Meaning                 |
| -------- | ----------------------- |
| PREFIX   | ID type (INV, BK, etc.) |
| DDMMYYYY | Date                    |
| XXXXX    | Zero-padded sequence    |

---

## Existing Data (Important)

If your database already contains IDs, you **must sync the sequence counters once**.

Without syncing, new IDs may start again from `00001` and cause **duplicate key errors**.

After syncing, the generator will continue from the **highest existing value**.

---

## Database Safety

Always keep **UNIQUE indexes** on generated ID columns:

```sql
ALTER TABLE payments ADD UNIQUE (invoice_number);
ALTER TABLE bookings ADD UNIQUE (booking_id);
```

This guarantees **database-level protection**.

---

## Concurrency Guarantee

This package uses MySQL atomic increments:

```sql
LAST_INSERT_ID(counter + 1)
```

This guarantees:

* One request = one unique ID
* Safe across multiple PHP workers
* Safe across multiple servers

---

## Supported Versions

| Component | Version         |
| --------- | --------------- |
| PHP       | 8.1+            |
| Laravel   | 10 / 11 / 12    |
| Database  | MySQL / MariaDB |

---

## Best Practices

* Always pass the correct date (e.g. `booking_date`)
* Keep unique indexes
* Sync sequences once for old data
* Do not manually edit sequence counters

---

## License

MIT License

---

## Credits

Developed by **Jahid**

Happy coding 🚀
