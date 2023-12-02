<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Package;
use App\Models\PackageEvent;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(5)->create();

        $users->each(function (User $user): void {
            Package::factory(5)->create([
                'user_id' => $user->id,
            ]);
        });

        $packages = Package::all();

        $packages->each(function (Package $package): void {
            $package->events()->saveMany(
                PackageEvent::factory(5)->create([
                    'package_id' => $package->id,
                ])
            );
        });
    }
}
