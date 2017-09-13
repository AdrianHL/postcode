<?php

/**
 * Download a source file to a target location
 *
 * @param $sourceUrl
 * @param $targetFile
 * @return bool
 */
function downloadFile($sourceUrl, $targetFile) : bool
{
    try {
        $resource = fopen($targetFile, 'w');
        $client = new \GuzzleHttp\Client();
        $client->request('GET', $sourceUrl, ['sink' => $resource]);
    } catch (\Exception $ex) {
        $errorMessage = sprintf("There was an error while downloading the file from '%s' and saving it to '%s': %s.", $sourceUrl, $targetFile, $ex->getMessage());
        \Log::error($errorMessage);
        return false;
    }

    return true;
}

/**
 * Unzip a zip file to the target path
 *
 * @param $zipFile
 * @param $targetPath
 * @return bool
 */
function unzipFile($zipFile, $targetPath) : bool
{
    try {
        $zip = new ZipArchive;
        if (($res = $zip->open($zipFile)) === TRUE)
        {
            $zip->extractTo($targetPath);
            $zip->close();
        } else {
            switch($res) {
                case ZipArchive::ER_EXISTS:
                    $errorCode = "File already exists";
                    break;

                case ZipArchive::ER_INCONS:
                    $errorCode = "Zip archive inconsistent";
                    break;

                case ZipArchive::ER_MEMORY:
                    $errorCode = "Malloc failure";
                    break;

                case ZipArchive::ER_NOENT:
                    $errorCode = "No such file";
                    break;

                case ZipArchive::ER_NOZIP:
                    $errorCode = "Not a zip archive";
                    break;

                case ZipArchive::ER_OPEN:
                    $errorCode = "Can't open file";
                    break;

                case ZipArchive::ER_READ:
                    $errorCode = "Read error";
                    break;

                case ZipArchive::ER_SEEK:
                    $errorCode = "Seek error";
                    break;

                default:
                    $errorCode = "Unknown (Code $res)";
                    break;
            }

            throw new Exception(sprintf('Zip Archive open error - %s', $errorCode));
        }
    } catch (\Exception $ex) {
        $errorMessage = sprintf("There was an error while extracting the file '%s' to '%s': %s.", $zipFile, $targetPath, $ex->getMessage());
        \Log::error($errorMessage);
        return false;
    }

    return true;
}