<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\Models\Zip;

class ZipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::unprepared(file_get_contents(__DIR__ . '/imports/zips.sql'));
        /*$csv = Reader::createFromPath(__DIR__ . '/imports/zip_codes_geo.csv', 'r');

        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();


        foreach ($records as $record => $row)
        {
            $zip = new Zip(
                [
                    'zipcode' => $row['zipcode'],
                    'geo_point' => new Point($row['lat'], $row['long'])
                ]
            );

            $zip->save();
        }*/
    }
}
