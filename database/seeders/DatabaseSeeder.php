<?php
namespace Database\Seeders;

// namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\Models\Tax;
use App\Models\ProductGroup;
use App\Models\ProductCategory;
use App\Models\Coin;
use App\Models\CurrencyValue;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\PaymentForm;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([UsersTableSeeder::class]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas
        // DB::table('purchase_details')->truncate();
        // DB::table('sale_details')->truncate();
        // DB::table('purchases')->truncate();
        // DB::table('sales')->truncate();
        // DB::table('payment_clients')->truncate();
        // DB::table('payment_suppliers')->truncate();
        // DB::table('products')->truncate();
        DB::table('clients')->truncate();
        DB::table('suppliers')->truncate();
        // DB::table('sale_details')->truncate();
        // DB::table('sales')->truncate();
        // DB::table('purchase_details')->truncate();
        // DB::table('purchases')->truncate();
        // DB::table('product_categories')->truncate();
        // DB::table('product_groups')->truncate();
        // DB::table('payment_forms')->truncate();
        // DB::table('taxes')->truncate();
        // DB::table('currency_values')->truncate();
        // DB::table('coins')->truncate();
        // DB::table('users')->truncate();
        // DB::table('roles')->truncate();
        // DB::table('permissions')->truncate();
        // DB::table('role_has_permissions')->truncate();
        // DB::table('model_has_roles')->truncate();
        // DB::table('model_has_permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;'); // Reactivamos la revisión de claves foráneas

        // $this->call(PermissionSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(CoinSeeder::class);
        // Coin::factory(2)->create();    hay que crear los datos bien. Creo las moneda id 1 bsd y id 2 $
        // CurrencyValue::factory(2)->create(); Crea dos currency coin 1 base 2 valores 4,5 4,8 y coin 2 base 1 va;ore ,222 ,208

        // PaymentForm::factory(3)->create();

        // Tax::factory(1)->create();
        // ProductGroup::factory(13)->create();
        // ProductCategory::factory(35)->create();
        Supplier::factory(15)->create();
         Client::factory(25)->create();
        // Product::factory(225)->create();
    }
}



