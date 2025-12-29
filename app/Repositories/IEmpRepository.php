<?php

namespace App\Repositories;

interface IEmpRepository {

    public function add_ambassador($credential);
    public function emp_reg_check_type_1($credential);
    public function emp_reg_check_type_2($credential);
    public function get_employee_detail($credential);
    public function emp_otp_update($credential);
    public function emp_update_after_valid_otp($credential);
    
    
}