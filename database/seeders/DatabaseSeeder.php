<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $uuid = '8a7c6630-6a70-4d25-9df5-15369a450c60';
        \App\Models\Message::factory()->create([
            'role' => 'user',
            'content' => 'What is Queue?',
            'chat_id' => $uuid,
        ]);

        \App\Models\Message::factory()->create([
            'role' => 'bot',
            'content' => 'Laravel queue is a feature of the Laravel framework, which allows you to defer time-consuming or resource-intensive tasks for background processing. It provides a way to handle tasks asynchronously and improve the performance of your application by offloading work to queues.',
            'chat_id' => $uuid,
        ]);
    }
}
