<?php

namespace Database\Seeders;

use App\Models\PointOfSale;
use Illuminate\Database\Seeder;

class PointOfSalesTableSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 15 نقطة بيع
        PointOfSale::factory(15)->create();
    }
}