<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Passenger;
use App\Models\Driver;

class FixProfileIds extends Command
{
    protected $signature = 'fix:profile-ids';
    protected $description = 'Fix mismatched ids in passengers and drivers tables to match users.id';

    public function handle()
    {
        $this->info('Fixing passenger profile IDs...');
        $passengers = Passenger::all();
        foreach ($passengers as $passenger) {
            $user = User::where('email', $passenger->email)->first();
            if ($user && $passenger->id !== $user->id) {
                $this->info("Updating passenger id from {$passenger->id} to {$user->id} for email {$passenger->email}");
                $passenger->id = $user->id;
                $passenger->save();
            }
        }

        $this->info('Fixing driver profile IDs...');
        $drivers = Driver::all();
        foreach ($drivers as $driver) {
            $user = User::where('email', $driver->email)->first();
            if ($user && $driver->id !== $user->id) {
                $this->info("Updating driver id from {$driver->id} to {$user->id} for email {$driver->email}");
                $driver->id = $user->id;
                $driver->save();
            }
        }

        $this->info('Profile ID fix complete.');
    }
} 