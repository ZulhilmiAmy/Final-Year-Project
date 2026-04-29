<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user with email zulhilmisemail@gmail.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'zulhilmisemail@gmail.com';
        $password = 'zulhilmi'; // You can change this

        if (User::where('email', $email)->exists()) {
            $this->warn("User with email {$email} already exists.");
            return;
        }

        User::create([
            'username' => 'zulhilmi',   // 👈 make sure this exists
            'name' => 'Zulhilmi',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("User created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");

    }
}
