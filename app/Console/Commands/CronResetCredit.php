<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CronResetCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:reset-credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to reset normal user\'s credit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("Cron Reset Credit is working fine!");
        $users = User::where('role', config('constants.roles.user'))->get();

        foreach ($users as $user) {
            if ($user->is_premium) {
                $user->credit = config('constants.credit.premium');
            } else {
                $user->credit = config('constants.credit.common');
            }
            $user->update();
        }
    }
}
