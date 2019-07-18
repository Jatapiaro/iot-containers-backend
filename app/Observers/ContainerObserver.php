<?php

namespace App\Observers;

use App\Models\Container;

class ContainerObserver
{
    /**
     * Handle the container "creating" event.
     *
     * @param App\Models\Container $container
     * @return void
     */
    public function creating(Container $container)
    {
        $container->dummy = (!is_null($container->device_id))? false : true;
    }

    /**
     * Handle the container "updating" event.
     *
     * @param App\Models\Container $container
     * @return void
     */
    public function updating(Container $container)
    {
        $container->dummy = (!is_null($container->device_id))? false : true;
    }
}
