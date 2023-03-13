<?php
/*
 * https://www.grok-interactive.com/blog/import-large-csv-into-mysql-with-php-part-1/
 * */
namespace Snap\Database;

use DB;

class CsvFileImporter
{
    /**
     * Import method used for saving file and importing it using a database query
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $csvImport
     * @return int number of lines imported
     */
    public function import($csvImport)
    {
        // Save file to temp directory
        $movedFile = $this->moveFile($csvImport);

        // Normalize line endings
        $normalizedFile = $this->normalize($movedFile);

        // Import contents of the file into database
        return $this->importFileContents($normalizedFile);
    }

    /**
     * Move File to a temporary storage directory for processing
     * temporary directory must have 0755 permissions in order to be processed
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $csvImport
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private function moveFile($csvImport)
    {
        $destinationDirectory = storage_path('imports/tmp');

        // Check if directory exists make sure it has correct permissions, if not make it
        if (is_dir($destinationDirectory)) {
            chmod($destinationDirectory, 0755);
        } else {
            mkdir($destinationDirectory, 0755, true);
        }

        // Get file's original name
        $originalFileName = $csvImport->getClientOriginalName();

        // Return moved file as File object
        return $csvImport->move($destinationDirectory, $originalFileName);
    }

    /**
     * Convert file line endings to uniform "\r\n" to solve for EOL issues
     * Files that are created on different platforms use different EOL characters
     * This method will convert all line endings to Unix uniform
     *
     * @param string $filePath
     * @return string $file_path
     */
    protected function normalize($filePath)
    {
        //Load the file into a string
        $string = @file_get_contents($filePath);

        if (!$string) {
            return $filePath;
        }

        //Convert all line-endings using regular expression
        $string = preg_replace('~\r\n?~', "\n", $string);

        file_put_contents($filePath, $string);

        return $filePath;
    }

    /**
     * Import CSV file into Database using LOAD DATA LOCAL INFILE function
     *
     * NOTE: PDO settings must have attribute PDO::MYSQL_ATTR_LOCAL_INFILE => true
     *
     * @param $filePath
     * @return mixed Will return number of lines imported by the query
     */
    private function importFileContents($filePath)
    {
        $query = sprintf("LOAD DATA LOCAL INFILE '%s' INTO TABLE file_import_contents 
            LINES TERMINATED BY '\\n'
            FIELDS TERMINATED BY ',' 
            IGNORE 1 LINES (`content`)", addslashes($filePath));

        return DB::connection()->getpdo()->exec($query);
    }
}