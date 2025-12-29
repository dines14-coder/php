<?php

namespace App\Repositories;

interface IDocRepository {

    public function get_ambassador_default($credentials);
    public function get_ambassador($credentials);
    
}