## SANCTUM API CRUD
Sanctum excels in providing simple and flexible token-based authentication for SPAs.

Use-
step1 : Install the package Run CMD >> composer require laravel/sanctum
step2 : Publish  the package Run CMD >> php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider
step3 : Migrate the DB Run CMD >> php artisan migrate
step4 : Uncomment the EnsureFrontendRequestsAreStateful Middleware from app/Http/Kernel.php


### Used Concept or Covered Topic
- Public APIs General Routes like(Register, Login, Forgot Password, Reset password)
- Public APIs CRUD Routes like(View Item, Search Item, Filter by id)
- Protected APIs General Routes like(Logout, Change Password)
- Protected APIs CRUD Routes like(Add Item, Delete Item, Update Item)
- Used laravel Library like - Validation, Mail , Carbon, Hash, Str & Message etc


### Project Configuration
- Take Clone 
- Set .env Values
- Run CMD >> composer install
- Run CMD >> php artisan serve
- Open URL http://localhost:8000/my-api/


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
