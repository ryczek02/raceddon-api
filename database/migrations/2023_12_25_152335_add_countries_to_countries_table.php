<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private string $_dataFile;

    public function __construct()
    {
        $this->_dataFile = database_path('data/countries.csv');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $countries = array_map('str_getcsv', file($this->_dataFile));
        $countriesToQuery = [];

        foreach ($countries as $country) {
            $countriesToQuery[] = [
                'code' => $country[0],
                'lat' => $country[1],
                'lng' => $country[2],
                'name' => $country[3],
            ];
        }

        Country::query()->insert($countriesToQuery);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Country::query()->delete();
    }
};
