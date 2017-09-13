<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:postcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and Import UK Postcodes';

    /**
     * @var string
     */
    protected $storagePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->storagePath = storage_path('postcodes-import') . DIRECTORY_SEPARATOR;

        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0775, true);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $this->info('Starting Download and Import UK Postcodes...');

        $this->info('Downloading Postcodes...');

        $file = $this->storagePath . 'download.zip';

        //ToDo - The file is always downloaded but it could be possible to download and verify a piece to know if it has been updated since the last time download
        //ToDo - Maybe an option in the command to do not download the fle and use existing one?
        $downloadedFile = downloadFile(
            $downloadUrl = config('postcodes.download_url'),
            $file
        );

        if (!$downloadedFile) {
            $this->error('It was not possible to download the Postcodes file. Please check the log for more info.');
            return false;
        }

        $this->info('Unzipping Postcodes...');

        //ToDo - Unzip only the Data folder (excluding the User Guide and Documents)
        //ToDo - The Extract Path should include a checksum/hash to make sure that the content is correct
        $unzippedFile = unzipFile(
            $file,
            $targetPath = $file = $this->storagePath . 'data'
        );

        if (!$unzippedFile) {
            $this->error('It was not possible to unzip the Postcodes zip file. Please check the log for more info.');
            return false;
        }

        $this->info('Importing Postcodes...');

        //ToDo - Import data from the individual csv files to the database

        //ToDo - Create Model(s) and Data Migration(s). Potential hash to avoid checking non updated data

        //ToDo - Show a progress bar and advance per file imported

            //ToDo - Use a create or update based on a primary key (and maybe a hash column) - storage/postcodes-import/data/User Guide/ONSPD User Guide May 2017.pdf, page 33


        $this->info('Downloaded and Imported UK Postcodes. The postcodes data is now ready to be consumed!');
    }

}
