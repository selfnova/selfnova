<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class ClearOldNotificationsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'notifications:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete old notifications';

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
		$count = Notification::where('status', 1)
						->whereDate('updated_at', '<', now()->subHours(24))
						->delete();
	}
}
