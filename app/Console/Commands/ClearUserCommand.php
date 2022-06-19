<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearUserCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'users:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete unverified users';

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
	 * @return int
	 */
	public function handle()
	{
		$count = User::whereNull('email_verified_at')
						->whereDate('created_at', '<', now()->subDays(10))
						->forceDelete();
		if ($count)
			\Log::info('Deleted unverified users: ' . $count);
	}
}
