<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'user:create';
    protected $description = 'Create a new user';

    public function handle()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->password = Hash::make('password123');
        $user->is_active = 1;
        $user->role = 'admin';
        $user->save();

        $this->info('User created successfully!');
        $this->info('Email: test@example.com');
        $this->info('Password: password123');
    }
}