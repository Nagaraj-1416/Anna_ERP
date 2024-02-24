<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class DatabaseBackup extends Command
{
    protected $storagePath = '/AnnaERP/Production/DB/';
    protected $filePrefix = 'production_anna_db_';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the production DB and upload to Dropbox';

    /**
     * DatabaseBackup constructor.
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
        /** get connection data */
        $connection = config('database.default');
        $host = config('database.connections.' . $connection . '.host');
        $userName = config('database.connections.' . $connection . '.username');
        $password = config('database.connections.' . $connection . '.password');
        $database = config('database.connections.' . $connection . '.database');

        /** set-up file name and route */
        $route = public_path('backup/');
        $file = $this->filePrefix . carbon()->format('Ymd_His') . '.sql.gz';
        $path = $route . $file;

        /** if backup folder is not available, create one */
        if (!file_exists($route)) {
            Storage::makeDirectory('backup');
        }

        /** execute mysql dump command */
        $this->comment("Creating dumb sql file ($file) .. ");
        $command = "mysqldump -h $host -u $userName -p$password $database | gzip > $path";
        exec($command);

        /** upload generated back-up to DropBox */
        $path = strtolower($this->storagePath) . $file;
        $dropBox = new Dropbox(new DropboxApp("", "", env('DROP_BOX_ACCESS_TOKEN')));
        $this->info('Uploading to DropBox...');
        $dropBox->upload(public_path('backup/' . $file), $path);

        /** delete the back-up file after uploaded to DropBox */
        $this->info('Deleting file...' . $file);
        Storage::delete('backup/' . $file);

        /** display this when back-up process done */
        $this->info('Done!');
    }
}
