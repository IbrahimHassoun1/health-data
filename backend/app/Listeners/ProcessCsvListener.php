<?php

namespace App\Listeners;

use \Carbon\Carbon;
use \App\Models\Data;
use App\Events\CsvUploaded;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;


class ProcessCsvListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CsvUploaded $event): void
    {
        $fullPath = Storage::path($event->filePath);

        // Open the CSV file
        if (($handle = fopen($fullPath, 'r')) !== false) {
            // Skip the header row directly by reading it (we don't process the header)
            fgetcsv($handle);

            // Prepare an array to store the rows to be inserted
            $dataToInsert = [];

            // Read and process each row from the file
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Make sure we have all the expected values in the row (5 columns)
                if (count($row) == 5) {
                    // Try to parse the date, assuming it's in 'Y-m-d' format
                    try {
                        $date = Carbon::createFromFormat('Y-m-d', $row[1])->format('Y-m-d H:i:s');

                        // Add the row to the array of data to be inserted
                        $dataToInsert[] = [
                            'user_id' => $row[0],       // Column 0: user_id
                            'date' => $date,            // Column 1: date
                            'steps' => $row[2],         // Column 2: steps
                            'distance_km' => $row[3],   // Column 3: distance_km
                            'active_minutes' => $row[4],// Column 4: active_minutes
                        ];
                    } catch (\Exception $e) {
                        // Log the error and continue if the date format is invalid
                        Log::error('Invalid date format in row: ' . implode(',', $row) . ' Error: ' . $e->getMessage());
                        continue;
                    }
                } else {
                    // Log an error if the row doesn't have the expected number of columns (5)
                    Log::error('Invalid row format: ' . implode(',', $row));
                }

                // Insert data in batches (optional: set a batch size)
                if (count($dataToInsert) >= 1000) {
                    // Insert the accumulated data to the database in a batch
                    Data::insert($dataToInsert);
                    // Reset the array for the next batch of rows
                    $dataToInsert = [];
                }
            }

            // After the loop, insert any remaining data
            if (!empty($dataToInsert)) {
                Data::insert($dataToInsert);
            }

            // Close the file after processing
            fclose($handle);
        }
    }

}
