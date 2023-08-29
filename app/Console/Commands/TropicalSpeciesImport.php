<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use App\Models\SpeciesTropical;

class TropicalSpeciesImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TropicalSpeciesImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import tropical species from a CSV file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $contents = Storage::disk('local')->get('tropical_species.csv');


        $lines = explode("\n", $contents);

        $data = array_map('str_getcsv', $lines);

        $header = array_shift($data);


    foreach ($data as $row) {
        // Skip empty lines
        if (count($row) < count($header)) continue;

        $tropical_species = array_combine($header, $row);

        // Check 'flowers' field
        if (!empty($tropical_species['flowering_season'])) {
            $tropical_species['flowers'] = 1;
        } else if (!isset($tropical_species['flowers']) || !in_array($tropical_species['flowers'], ['0', '1'])) {
            $tropical_species['flowers'] = 0; // or set to a default value that makes sense for your data
        }

        // Check 'fruits' field
        if (!isset($tropical_species['fruits']) || !in_array($tropical_species['fruits'], ['0', '1'])) {
            $tropical_species['fruits'] = 0; // or set to a default value that makes sense for your data
        }

        // Ignore 'growth_rate' field
        unset($tropical_species['growth_rate']);

        // Convert string fields to arrays or empty arrays if not set or empty
        foreach (['scientific_name', 'other_name', 'origin', 'attracts', 'sun_exposure', 'propagation','soil','pest_susceptibility'] as $field) {
            if (isset($tropical_species[$field]) && is_string($tropical_species[$field]) && !empty($tropical_species[$field])) {
                $string = str_replace([';', 'to', 'and'], ',', $tropical_species[$field]); // Replacing different delimiters with comma
                $string = preg_replace('/\s*,\s*/', ',', $string); // Removing spaces around commas
                $tropical_species[$field] = array_filter(explode(',', $string));
            } else {
                $tropical_species[$field] = [];
            }
        }

        // Convert 'hardiness' field to JSON object with 'min' and 'max' or null if not set or empty
        if (isset($tropical_species['hardiness']) && is_string($tropical_species['hardiness']) && !empty($tropical_species['hardiness'])) {
            $hardiness = explode(',', $tropical_species['hardiness']);
            $tropical_species['hardiness'] = [
                'min' => isset($hardiness[0]) ? trim($hardiness[0]) : null,
                'max' => isset($hardiness[1]) ? trim($hardiness[1]) : null,
            ];
        } else {
            $tropical_species['hardiness'] = null;
        }

        // Check 'poisonous' field
        if (isset($tropical_species['poisonous']) && strtoupper($tropical_species['poisonous']) === 'T') {
            $tropical_species['poisonous'] = 1;
        } else {
            $tropical_species['poisonous'] = null;
        }

        // Check 'edible' field
        if (isset($tropical_species['edible']) && strtoupper($tropical_species['edible']) === 'T') {
            $tropical_species['edible'] = 1;
        } else {
            $tropical_species['edible'] = null;
        }

        SpeciesTropical::create($tropical_species);
    }


        return 'Done';


    }
}
