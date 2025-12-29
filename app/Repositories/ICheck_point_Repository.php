<?php

namespace App\Repositories;

interface ICheck_point_Repository {

    
    public function get_c_p_data_to_fill($credentials);
    public function get_c_p_emp_data($credentials);
    public function check_q_availablity($credentials);
    public function inset_q_rating($credentials);
    public function update_q_rating($credentials);
    public function update_f_f_status($credentials);
    
}