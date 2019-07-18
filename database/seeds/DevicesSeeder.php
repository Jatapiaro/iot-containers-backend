<?php

use Illuminate\Database\Seeder;

/**
 * Models
 */
use App\Models\Device;


class DevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $ids = [
            '350043001147343438323536'
        ];
        $data = [];
        foreach($ids as $id) {

            $prev = Device::find($id);
            if (is_null($prev)) {
                $data[] = ['id' => $id];
            }

        }
        Device::insert($data);

    }
}
