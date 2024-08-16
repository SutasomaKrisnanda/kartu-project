<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Quest;
use App\Models\User;
use App\Models\QuestProgress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Quest::factory(10)->create();

        User::factory()->create([
            'username' => 'username1',
            'email' => 'test1@example.com',
        ]);

        User::factory()->create([
            'username' => 'username2',
            'email' => 'test2@example.com',
        ]);

        // User::all()->each(function ($user) {
        //     $quests = Quest::inRandomOrder()->take(5)->get();
        //     foreach ($quests as $quest) {
        //         QuestProgress::factory()->forQuest($quest)->create([
        //             'user_id' => $user->id,
        //         ]);
        //     }
        // });

        Item::factory(100)->create();
        $this->call([InventorySeeder::class]);
    }
}
