<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\history_f_f;
use Carbon\Carbon;
use App\Models\Escalation;
use DateTime;
use Illuminate\Support\Facades\Log;

class croncheckController extends Controller
{
    public function __construct()
    {
        // parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();

        $empids = history_f_f::select('emp_id', \DB::raw('MAX(id) as latest_id'))
        ->groupBy('emp_id')
        ->get();

        $stage_history = history_f_f::whereIn('id', $empids->pluck('latest_id'))
        ->orderBy('emp_id')
        ->get();

        // print_r($empids->toArray());
        // exit;
        Log::info('stage_history',['stage_history' => $stage_history]);
        Log::info('empids',['empids' => $empids]);
        foreach($stage_history as $stage_val){

            //send escalation mail level 2
            $esids = Escalation::select('emp_id', \DB::raw('MAX(id) as latest_id'))
            ->groupBy('emp_id')
            ->get();

            $escalate_history = Escalation::whereIn('id', $esids->pluck('latest_id'))
            ->orderBy('emp_id')
            ->get();

            // $escalate_history = Escalation::where('emp_id' ,$stage_val->emp_id)->where('stage',$stage_val->to_sg)
            // ->groupBy('emp_id')
            // ->latest('id')
            // ->get();

            // print_r($escalate_history->toArray());
            Log::info('stage_val',['stage_val' => $stage_val]);
            Log::info('escalate_history',['escalate_history' => $escalate_history]);
            Log::info('esids',['esids' => $esids]);

            foreach( $escalate_history as $escalate){
                if($escalate->es_level == 1){
                    Log::info('escalate',['escalate' => $escalate]);
                    Log::info('es_level',['es_level' =>$escalate->es_level]);
                    Log::info('emp_id',['emp_id' =>$stage_val->emp_id]);
                    //content level2
                    $mailData = [
                        'body_content1' => 'Hello ! ',
                        'body_content2' => 'Hi, SR No. '.$stage_val->emp_id.' dated '.$stage_val->date.' has exceeded its Turnaround Time (TAT) without resolution, both at the user level and at escalation 1 level. Your immediate attention is required to close this case on top priority.'.$stage_val->to_sg,
                        'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                        'body_content5' => 'Cheers',
                        'body_content6' => 'Alumni',
                    ];
                    

                    $gettime = $escalate->created_at;
                    $secEscalteTime = date('H:i', strtotime($gettime . '+ 1 hour + 30 minutes'));
                    // $secEscalteTime = '11:46' ;
                    Log::info('secEscalteTime',['secEscalteTime' =>$secEscalteTime]);
                    Log::info('time',['time' =>date('H:i')]);
                    
                    $evenTime = date('H:i', strtotime($gettime));
                    $gDate = date('Y-m-d', strtotime($gettime));
                    // print_r(date("H:i"));die;
                    // print_r();die;
                    if($evenTime >= '17:45' && $gDate == date('Y-m-d')){   /// check evening time
                        $nextDay = date('Y-m-d', strtotime($gDate. ' + 1 days'));

                        if(date('H:i') == '10:45' && $nextDay == date('Y-m-d') ){
                            
                             //save escalation history     ****************************************   //
                            $data2 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '2',
                            ];
                            Escalation::create($data2);

                            \Mail::to('manoj.a@hepl.com')
                            ->cc(['arun.se@hepl.com', 'naziya.r@hepl.com']) 
                            // ->send(new \App\Mail\EscalationMail($mailData));
                            // \Mail::to('manoj.a@hepl.com')
                            // ->cc(['arun.se@hepl.com', 'naziya.r@hepl.com']) 
                            ->send(new \App\Mail\EscalationMail($mailData));
                        }
                    }elseif($secEscalteTime == date("H:i") && $secEscalteTime <= '17:45'){

                         //save escalation history     ****************************************   //
                        $data2 = [
                            'emp_id' => $stage_val->emp_id,
                            'stage' => $stage_val->to_sg,
                            'es_level' => '2',
                        ];
                        Escalation::create($data2);

                        \Mail::to('manoj.a@hepl.com')
                        ->cc(['arun.se@hepl.com', 'naziya.r@hepl.com']) 
                        ->send(new \App\Mail\EscalationMail($mailData));
                    }elseif($secEscalteTime > '17:45'){

                        $t1 = strtotime('17:45');
                        $t2 = $secEscalteTime;

                        
                        $dateDiff = intval((strtotime($t2)-strtotime($t1))/60);
                        $hours = intval($dateDiff/60);
                        $minutes = $dateDiff%60;

                        $hrs = !empty($hours) ? $hours : '0';
                        $tDifference = $hrs.':'.$minutes;

                        $nextDay = date('Y-m-d', strtotime($gDate. ' + 1 days'));

                        $t11 = '09:15';
                        $t21 = $tDifference;
                        $secs = strtotime($t21)-strtotime("00:00");
                        $tt1 = date("H:i",strtotime($t11)+$secs);

                        if( $nextDay == date('Y-m-d') && date('H:i') == $tt1){
                             //save escalation history     ****************************************   //
                            $data2 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '2',
                            ];
                            Escalation::create($data2);

                            \Mail::to('manoj.a@hepl.com')
                            ->cc(['arun.se@hepl.com', 'naziya.r@hepl.com']) 
                            ->send(new \App\Mail\EscalationMail($mailData));
                        }
                    }
                }
            }



            ////////////////////Escalation level 1 start /////////////////////////////////////////////////////////////////

            if( $stage_val->from_sg != 6 && $stage_val->to_sg != 7 ){ //check which stage / first stage

                $givenTime = $stage_val->time;
                $valtime = date('H:i:s', strtotime($givenTime . " +3 hours"));
                Log::info('givenTime',['givenTime' =>$givenTime]);
                Log::info('valtime',['valtime' =>$valtime]);
                //send escalation mail level 1
                if($stage_val->time >= '15:01:00'){
                    $data1 = [];
                    $mailData = [];

                    $mailData = [
                        'body_content1' => 'Hello ! ',
                        'body_content2' => 'Hi, SR No. '.$stage_val->emp_id.' dated '.$stage_val->date.' has exceeded its Turnaround Time (TAT) without a resolution. Your immediate action is required to close this case promptly.'.$stage_val->to_sg,
                        'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                        'body_content5' => 'Cheers',
                        'body_content6' => 'HR Team',
                    ];

                    if($stage_val->from_sg == 0 && $stage_val->to_sg == 1){
                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate. ' + 1 days'));
    
                        // print_r('5 after hrss');
                        $checktime = '12:01:00';
                        if($result == date('Y-m-d') && $checktime == date("H:i:s")){

                            $data1 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            Escalation::create($data1);

                            \Mail::to('manoj.a@hepl.com')
                            ->cc('arun.se@hepl.com') // Add the cc email to leader
                            ->send(new \App\Mail\EscalationMail($mailData));
                        }

                    }elseif($stage_val->from_sg == 1 && $stage_val->to_sg == 2 || $stage_val->from_sg == 2 && $stage_val->to_sg == 3){

                        $t1 = '17:45:00';
                        $t2 = $valtime;

                        $dateDiff = intval((strtotime($t2)-strtotime($t1))/60);
                        $hours = intval($dateDiff/60);
                        $minutes = $dateDiff%60;

                        $hrs = !empty($hours) ? $hours : '0';
                        $tDifference = $hrs.':'.$minutes;
                        
                        $nextDay1 = date('Y-m-d', strtotime($stage_val->date. ' + 1 days'));
                             
                        $t1 = '09:15';
                        $t2 = $tDifference;
                        $secs = strtotime($t2)-strtotime("00:00");
                        $ttime = date("H:i",strtotime($t1)+$secs);

                        // print_r('5 after PRHR ---');

                        if( $nextDay1 == date('Y-m-d') && date('H:i') == $ttime){

                            $data1 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            Escalation::create($data1);

                            \Mail::to('manoj.a@hepl.com')->cc('arun.se@hepl.com')->send(new \App\Mail\EscalationMail($mailData));
                        }

                    }elseif($stage_val->from_sg == 3 && $stage_val->to_sg == 4 || $stage_val->from_sg == 4 && $stage_val->to_sg == 5){
                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate. ' + 2 days'));
    
                        $checktime = '17:45';

                        // print_r('5 after PYFN ---');

                        if($result == date('Y-m-d') && $checktime == date("H:i")){

                            $data1 = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            Escalation::create($data1);

                            \Mail::to('manoj.a@hepl.com')
                            ->cc('arun.se@hepl.com') // Add the cc email to leader
                            ->send(new \App\Mail\EscalationMail($mailData));
                        }
                    }elseif($stage_val->from_sg == 5 && $stage_val->to_sg == 6 ){
                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate. ' + 1 days'));
    
                        // print_r('5 before PYFN ---');

                        $checktime = '17:45';
                        if($result == date('Y-m-d') && $checktime == date("H:i")){
                            
                            $dataa = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            Escalation::create($dataa);

                            \Mail::to('manoj.a@hepl.com')
                            ->cc('arun.se@hepl.com') // Add the cc email to leader
                            ->send(new \App\Mail\EscalationMail($mailData));
                        }
                    }

                }else{

                    $mailData = [];
                    //content level1
                    $mailData = [
                        'body_content1' => 'Hello ! ',
                        'body_content2' => 'Hi, SR No. '.$stage_val->emp_id.' dated '.$stage_val->date.' has exceeded its Turnaround Time (TAT) without a resolution. Your immediate action is required to close this case promptly.',
                        'body_content4' => 'https://citpl_alumni.cavinkare.in/index.php/login',
                        'body_content5' => 'Cheers',
                        'body_content6' => 'Alumni',
                    ];

                    if($stage_val->from_sg == 3 && $stage_val->to_sg == 4 || $stage_val->from_sg == 4 && $stage_val->to_sg == 5 || $stage_val->from_sg == 5 && $stage_val->to_sg == 6){
                        
                        $saveddate = $stage_val->date;
                        $result = date('Y-m-d', strtotime($saveddate. ' + 1 days'));
    
                        // print_r('5 before PYFN ---');

                        $checktime = '17:45';
                        if($result == date('Y-m-d') && $checktime == date("H:i")){
                            
                            $dataa = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            Escalation::create($dataa);

                            \Mail::to('manoj.a@hepl.com')
                            ->cc('arun.se@hepl.com') // Add the cc email to leader
                            ->send(new \App\Mail\EscalationMail($mailData));
                        }

                    }else{

                        if($valtime == date("H:i:s") && $stage_val->date == date('Y-m-d')){
                         
                            $dataa = [
                                'emp_id' => $stage_val->emp_id,
                                'stage' => $stage_val->to_sg,
                                'es_level' => '1',
                            ];
                            Escalation::create($dataa);
    
                            // print_r('5 before PYHR ---');


                            \Mail::to('manoj.a@hepl.com')
                            ->cc('arun.se@hepl.com') // Add the cc email to leader
                            ->send(new \App\Mail\EscalationMail($mailData));
                            
                        }
                    }
                }
            }

             
          
        }



    }

}
