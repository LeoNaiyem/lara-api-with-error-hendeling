🔹 Step 1: Publish Laravel's Handler.php
$php artisan vendor:publish --tag=laravel-exceptions (This creates: app/Exceptions/Handler.php)

$composer dump-autoload (This ensures Laravel recognizes the newly added class)

$php artisan make:controller CustomerController --api --model=Customer (This will give api controller with model name in the parameter)
