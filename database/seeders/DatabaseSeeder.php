<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\CardEffect;
use App\Models\User;
use App\Models\Quest;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Quest::factory(10)->create();
        $this->call([InventorySeeder::class]);

        User::factory()->create([
            'username' => 'username1',
            'email' => 'test1@example.com',
        ]);

        User::factory()->create([
            'username' => 'username2',
            'email' => 'test2@example.com',
        ]);

        // $items = Item::factory(100)->create();
        // foreach ($items as $item) {
        //     if ($item->type === 'card') {
        //         CardEffect::factory()->create([
        //             'item_id' => $item->id,
        //         ]);
        //     }
        // }


    }
}
