<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Models\History_f_f;
use App\Models\F_F_tracker_alumni_data;
use Carbon\Carbon;
use App\Models\Escalation;
use App\Models\EscalateMailDetails;
use App\Models\emp_profile_tbl;
use DateTime;
use Illuminate\Support\Facades\Log;

class EscalationMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'escalation:toall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();
       
        $empids = History_f_f::select('emp_id', \DB::raw('MAX(id) as latest_id'))
            ->groupBy('emp_id')
            ->get();

        $stage_history = History_f_f::whereIn('id', $empids->pluck('latest_id'))
            ->orderBy('emp_id')
            ->get();



        foreach ($stage_history as $stage_val) {
            
            
            // print_r($stage_val->from_sg == 5);
            // print_r($stage_val->from_sg == 5);
            $esids = Escalation::select('emp_id', \DB::raw('MAX(id) as latest_id'))
            ->groupBy('emp_id')
                ->get();

                $escalate_history = Escalation::whereIn('id', $esids->pluck('latest_id'))
                ->orderBy('emp_id')
                ->get();
                
                $pay_rec = F_F_tracker_alumni_data::select('pay_rec')->where('emp_id', $stage_val->emp_id)->first();
                $lastWorkingDate = emp_profile_tbl::select('last_working_date')->where('emp_id', $stage_val->emp_id)->first();
                ////////////////////Escalation level 2 start /////////////////////////////////////////////////////////////////
            $getMailId = EscalateMailDetails::where('to_sg', $stage_val->to_sg)->first();

            if ($stage_val->from_sg != 6 && $stage_val->to_sg != 7 && $stage_val->revert_status != 'reverted') {
                foreach ($escalate_history as $escalate) {

                    if ($escalate->es_level == 1  && $escalate->emp_id == $stage_val->emp_id && $escalate->stage == $stage_val->to_sg) {

                        $mailData = [
                            'body_content1' => 'Hello ! ',
                            'body_content2' => 'Hi, SR No. ' . $stage_val->emp_id . ' dated ' . $stage_val->date . ' has exceeded its Turnaround Time (TAT) without resolution, both at the user level and at escalation 1 level. Your immediate attention is required to close this case on top priority.',
                            'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                            'body_content5' => 'Cheers',
                            'body_content6' => 'Alumni',
                        ];
                        $gettime = $escalate->created_at;
                        $secEscalteTime = date('H:i', strtotime($gettime . '+ 1 hour + 30 minutes'));
                       
                        $evenTime = date('H:i', strtotime($gettime));
                        $gDate = date('Y-m-d', strtotime($gettime));
                        $saveddateDayOfWeek = date('N', strtotime($gDate)); // 'N' gives 1 for Monday and 7 for Sunday
                        if ($saveddateDayOfWeek == 6) {
                            $gDate = date('Y-m-d', strtotime($gDate . ' + 2 days'));
                        } elseif ($saveddateDayOfWeek == 7) {   
                            $gDate = date('Y-m-d', strtotime($gDate. ' + 1 day'));
                        } 
                        if ($stage_val->from_sg == 3 && $stage_val->to_sg == 4 || $stage_val->from_sg == 4 && $stage_val->to_sg == 5 ) {
                            $saveddate = $escalate->created_at;
                            $result = date('Y-m-d', strtotime($saveddate . ' + 1 days'));
                            $saveddateDayOfWeek = date('N', strtotime($result)); // 'N' gives 1 for Monday and 7 for Sunday
                            if ($saveddateDayOfWeek == 6) {
                                $result = date('Y-m-d', strtotime($result . ' + 2 days'));
                            } elseif ($saveddateDayOfWeek == 7) {   
                                $result = date('Y-m-d', strtotime($result. ' + 1 day'));
                            } 
                            $checktime = '17:45';

                            if ($result == date('Y-m-d') && $checktime == date("H:i")) {

                                $data1 = [
                                    'emp_id' => $stage_val->emp_id,
                                    'stage' => $stage_val->to_sg,
                                    'es_level' => '2',
                                ];
                                // Escalation::create($data1);
                                if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable"){
                                    \Mail::to($getMailId->to_mail)
                                        ->cc([$getMailId->level_one_cc, $getMailId->level_two_cc]) 
                                        ->send(new \App\Mail\EscalationMail($mailData));
                                    Escalation::create($data2);
                            
                                }else{
                                    Log::info("skiped");
                                }
                            }
                        } else if ($stage_val->from_sg == 5 && $stage_val->to_sg == 6 ) {
                            $saveddate = $escalate->created_at;
                            $result = date('Y-m-d', strtotime($saveddate . ' + 1 days'));

                            $dayOfWeek = date('w', strtotime($result));
                            if ($dayOfWeek == 0) {
                                $result = date('Y-m-d', strtotime($result . ' + 1 days'));
                            }
                            elseif ($dayOfWeek == 6) {
                                $result = date('Y-m-d', strtotime($result . ' + 2 days'));
                            }

                            $checktime = '17:45';

                            if ($result == date('Y-m-d') && $checktime == date("H:i")) {

                                $data1 = [
                                    'emp_id' => $stage_val->emp_id,
                                    'stage' => $stage_val->to_sg,
                                    'es_level' => '2',
                                ];
                                // Escalation::create($data1);
                                if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {
                                    \Mail::to($getMailId->to_mail)
                                        ->cc([$getMailId->level_one_cc, $getMailId->level_two_cc]) 
                                        ->send(new \App\Mail\EscalationMail($mailData));
                                    Escalation::create($data2);
                            
                                }else{
                                    Log::info("skiped");
                                }
                            }
                        } else if ($evenTime >= '17:45' && $gDate == date('Y-m-d')) {   /// check evening time
                            $nextDay = date('Y-m-d', strtotime($gDate . ' + 1 days'));

                            if (date('H:i') == '10:45' && $nextDay == date('Y-m-d')) {

                                //save escalation history     ****************************************   //
                                $data2 = [
                                    'emp_id' => $stage_val->emp_id,
                                    'stage' => $stage_val->to_sg,
                                    'es_level' => '2',
                                ];
                                if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {

                                    \Mail::to($getMailId->to_mail)
                                        ->cc([$getMailId->level_one_cc, $getMailId->level_two_cc]) 
                                        ->send(new \App\Mail\EscalationMail($mailData));
                                    Escalation::create($data2);
                            
                                }
                            }
                        } elseif ($secEscalteTime == date("H:i") && $secEscalteTime <= '17:45') {

                            $data2 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '2',
                            ];
                            // Escalation::create($data2);

                            if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {

                                \Mail::to($getMailId->to_mail)
                                    ->cc([$getMailId->level_one_cc, $getMailId->level_two_cc]) 
                                    ->send(new \App\Mail\EscalationMail($mailData));
                                Escalation::create($data2);
                        
                            }
                        } elseif ($secEscalteTime > '17:45') {

                            $t1 = strtotime('17:45');
                            $t2 = $secEscalteTime;


                            $dateDiff = intval((strtotime($t2) - strtotime($t1)) / 60);
                            $hours = intval($dateDiff / 60);
                            $minutes = $dateDiff % 60;

                            $hrs = !empty($hours) ? $hours : '0';
                            $tDifference = $hrs . ':' . $minutes;

                            $nextDay = date('Y-m-d', strtotime($gDate . ' + 1 days'));
                            $t11 = '09:15';
                            $t21 = $tDifference;
                            $secs = strtotime($t21) - strtotime("00:00");
                            $tt1 = date("H:i", strtotime($t11) + $secs);
                            $saveddateDayOfWeek = date('N', strtotime($nextDay)); // 'N' gives 1 for Monday and 7 for Sunday
                            if ($saveddateDayOfWeek == 6) {
                                $nextDay = date('Y-m-d', strtotime($nextDay . ' + 2 days'));
                            } elseif ($saveddateDayOfWeek == 7) {   
                                $nextDay = date('Y-m-d', strtotime($nextDay. ' + 1 day'));
                            } 
                            if ($nextDay == date('Y-m-d') && date('H:i') == $tt1) {
                                $data2 = [
                                    'emp_id' => $stage_val->emp_id,
                                    'stage' => $stage_val->to_sg,
                                    'es_level' => '2',
                                ];
                                Escalation::create($data2);

                                if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable"){

                                    \Mail::to($getMailId->to_mail)
                                        ->cc([$getMailId->level_one_cc, $getMailId->level_two_cc]) 
                                        ->send(new \App\Mail\EscalationMail($mailData));
                                    Escalation::create($data2);
                            
                                }
                            }
                        }
                    }
                }
            }



            ////////////////////Escalation level 1 start /////////////////////////////////////////////////////////////////

            if ($stage_val->from_sg != 6 && $stage_val->to_sg != 7 && $stage_val->revert_status != 'reverted') { //check which stage / first stage

                $givenTime = $stage_val->time;
                $givenTimeWithoutSec = date('H:i', strtotime($givenTime));
                $valtime = date('H:i', strtotime($givenTimeWithoutSec . " +3 hours"));
                // $valtime = date('H:i:s', strtotime($givenTime . " +3 hours"));

                //send escalation mail level 1
                // if($stage_val->to_sg == 6)
                if ($stage_val->time >= '15:01:00') {
                    $data1 = [];
                    $mailData = [];

                    $mailData = [
                        'body_content1' => 'Hello ! ',
                        'body_content2' => 'Hi, SR No. ' . $stage_val->emp_id . ' dated ' . $stage_val->date . ' has exceeded its Turnaround Time (TAT) without a resolution. Your immediate action is required to close this case promptly.',
                        'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                        'body_content5' => 'Cheers',
                        'body_content6' => 'Alumni',
                    ];

                    
                    // if($stage_val->from_sg == 0 && $stage_val->to_sg == 1){
                    //     $saveddate = $stage_val->date;
                    //     $result = date('Y-m-d', strtotime($saveddate. ' + 1 days'));
                    // $saveddateDayOfWeek = date('N', strtotime($result)); // 'N' gives 1 for Monday and 7 for Sunday
                    // if ($saveddateDayOfWeek == 6) {
                    //     $result = date('Y-m-d', strtotime($result . ' + 2 days'));
                    // } elseif ($saveddateDayOfWeek == 7) {   
                    //     $result = date('Y-m-d', strtotime($result. ' + 1 day'));
                    // }     
                    //     // print_r('5 after hrss');
                    //     $checktime = '12:01';
                    //     if($result == date('Y-m-d') && $checktime == date("H:i")){

                    //         $data1 = [
                    //             'emp_id' => $stage_val->emp_id,
                    //             'stage' => $stage_val->to_sg,
                    //             'es_level' => '1',
                    //         ];
                    //         // Escalation::create($data1);
                    //         \Mail::to($getMailId->to_mail)
                    //                 ->cc($getMailId->level_one_cc) 
                    //                 ->send(new \App\Mail\EscalationMail($mailData));
                    //             Escalation::create($data1);

                    //         // \Mail::to('manoj.a@hepl.com','marieswari.v@hepl.com')
                    //         // ->cc('arun.se@hepl.com') // Add the cc email to leader
                    //         // ->send(new \App\Mail\EscalationMail($mailData));
                    //     }

                    // }
                    // else
                    if ($stage_val->from_sg == 1 && $stage_val->to_sg == 2 || $stage_val->from_sg == 2 && $stage_val->to_sg == 3) {

                        $t1 = '17:45:00';
                        $t2 = $valtime;

                        $dateDiff = intval((strtotime($t2) - strtotime($t1)) / 60);
                        $hours = intval($dateDiff / 60);
                        $minutes = $dateDiff % 60;

                        $hrs = !empty($hours) ? $hours : '0';
                        $tDifference = $hrs . ':' . $minutes;
                        $nextDay1 = date('Y-m-d', strtotime($stage_val->date . ' + 1 days'));
                        $saveddateDayOfWeek = date('N', strtotime($nextDay1)); // 'N' gives 1 for Monday and 7 for Sunday
                        if ($saveddateDayOfWeek == 6) {
                            $nextDay1 = date('Y-m-d', strtotime($nextDay1 . ' + 2 days'));
                        } elseif ($saveddateDayOfWeek == 7) {   
                            $nextDay1 = date('Y-m-d', strtotime($nextDay1. ' + 1 day'));
                        } 

                        $t1 = '09:15';
                        $t2 = $tDifference;
                        $secs = strtotime($t2) - strtotime("00:00");
                        $ttime = date("H:i", strtotime($t1) + $secs);

                        // print_r('5 after PRHR ---');

                        if ($nextDay1 == date('Y-m-d') && date('H:i') == $ttime) {

                            $data1 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            // Escalation::create($data1);

                            if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {

                                \Mail::to($getMailId->to_mail)
                                    ->cc($getMailId->level_one_cc) 
                                    ->send(new \App\Mail\EscalationMail($mailData));
                                Escalation::create($data1);
                        
                            }
                        }
                    } elseif ($stage_val->from_sg == 3 && $stage_val->to_sg == 4 || $stage_val->from_sg == 4 && $stage_val->to_sg == 5) {
                        Log::info($stage_val->to_sg);
                        
                        
                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate . ' + 2 days'));
                        $saveddateDayOfWeek = date('N', strtotime($result)); // 'N' gives 1 for Monday and 7 for Sunday
                        if ($saveddateDayOfWeek == 6) {
                            Log::info($stage_val->emp_id);
                            $result = date('Y-m-d', strtotime($result . ' + 2 days'));
                        } elseif ($saveddateDayOfWeek == 7) {   
                            $result = date('Y-m-d', strtotime($result. ' + 1 day'));
                        } 

                        $checktime = '17:45';
                        // $checktime = '12:25';
                        Log::info("cxdvd   ".$stage_val->emp_id);
                        Log::info("cxdvd12   ".$result);
                        Log::info("time   ". $checktime == date("H:i"));
                        Log::info("time   ".$result == date('Y-m-d'));

                        // print_r('5 after PYFN ---');

                        if ($result == date('Y-m-d') && $checktime == date("H:i")) {

                            $data1 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {

                                \Mail::to($getMailId->to_mail)
                                    ->cc($getMailId->level_one_cc) 
                                    ->send(new \App\Mail\EscalationMail($mailData));
                                Escalation::create($data1);
                        
                            }
                        }
                    } elseif ($stage_val->from_sg == 5 && $stage_val->to_sg == 6) {
                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate . ' + 1 days'));

                        $dayOfWeek = date('w', strtotime($result));
                        if ($dayOfWeek == 0) {
                            // Move to the next day (Monday)
                            $result = date('Y-m-d', strtotime($result . ' + 1 days'));
                        }
                        elseif ($dayOfWeek == 6) {
                            // Move to the next day (Monday)
                            $result = date('Y-m-d', strtotime($result . ' + 2 days'));
                        }

                        // print_r('5 before PYFN ---');

                        $checktime = '17:45';
                        if ($result == date('Y-m-d') && $checktime == date("H:i")) {

                            $dataa = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            // Escalation::create($dataa);

                            if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {

                                \Mail::to($getMailId->to_mail)
                                    ->cc($getMailId->level_one_cc) 
                                    ->send(new \App\Mail\EscalationMail($mailData));
                                Escalation::create($dataa);
                        
                            }
                        }
                    }
                } else {

                    $mailData = [];
                    //content level1
                    $mailData = [
                        'body_content1' => 'Hello ! ',
                        'body_content2' => 'Hi, SR No. ' . $stage_val->emp_id . ' dated ' . $stage_val->date . ' has exceeded its Turnaround Time (TAT) without a resolution. Your immediate action is required to close this case promptly.',
                        'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                        'body_content5' => 'Cheers',
                        'body_content6' => 'Alumni',
                    ];

                    if ($stage_val->from_sg == 3 && $stage_val->to_sg == 4 || $stage_val->from_sg == 4 && $stage_val->to_sg == 5 || $stage_val->from_sg == 5 && $stage_val->to_sg == 6) {

                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate . ' + 1 days'));
                        $dayOfWeek = date('w', strtotime($result));
                        if ($dayOfWeek == 0) { 
                            $result = date('Y-m-d', strtotime($result . ' + 1 days'));
                        }elseif ($dayOfWeek == 6 ) {  
                            $result = date('Y-m-d', strtotime($result . ' + 2 days'));
                        }
                        
                        // print_r('5 before PYFN ---');

                        $checktime = '17:45';
                        // $checktime = '14:25';
                        if ($result == date('Y-m-d') && $checktime == date("H:i")) {

                            $dataa = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            // Escalation::create($dataa);

                            if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable") {

                                \Mail::to($getMailId->to_mail)
                                    ->cc($getMailId->level_one_cc) 
                                    ->send(new \App\Mail\EscalationMail($mailData));
                                Escalation::create($dataa);
                        
                            }
                        }
                    }else{
                        $saveddateDayOfWeek = date('N', strtotime($valtime)); // 'N' gives 1 for Monday and 7 for Sunday
                        if ($saveddateDayOfWeek == 6) {
                            $valtime = date('Y-m-d', strtotime($valtime . ' + 2 days'));
                        } elseif ($saveddateDayOfWeek == 7) {   
                            $valtime = date('Y-m-d', strtotime($valtime. ' + 1 day'));
                        } 
                        if ($valtime == date("H:i") && $stage_val->date == date('Y-m-d') && $stage_val->to_sg != 1) {

                            $dataa = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            // Escalation::create($dataa);

                            if ($stage_val->to_sg == 5 && isset($pay_rec) && $pay_rec->pay_rec != "Recoverable"){

                                \Mail::to($getMailId->to_mail)
                                    ->cc($getMailId->level_one_cc) 
                                    ->send(new \App\Mail\EscalationMail($mailData));
                                Escalation::create($dataa);
                        
                            }

                        }
                    }
                }
            }
        }
    }
}
