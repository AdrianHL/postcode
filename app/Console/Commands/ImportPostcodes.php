<?php

namespace App\Console\Commands;

use App\Postcode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use SplFileObject;
use Symfony\Component\Finder\SplFileInfo;

class ImportPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:postcodes
                            {--no-download} 
                            {--no-unzip}';

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

        if (!$this->option('no-download'))
        {
            $this->info('Downloading Postcodes Data ... This may take a few seconds.');

            $file = $this->storagePath . 'download.zip';

            //ToDo - The file is always downloaded but it could be possible to download and verify a piece to know if it has been updated since the last time download
            $downloadedFile =  downloadFile(
                $downloadUrl = config('postcodes.download_url'),
                $file
            );

            if (!$downloadedFile) {
                $this->error('It was not possible to download the Postcodes file. Please check the log for more info.');
                return false;
            }
        }

        $targetPath = $this->storagePath . 'data';

        if (!$this->option('no-unzip'))
        {
            $this->info('Unzipping Postcodes ... This may take a few seconds.');

            //ToDo - Unzip only the Data folder (excluding the User Guide and Documents)
            $unzippedFile = unzipFile(
                $file,
                $targetPath
            );

            if (!$unzippedFile) {
                $this->error('It was not possible to unzip the Postcodes zip file. Please check the log for more info.');
                return false;
            }
        }

        $this->info('Importing Postcodes ... This is going to take some minutes to complete.');

        $individualFilesPath = $targetPath . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'multi_csv';
        $files = File::allFiles($individualFilesPath);

        if (empty($files)) {
            $this->error('There were no individual files to process.');
            return false;

            //ToDo - Try to use the global file
        }

        $progressBar = $this->output->createProgressBar(count($files));
        $postcodes = 0;
        foreach ($files as $file)
        {
            $fileData = $this->extractPostcodeFileData($file);

            $filePostcodes = 0;
            if (count($fileData)) {
                $this->info(sprintf(PHP_EOL . 'Processing file %s ...', $file->getFileName()));
                $filePostcodes = $this->processPostcodeData($fileData);
            }

            $postcodes += $filePostcodes;

            $this->info(sprintf(PHP_EOL . PHP_EOL . 'The file %s contained information for %s postcodes.' . PHP_EOL, $file->getFileName(), $filePostcodes));

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info(PHP_EOL . PHP_EOL . 'Downloaded and Imported UK Postcodes. The postcodes data is now ready to be consumed!');

        return true;
    }

    /**
     * Process the Postcode data
     *
     * @param array $data
     * @return int Amount of processed postcodes
     */
    protected function processPostcodeData(array $data) : int
    {
        $postcodes = 0;

        //Transaction ... to speed up writes in SQLite!
        DB::transaction(function () use ($data, &$postcodes)
        {
            $progressBar = $this->output->createProgressBar(count($data));
            foreach ($data as $dataRow) {
                //ToDo - Use the hash to create a new row if the previous one has been updated , if so a soft delete approach will be required to delete the older one later
                Postcode::updateOrCreate([
                    'pcd' => $dataRow['pcd']
                ], $dataRow);
                $postcodes++;
                $progressBar->advance();
            }
            $progressBar->finish();
        });

        return $postcodes;
    }

    /**
     * Extract the Postcode data from a CSV file
     *
     * @param SplFileInfo $file
     * @return array
     */
    protected function extractPostcodeFileData(SplFileInfo $file) : array
    {
        $fileObject = new SplFileObject($file);
        $fileObject->setFlags(SplFileObject::DROP_NEW_LINE);
        $fileObject->setFlags(SplFileObject::SKIP_EMPTY);
        $fileData = [];
        $firstLine = true;

        while (!$fileObject->eof())
        {
            if ($firstLine) {
                $header = $fileObject->fgetcsv();
                $firstLine = false;
                continue;
            }

            $dataRow = $fileObject->fgetcsv();

            if (count($header) != count($dataRow)) {
                \Log::warning(sprintf('There is a data row in the file %s that do not contain the same number of fields than the header and it has been excluded.', $file->getFileName()), ['data' => $dataRow]);
                continue;
            }

            $fileData[] = array_combine($header, $dataRow);
        }

        return $fileData;
    }
}
