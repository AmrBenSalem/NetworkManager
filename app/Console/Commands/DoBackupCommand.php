<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DoBackup;

class DoBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dobackup:backup {networkss}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        dispatch((new DoBackup($this->argument('networkss')))->onQueue('backups'));
    }
}
