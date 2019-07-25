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

        $this->createYearData($containers);
        $this->createDayData($containers);
    }

    private function createDayData($containers) {
        /**
         * Start date of seeding
         */
        $date = Carbon::now();
        $stOfDay = $date->copy()->startOfDay();

        /**
         * Iterate the containers and add them dummy measures
         */
        foreach($containers as $container) {
            $nMeasures = $this->faker->numberBetween($min = 1000, $max = 2000);
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
                    'created_at' => $stOfDay->copy()->addMinutes(rand(1, 1440))->format('Y-m-d H:i:s')
                ];
                // Add the measure to the list
                $measuresData[] = $measure;

            }
            // Store all the measures
            Measure::insert($measuresData);
        }
    }

    private function createYearData($containers) {
        /**
         * Start date of seeding
         */
        $date = Carbon::now();
        $date->sub('78 weeks');

        /**
         * Iterate the containers and add them dummy measures
         */
        foreach($containers as $container) {
            $nMeasures = $this->faker->numberBetween($min = 10000, $max = 15000);
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
                    'created_at' => $date->copy()->addWeeks(rand(1, 78))->addMinutes(rand(1, 1440))->format('Y-m-d H:i:s')
                ];
                // Add the measure to the list
                $measuresData[] = $measure;

            }
            // Store all the measures
            Measure::insert($measuresData);
        }
    }

}
