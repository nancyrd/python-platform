<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(StageLevelSeeder::class);
        $this->call(Stage2VariablesSeeder::class);
           $this->call(Stages3Seeder::class);  
           $this->call(Stage4DecisionsSeeder::class);  
           $this->call(Stage5LoopsSeeder::class); 
           $this->call(Stage6FunctionsSeeder::class);   
           $this->call(Stage7CollectionsSeeder::class);  
           $this->call(Stage8MiniProjectsSeeder::class); 
       $this->call(AdminSeeder::class); }
    
}
