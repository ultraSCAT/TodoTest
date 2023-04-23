<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'user_id' => 2,
            'title' => 'Buy groceries',
            'description' => 'Buy milk, bread, and eggs',
            'completed' => false,
        ]);

        Task::create([
            'user_id' => 1,
            'title' => 'Do laundry',
            'description' => 'Wash and fold clothes',
            'completed' => false,
        ]);
    }
}
