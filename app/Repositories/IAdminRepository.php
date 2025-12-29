<?php

namespace App\Repositories;

interface IAdminRepository {

    public function admin_login_check($credential);
    public function under_admin_emp($credential);
    
    
}