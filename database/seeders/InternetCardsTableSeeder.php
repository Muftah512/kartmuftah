<?php

namespace Database\Seeders;

use App\Models\InternetCard;
use App\Models\Package;
use App\Models\PointOfSale;
use Illuminate\Database\Seeder;

class InternetCardsTableSeeder extends Seeder
{
    public function run()
    {
        $pointsOfSale = PointOfSale::all();
        $packages = Package::all();

        foreach ($pointsOfSale as $pos) {
            for ($i = 0; $i < 5; $i++) {
                InternetCard::create([
                    'username' => 'user_' . $pos->id . '_' . $i . '_' . uniqid(),
                    'package_id' => $packages->random()->id,
                    'pos_id' => $pos->id,
                    'expiration_date' => now()->addDays(rand(7, 90)),
                ]);
            }
        }
    }
}