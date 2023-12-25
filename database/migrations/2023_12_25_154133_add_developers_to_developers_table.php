<?php

use App\Models\Country;
use App\Models\Developer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private string $_dataFile;

    public function __construct()
    {
        $this->_dataFile = database_path('data/developers.csv');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $developers = array_map('str_getcsv', file($this->_dataFile));
        $developersToQuery = [];

        foreach ($developers as $developer) {
            $developersToQuery[] = [
                'name' => $developer[0],
                'slug' => \Illuminate\Support\Str::slug($developer[0]),
                'country_id' => Country::query()->where('code', $developer[1])->first()->id,
                'founded_at' => date('Y-m-d H:i:s', strtotime($developer[2])),
                'url' => $developer[3],
            ];
        }

        Developer::query()->insert($developersToQuery);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Developer::query()->delete();
    }
};
