<?php

namespace Database\Seeders;

use App\Models\CardEffect;
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
        $items = json_decode(file_get_contents(public_path('item_data.json')), true);
        $index = 1;
        foreach ($items as $item) {
            Item::create([
                'name' => $item['name'],
                'token' => $item['token'],
                'element' => $item['element'],
                'description' => $item['description'],
                'type' => $item['type'],
                'rarity' => $item['rarity'],
                'image' => $item['image']
            ]);
            CardEffect::create([
                'item_id' => $index++,
                'type' => $item['effect_type'],
                'value' => $item['effect_value'],
                'cooldown' => $item['cooldown']
            ]);
        }
    }
}
