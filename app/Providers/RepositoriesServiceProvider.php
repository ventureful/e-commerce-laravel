<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

##AUTO_INSERT_USE##
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\UserRepository;

class RepositoriesServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public $bindings = [
        ##AUTO_INSERT_BIND##
        IUserRepository::class => UserRepository::class
    ];
}
