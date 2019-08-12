<?php namespace BookStack\Providers;

use Illuminate\Support\ServiceProvider;
use BookStack\Orz\SpaceRepo;
use Illuminate\Support\Facades\View;

class DataServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(SpaceRepo $spaceRepo)
    {
        $share = $spaceRepo->all();
        View::share('share_space',$share->where('type',1));
    }
}
