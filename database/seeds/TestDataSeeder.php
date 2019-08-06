<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Carbon\Carbon;

/**
 * Models
 */
use App\Models\Measure;
use App\Models\User;

/**
 * Services
 */
use App\Services\ContainerService;
use App\Services\ParticleService;

class TestDataSeeder extends Seeder
{

    /**
     * Container Service
     *
     * @var App\Services\ContainerService
     */
    private $cs;

    /**
     * Faker
     *
     * @var Faker\Generator
     */
    private $faker;

    /**
     * Particle service
     *
     * @var App\Services\ParticleService
     */
    private $ps;

    public function __construct(ContainerService $cs,
        Faker $faker,
        ParticleService $ps)
    {
        $this->cs = $cs;
        $this->faker = $faker;
        $this->ps = $ps;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Look for the user, if it is on the db
         * stop the seeding process
         */
        $prevUser = User::where('email', 'jacob.et.cetera@gmail.com')->first();
        if (!is_null($prevUser)) {
            return;
        }

        /**
         * Creates the user and mirrors it
         * in Particle Cloud
         */
        $u = User::create([
            'email' => 'jacob.et.cetera@gmail.com',
            'name' => 'Usuario Prueba',
            'password' => '$2y$10$BBonVIoCFlEaCCm/lkHbju.mxRgWZkAMfu0sTpUNO7N2535VYnT/2',
        ]);
        $this->ps->mirror($u->email);

        /**
         * Generate n containers
         */
        $containers = [];
        $nContainers = $this->faker->numberBetween($min = 1, $max = 2);
        for ($i = 0; $i < $nContainers; $i++) {
            // Generates container data
            $contData = [
                'container' => [
                    'height' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 2),
                    'name' => $this->faker->firstName(),
                    'radius' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0.5, $max = 1.5),
                    'user_id' => $u->id,
                ]
            ];
            // Store container and add it to the array
            $containers[] = $this->cs->store($contData);
        }

        $this->createMeasureData($containers, 'day', 2500, 5000);
        $this->createMeasureData($containers, 'week', 5000, 7500);
        $this->createMeasureData($containers, 'month', 7500, 10000);
        $this->createMeasureData($containers, 'year', 10000, 15000);
    }

    /**
     * Creates the dummy measures for a given $interval
     *
     * @param array $containters to be filled
     * @param str $interval of the measures
     * @param int $minMeasures to add
     * @param int $maxMeasures to add
     */
    private function createMeasureData(
        $containers,
        $interval = 'day',
        $minMeasures = 1000,
        $maxMeasures = 2000)
    {

        $startDate = $this->getStartDate($interval);
        foreach($containers as $container) {
            $nMeasures = $this->faker->numberBetween($min = $minMeasures, $max = $maxMeasures);
            $measuresData = [];

            // Constants in this iteration
            $pi = pi();
            $r2 = $container->radius * $container->radius;

            for ($i = 0; $i < $nMeasures; $i++) {

                // We got a dummy measured height
                $measureHeight = $this->faker->randomFloat(
                    $nbMaxDecimals = 2,
                    $min = 0,
                    $max = $container->height
                );

                // Calcuclate the current volume
                $measureVolume = $measureHeight * $pi * $r2;

                // Save the measure data
                $measure = [
                    'container_id' => $container->id,
                    'height' => $measureHeight,
                    'volume' => ($container->volume - $measureVolume),
                    'created_at' => $this->getDateGivenRange($startDate, $interval)
                ];
                // Add the measure to the list
                $measuresData[] = $measure;

            }
            // Store all the measures
            Measure::insert($measuresData);
        }

    }

    /**
     * Calcultates the start date of a given interval
     * @param str $interval
     *
     * @return Carbon $startDate
     */
    private function getStartDate($interval = 'day') {
        /**
         * Get today's date
         */
        $date = Carbon::now();
        switch($interval) {
            case 'day':
                return $date->copy()->startOfDay();
            case 'week':
                return $date->copy()->startOfWeek();
            case 'month':
                return $date->copy()->startOfMonth();
            case 'year':
                return $date->copy()->startOfYear();
        }
    }

    /**
     * Given a start date, and an interval, return a new date in the given interval
     *
     * @param Carbon $startDate
     * @param str $interval
     *
     * @return Carbon formated date
     */
    private function getDateGivenRange($startDate, $interval = 'day') {
        switch($interval) {
            case 'day':
                return $startDate->copy()->addMinutes(rand(0, 1440))->format('Y-m-d H:i:s');
            case 'week':
                return $startDate->copy()->addMinutes(rand(0, (1440*7)))->format('Y-m-d H:i:s');
            case 'month':
                return $startDate->copy()->addMinutes(rand(0, (1440*32)))->format('Y-m-d H:i:s');
            case 'year':
                return $startDate->copy()->addMinutes(rand(0, (1440*367)))->format('Y-m-d H:i:s');
        }
    }

}
