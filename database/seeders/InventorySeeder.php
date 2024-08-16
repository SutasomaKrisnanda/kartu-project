<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $items = Item::all();

        foreach ($users as $user) {
            foreach ($items as $item) {
                $user->items()->attach($item->id, ['quantity' => rand(1, 5)]);
            }
        }
    }
}
