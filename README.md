# Laravel Form Sync

**Sync Laravel validation rules & messages with frontend (React/Vue) automatically.**

`laravel-form-sync` allows you to **export Laravel FormRequest rules** to JSON schema and **use them directly in your frontend**, keeping your validations consistent and saving tons of development time.

---

## Features

* Automatically sync Laravel `FormRequest` rules to frontend JSON schema.
* Supports **React** (`useFormSync` hook) and **Vue** (`useFormSync` composable).
* Preserves Laravel validation messages.
* Supports **required, min, max, email, confirmed, unique**, and custom rules.
* Easy `--all` command to sync all FormRequests in your app.
* Publishable stubs so you can customize frontend helpers.

---

## Installation

### 1. Install via Composer (Local / Dev)

```bash
composer require matrixbrains/laravel-form-sync:@dev
```

> If using locally, add repository path to your `composer.json`:

```json
"repositories": [
    {
        "type": "path",
        "url": "../path-to-laravel-form-sync"
    }
]
```

### 2. Publish Frontend Hooks

**React:**

```bash
php artisan vendor:publish --tag=react
```

**Vue:**

```bash
php artisan vendor:publish --tag=vue
```

---

## Usage

### 1. Create a FormRequest

```bash
php artisan make:request UserRequest
```

**Example:**

```php
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ];
}

public function messages()
{
    return [
        'name.required' => 'Name is required.',
        'email.email' => 'Email must be valid.',
    ];
}
```

### 2. Generate JSON Schema

```bash
php artisan form:sync UserRequest
```

> This will create: `resources/forms/user_request.json`

To sync all FormRequests:

```bash
php artisan form:sync --all
```

### 3. Use in React

```tsx
import { useFormSync } from "@/hooks/useFormSync";
import userSchema from "../forms/user_request.json";

export default function RegisterForm() {
  const { register, handleSubmit, errors } = useFormSync(userSchema);

  const onSubmit = (data) => {
    console.log("Submitted:", data);
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input {...register("name")} placeholder="Name" />
      {errors.name && <span>{errors.name}</span>}

      <input {...register("email")} placeholder="Email" />
      {errors.email && <span>{errors.email}</span>}

      <input type="password" {...register("password")} placeholder="Password" />
      {errors.password && <span>{errors.password}</span>}

      <button type="submit">Register</button>
    </form>
  );
}
```

### 4. Use in Vue

```ts
import { useFormSync } from "@/composables/useFormSync";
import userSchema from "../forms/user_request.json";

export default {
  setup() {
    const { register, handleSubmit, state } = useFormSync(userSchema);

    const onSubmit = (data) => console.log("Submitted:", data);

    return { register, handleSubmit, state, onSubmit };
  }
}
```

---

## Commands

| Command                                  | Description                                  |
| ---------------------------------------- | -------------------------------------------- |
| `php artisan form:sync {FormRequest}`    | Sync a single FormRequest                    |
| `php artisan form:sync --all`            | Sync all FormRequests in `App\Http\Requests` |
| `php artisan vendor:publish --tag=react` | Publish React hook                           |
| `php artisan vendor:publish --tag=vue`   | Publish Vue composable                       |

---

## Example JSON Schema

```json
{
  "fields": {
    "name": { "required": true, "string": true, "max": 255 },
    "email": { "required": true, "email": true, "unique": "users,email" },
    "password": { "required": true, "min": 8, "confirmed": true }
  },
  "messages": {
    "name.required": "Name is required.",
    "email.email": "Email must be valid."
  }
}
```

---

## Contribution

Contributions are welcome!

* Report issues or request features on GitHub.
* Fork, create a branch, make your changes, and submit a PR.

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
