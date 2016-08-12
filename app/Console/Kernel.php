<?php namespace Biffy\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
        'Biffy\Console\Commands\Script\CurrencyConverterScript',
        'Biffy\Console\Commands\Script\DeviceSortOrder',
        'Biffy\Console\Commands\Script\FillUrlAliasTableScript',
        'Biffy\Console\Commands\Script\FrenchPricingScript',
        'Biffy\Console\Commands\Script\ImportKeywords',
        'Biffy\Console\Commands\Script\NewSmartPhoneRepairs',
        'Biffy\Console\Commands\Script\RootParentScript',
        'Biffy\Console\Commands\Script\UpdateSmartPhoneRepairs',
        'Biffy\Console\Commands\GoogleImportAll',
        'Biffy\Console\Commands\GoogleImportGroups',
        'Biffy\Console\Commands\GoogleImportGroupMembers',
        'Biffy\Console\Commands\GoogleImportUsers',
        'Biffy\Console\Commands\MakeEntity',
        'Biffy\Console\Commands\MakeRepositoryInterface',
        'Biffy\Console\Commands\MakeRepositoryEloquent',
        'Biffy\Console\Commands\MakeJs',
        'Biffy\Console\Commands\TestSetupCommand',
        'Biffy\Console\Commands\StartWebsocket'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
	}

}
