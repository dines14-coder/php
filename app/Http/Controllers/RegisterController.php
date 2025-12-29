<?php

namespace App\Http\Controllers;

use Mail;
use Session;
use DB;
use Illuminate\Http\Request;
use App\Repositories\IEmpRepository;
use App\Repositories\IAdminRepository;
use Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegisterController extends Controller
{
    //

    public function __construct( IEmpRepository $emp_task,IAdminRepository $admin_task ) {
        // $this->middleware( 'auth' ); 
        $this->emp_task = $emp_task;
        $this->admin_task = $admin_task;
        
    }


    public function login() { 
        return view( 'login' );
    }
    public function register() {
        return view( 'register' );
    }
    public function logout() { 
        auth()->logout();
        Session::flush();
        return redirect()->to( 'login' );
    }
    public function Adminlogin()
    {
        return view( 'Admin.login' );
    }
    public function forgot_pass(){
        return view( 'forgot_pass_reset' );
    }
    public function admin_login_check(Request $request)
    {
        $login_credentals=[
            'emp_id'=>$request->input('emp_id'),
            'password'=>base64_encode($request->input('password')),
            'status'=>'Active',
        ];
        $get = $this->admin_task->admin_login_check( $login_credentals );
        $account_row= count($get);
        if($account_row=="1"){
            // account valid 
            session(
                [
                    'user_type' => $get[0]->user_type, 
                    'emp_id'=>$get[0]->emp_id,
                    'email'=>$get[0]->email,
                    'emp_name'=>$get[0]->emp_name,
                    'department'=>$get[0]->department,
                ] 
            );

            if(session('user_type')=="F_F_Admin" ){
                return response()->json( ['url'=>route( 's_admin.dashboard' ), 'response' => 'success'] );
            }if(session('user_type')=="Super_Admin"){
                return response()->json( ['url'=>route( 'p_s_admin.dashboard' ), 'response' => 'success'] );
            } if(session('user_type')=="Claims" || session('user_type')=="Payroll_Finance" || session('user_type')=="Payroll_HR" || session('user_type')=="HR-LEAD" || session('user_type')=="Payroll_IT" || session('user_type')=="IT-INFRA"){
                return response()->json( ['url'=>route( 'F_and_F_document.form' ), 'response' => 'success'] );
            } else if(session('user_type')=="Payroll_QC"){
                return response()->json( ['url'=>route( 'f_f_tracker_landing' ), 'response' => 'success'] );
            } else{
                return response()->json( ['url'=>route( 'admin.dashboard' ), 'response' => 'success'] );
            }
        }
        else{
            // account  not valid error pass
            $response = 'not_valid';
            return response()->json( ['response' => $response] );
        }
    }
    public function login_check(Request $request)
    {

        // admin login check temp created

        $login_credentals=[
            'emp_id'=>$request->input('emp_id'),
            'password'=>base64_encode($request->input('password')),
            'status'=>'Active',
        ];
        $get = $this->admin_task->admin_login_check( $login_credentals );
        $account_row= count($get);
        if($account_row=="1"){
            // account valid 
            session(
                [
                    'user_type' => $get[0]->user_type, 
                    'emp_id'=>$get[0]->emp_id,
                    'email'=>$get[0]->email,
                    'emp_name'=>$get[0]->emp_name,
                     'department'=>$get[0]->department,
 
                ] 
            );

            if(session('user_type')=="F_F_Admin" ){
                return response()->json( ['url'=>route( 's_admin.dashboard' ), 'response' => 'success'] );
            }if(session('user_type')=="Super_Admin"){
                return response()->json( ['url'=>route( 'p_s_admin.dashboard' ), 'response' => 'success'] );
            } if(session('user_type')=="Claims" || session('user_type')=="Payroll_Finance" || session('user_type')=="Payroll_HR" || session('user_type')=="HR-LEAD" || session('user_type')=="Payroll_IT" || session('user_type')=="IT-INFRA"){
                return response()->json( ['url'=>route( 'F_and_F_document.form' ), 'response' => 'success'] );
            } else if(session('user_type')=="Payroll_QC"){
                return response()->json( ['url'=>route( 'f_f_tracker_landing' ), 'response' => 'success'] );
            } else{
                return response()->json( ['url'=>route( 'admin.dashboard' ), 'response' => 'success'] );
            }
        }
        // else{
        //     $response = 'not_valid';
        //     return response()->json( ['response' => $response] );
        // }

        // end admin login check

        $login_credentals=[
            'email'=>$request->input('emp_id'),
            'password'=>$request->input('password'),
        ];

        $get = $this->emp_task->get_employee_detail_u_mail( $login_credentals );
        // dd(auth()->attempt( $login_credentals, true ));
        if(auth()->attempt( $login_credentals, true )){

            

            if($get[0]->status=="Active"){

                session(
                    [
                        'emp_id'=>$get[0]->emp_id,
                        'emp_name'=>$get[0]->emp_name,
                    ] 
                );

                // Check if this is first-time login
                if($get[0]->is_first_login) {
                    return response()->json( ['url'=>route('password_update_landing'), 'response' => 'first_login'] );
                }

                return response()->json( ['url'=>route('dashboard'), 'response' => 'success'] );
            }
            if($get[0]->status=="Hold"){
                // account  not valid error pass
                $response = 'hold';
                return response()->json( ['response' => $response] );
            }

        }
        else{
            $response= "not valid";
            return response()->json( ['response' => $response] );
        }
    }

    public function password_change_submit(Request $request){
        $password = $request->input('password');
        $c_password = $request->input('c_password');
        $email=base64_decode($request->input('email'));
        if($password==$c_password){
            // update password
            $credentials=[
                'email'=>$email,
                'password'=>$request->input('password'),
            ];
            $update_query = $this->emp_task->upd_amb_pass( $credentials );
            $response= "success";
            return response()->json( ['response' => $response] );
        }
        else{
            $response= "password mismatch";
            return response()->json( ['response' => $response] );
        }
    }

    public function f_p_submit(Request $request){


        $get = $this->emp_task->get_employee_detail_u_mail( $request->input('f_p_mailid') );

        if(isset($get[0])){

            // send query mail
        
            // To Master Mail 
            $company_email = $request->input('f_p_mailid');
            // $company_email ="lakshminarayanan@hemas.in"; 

            $body_content1 = "You have requested to reset your password";
            $body_content2 = "We cannot simply send you your old password. A unique link to reset your password has been generated for you. To reset your password, click the following link and follow the instructions.";
            $body_content3 = 'Powered by Alumni';

            $details = [
                'subject' => 'Alumni - ID password reset request.',
                'title' => 'ID password reset request',
                'body_content1' => $body_content1,
                'body_content2' => $body_content2,
                'body_content3' => $body_content3,
                'mailid' => base64_encode($company_email),
            ];

            // in proper laravel method mail send plz enable below link
            \Mail::to($company_email)->send(new \App\Mail\FP_mail($details));

            $response= "success";
            return response()->json( ['response' => $response,'send_mail_to'=>$company_email]);


        }
        else{
            $response= "Not Available";
            return response()->json( ['response' => $response]);

        }
        

        

    }

    public function register_process(Request $request)
    {
            $dt = new \Carbon\Carbon();
            $before = $dt->subYears(18)->format('d-m-Y');
            $mes = ['email.regex'=>"Please enter your personal email."];
            $validator = Validator::make($request->all(),[
                'employee_id' =>'required|unique:emp_profile_tbls,emp_id|alpha_num',
                'name' =>'required|regex:/(^[A-Za-z\s]+$)+/u|max:33',
                'email' => ['required','email:rfc,dns', 'unique:emp_profile_tbls','regex:/(.*)@(gmail|outlook|yahoo).com/i'],
                'pan_number' =>'required|unique:emp_profile_tbls,pan_no|alpha_num|min:10|max:10',
                'dob' =>'required||before:'.$before,
                'contact_number' =>'required|unique:emp_profile_tbls,mobileno|numeric|digits:10',
            ],$mes);

            if($validator->passes()){  
                    // employee register
                    $credentials = [
                        'emp_name'=>$request->input('name'),
                        'emp_id'=>$request->input('employee_id'),
                        'pan_no'=>$request->input('pan_number'),
                        'dob'=>$request->input('dob'),
                        'mobileno'=>$request->input('contact_number'),
                        'email'=>$request->input('email'),
                        'type_of_leaving'=>"",
                        'last_working_date'=>"",
                        'f_f_document'=>"",
                        'cl_c_p'=>"",
                        'fn_c_p'=>"",
                        'f_f_c_s_g'=>"",
                        'pr_c_p'=>"",
                        'hr_ld_c_p'=>"",
                        'it_c_p'=>"",
                        'it_inf_c_p'=>"",
                        'doc_status'=>"",
                        'doc_status_two'=>"",
                        'ff_doc_updated_by'=>"",
                        's_doc_updated_by'=>"",
                        'status'=>"Hold",
                        'password'=>"123456",
                    ];

                    // add ambassador
                    $add_ambassador = $this->emp_task->add_ambassador( $credentials ); 

                    $response= "Created_wait_for_verify";
                    return response()->json( ['response' => $response,'emp_id'=>$credentials['emp_id']]);
            }
            else{
                return response()->json( ['status' => 0,'error'=>$validator->errors()->toArray()] );
            }
        
    }

    public function send_reg_mail(Request $request)
    {
        # code...
        // send otp mail
        $emp_id = $request->input('emp_id');


        $getempdetail = $this->emp_task->get_employee_detail( $emp_id );

        // To Master Mail 
                $company_email = $getempdetail[0]->email;
                // $company_email ="lakshminarayanan@hemas.in"; 

                $body_content1 = "Dear ".$getempdetail[0]->emp_name;
                $body_content2 = "Your code is: ".$getempdetail[0]->otp;
                $body_content3 = "Kindly use the above Code for CITPL Mail verification..!";
                $body_content4 = "Have any queries please contact our support Team."; 
                $body_content5 = "Support Number : 9087428914"; 
                
                $details = [
                    'subject' => 'CITPL',
                    'title' => 'Your code is: '.$getempdetail[0]->otp,
                    'body_content1' => $body_content1,
                    'body_content2' => $body_content2,
                    'body_content3' => $body_content3,
                    'body_content4' => $body_content4,
                    'body_content5' => $body_content5, 
                ];
                
                // send 2 nd mail method two
                // $footer_img='<img src="https://citpl_alumni.cavinkare.in/assets/img/logo.png" alt="" style="width:90px;">';
                // $footer_th='<p>Thank you</p>';
                // $footer_ad='<b>The Cavinkare Team</b>';

                // $to      = $company_email;
                // $subject = $details['subject'];
                // $message = '<html>
                // <body><p>'.$body_content1."</p>\r\n<h3>".$body_content2."</h3>\r\n<p>".$body_content3."</p>\r\n<p>".$body_content4."</p>\r\n<p>".$body_content5."</p>\r\n".$footer_img."\r\n".$footer_th."\r\n".$footer_ad."</body>
                // </html>";
                // // To send HTML mail, the Content-type header must be set
                // $headers  = 'MIME-Version: 1.0' . "\r\n";
                // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // $headers .= 'From: ambassador@cavinkare.com'. "\r\n" .
                //             'Reply-To: ambassador@cavinkare.com' . "\r\n" .
                //             'X-Mailer: PHP/' . phpversion();
                // mail($to, $subject, $message, $headers);
                // send 2 nd mail method two end
            
                // in proper laravel method mail send plz enable below link
                \Mail::to($company_email)->send(new \App\Mail\RegisterOTP($details));
    }

    public function otp_submit(Request $request)
    {

        $credentials = [
            'name'=>$request->input('name'),
            'emp_id'=>$request->input('emp_id'),
            'pan_num'=>$request->input('pan_num'),
            'dob'=>$request->input('dob'),
            'mobileno'=>$request->input('mobileno'),
            'email'=>$request->input('email'),
            'password'=>$request->input('password'),
            'otp'=>$request->input('otp'),
            'status'=>"Active",
        ];

        $log_check_credentials=[
            'emp_id'=>$request['emp_id'],
            'otp'=>$request['otp'],
            'password'=>$request['otp'],
        ];

        if ( auth()->attempt( $log_check_credentials, true ) ) { 
            // update emp_row
            $get = $this->emp_task->emp_update_after_valid_otp( $credentials );
            return response()->json( ['url'=>url( '/' ), 'response' => 'success'] );
        }
        else{
            $response= "OTP not valid";
            return response()->json( ['response' => $response] );
        }

    }

    public function theme_change(Request $request) {
        $theme_clr=$request->input('theme_clr');
        $file =file_put_contents('theme.txt', $theme_clr);
    }
    public function theme_sidebar_change(Request $request)
    {
        $theme_clr=$request->input('theme_clr');
        $file =file_put_contents('theme_sidebar.txt', $theme_clr);
    }
    public function check_theme_clr() {
        $filename = 'theme.txt';
        if (file_exists($filename)) {
            $file = fopen($filename, "r");
            while(!feof($file)) {
                $txt_con=fgets($file);
            }
            if($txt_con=='light'){
                $have='have';
                $theme='light';
            }
            else if($txt_con=='dark'){
                $have='have';
                $theme='dark';
            }
        }
        else{
            $have='dont_have';
            $theme='';
        }
        $response= "not valid";
        return response()->json( ['have' => $have,'theme' => $theme] );
    }
    public function check_theme_sidebar_clr() {
        $filename = 'theme_sidebar.txt';
        if (file_exists($filename)) {
            $file = fopen($filename, "r");
            $theme="";
            while(!feof($file)) {
                $txt_con=fgets($file);
            }
            if($txt_con=='light'){
                $theme='light';
            }
            else if($txt_con=='dark'){
                $theme='dark';
            }
            $have='have';

        }
        else{
            $have='dont_have';
            $theme='';
        }
        $response= "not valid";
        return response()->json( ['have' => $have,'theme' => $theme] );
    }

    



}
