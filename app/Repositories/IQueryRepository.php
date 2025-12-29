<?php

namespace App\Repositories;

interface IQueryRepository {

    public function QueryEntry($credential);
    public function get_all_emp_query($credential);
    public function get_admin_query_default($credential);
    public function get_admin_query($credential);
    public function update_query_status($credential);
    public function Get_docs_Query($credential);
    public function bank_account($doc_bank);
    public function QueryDocEntry($credential);
    public function bank_status();


    
}