<?php
namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
 {

    public function register() {
        $this->app->bind(
            'App\Repositories\IEmpRepository',
            'App\Repositories\EmpRepository'             
        );
        $this->app->bind(
            'App\Repositories\IQueryRepository',
            'App\Repositories\QueryRepository'             
        );
        $this->app->bind(
            'App\Repositories\IDocRepository',
            'App\Repositories\DocRepository'             
        );
        $this->app->bind(
            'App\Repositories\IAdminRepository',
            'App\Repositories\AdminRepository'             
        );
        $this->app->bind(
            'App\Repositories\ICheck_point_Repository',
            'App\Repositories\Check_point_Repository'             
        );
        $this->app->bind(
            'App\Repositories\IF_F_tracker_Repository',
            'App\Repositories\F_F_tracker_Repository'             
        );
        
        
    }
}
