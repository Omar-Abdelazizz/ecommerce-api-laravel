<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Variant;
use App\Models\ItemVariant;
use App\Models\Discount;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        $admin1 = User::create([
            'name' => 'Admin One',
            'username' => 'admin1',
            'email' => 'admin1@test.com',
            'password' => Hash::make('12345678'),
            'type' => 'admin'
        ]);

        $admin2 = User::create([
            'name' => 'Admin Two',
            'username' => 'admin2',
            'email' => 'admin2@test.com',
            'password' => Hash::make('12345678'),
            'type' => 'admin'
        ]);

        $user1 = User::create([
            'name' => 'Ahmed',
            'username' => 'ahmed',
            'email' => 'ahmed@test.com',
            'password' => Hash::make('12345678'),
            'type' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Omar',
            'username' => 'omar',
            'email' => 'omar@test.com',
            'password' => Hash::make('12345678'),
            'type' => 'user'
        ]);

        $user3 = User::create([
            'name' => 'Ali',
            'username' => 'ali',
            'email' => 'ali@test.com',
            'password' => Hash::make('12345678'),
            'type' => 'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        $clothes = Category::create(['name' => 'Clothes']);
        $shoes = Category::create(['name' => 'Shoes']);
        $electronics = Category::create(['name' => 'Electronics']);

        /*
        |--------------------------------------------------------------------------
        | Items
        |--------------------------------------------------------------------------
        */
        $tshirt = Item::create([
            'name' => 'T-Shirt',
            'price' => 200,
            'category_id' => $clothes->id
        ]);

        $hoodie = Item::create([
            'name' => 'Hoodie',
            'price' => 400,
            'category_id' => $clothes->id
        ]);

        $sneaker = Item::create([
            'name' => 'Sneaker',
            'price' => 800,
            'category_id' => $shoes->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | Variants (attributes)
        |--------------------------------------------------------------------------
        */
        $sizeS = Variant::create(['type' => 'size', 'value' => 'S']);
        $sizeM = Variant::create(['type' => 'size', 'value' => 'M']);
        $sizeL = Variant::create(['type' => 'size', 'value' => 'L']);

        $colorRed = Variant::create(['type' => 'color', 'value' => 'Red']);
        $colorBlack = Variant::create(['type' => 'color', 'value' => 'Black']);

        /*
        |--------------------------------------------------------------------------
        | Item Variants (actual sellable SKUs)
        |--------------------------------------------------------------------------
        */

        // T-Shirt Variants
        $ts1 = ItemVariant::create([
            'item_id' => $tshirt->id,
            'sku' => 'TS-RED-M',
            'stock' => 10,
            'price' => 220
        ]);

        $ts2 = ItemVariant::create([
            'item_id' => $tshirt->id,
            'sku' => 'TS-BLACK-L',
            'stock' => 8,
            'price' => 230
        ]);

        // Hoodie Variants
        $hd1 = ItemVariant::create([
            'item_id' => $hoodie->id,
            'sku' => 'HD-BLACK-M',
            'stock' => 5,
            'price' => 450
        ]);

        // Sneaker Variants
        $sn1 = ItemVariant::create([
            'item_id' => $sneaker->id,
            'sku' => 'SN-RED-42',
            'stock' => 6,
            'price' => 850
        ]);


        /*
        |--------------------------------------------------------------------------
        | Pivot: item_variant_values
        |--------------------------------------------------------------------------
        */
        $ts1->values()->attach([$sizeM->id, $colorRed->id]);
        $ts2->values()->attach([$sizeL->id, $colorBlack->id]);
        $hd1->values()->attach([$sizeM->id, $colorBlack->id]);
        $sn1->values()->attach([$colorRed->id]);


        $item = Item::first(); // T-Shirt مثلاً
        $category = Category::first(); // Clothes

        /*
        |--------------------------------------------------------------------------
        | Item Discount (10%)
        |--------------------------------------------------------------------------
        */
        Discount::create([
            'type' => 'item',
            'discounted_id' => $item->id,
            'value_type' => 'percentage',
            'value' => 10,
            'min_quantity' => 1,
            'min_price' => null,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(10),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Category Discount (50 EGP off)
        |--------------------------------------------------------------------------
        */
        Discount::create([
            'type' => 'category',
            'discounted_id' => $category->id,
            'value_type' => 'fixed',
            'value' => 50,
            'min_quantity' => null,
            'min_price' => 300,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(5),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Big Discount (testing edge case)
        |--------------------------------------------------------------------------
        */
        Discount::create([
            'type' => 'item',
            'discounted_id' => $item->id,
            'value_type' => 'percentage',
            'value' => 50,
            'min_quantity' => 2,
            'min_price' => null,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(2),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */
        $permissions = [
            'manage categories',
            'manage items',
            'manage variants',
            'manage discounts',
            'view orders',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum' // 👈 مهم جدا
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum'
        ]);

        $user = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'sanctum'
        ]);

        $admin->syncPermissions($permissions);
        $admin1->assignRole('admin');
        $admin2->assignRole('admin');

        $user1->assignRole('user');
        $user2->assignRole('user');
        $user3->assignRole('user');
    }
}