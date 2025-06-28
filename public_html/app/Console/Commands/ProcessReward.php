<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ProcessRewards extends Command
{
    protected $signature = 'rewards:process';
    protected $description = 'Assign eligible rewards to users based on their donations';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->assignEligibleRewards();
        }

        $this->info('Rewards have been processed successfully.');
    }
}
