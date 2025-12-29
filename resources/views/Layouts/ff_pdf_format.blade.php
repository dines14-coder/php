<!DOCTYPE html>
<html lang="en">


<!-- tabs.html  21 Nov 2019 03:54:41 GMT --> 
<head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{$website_name}}</title>
<style>
    .tbl_style th {
        background-color: rgb(226,239,217);
    }
    .tbl_style2 th {
        background-color:rgb(216,216,216);
    }
    .tbl_style_c th ,.tbl_style_c td{
        color: black;
    }
    .tbl_style1 th ,.tbl_style1 td {
        background-color: rgb(47,84,150);
        color: white;
    }
    .common_tbl th,.common_tbl td{
        padding: 1px 20px;
        margin: 0px;
        font-family: 'Arial';
        font-size: 11px;
    }
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    .border_td td{
        border: none;
    }

    .container-fluid{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}
    .row{display:-ms-flexbox;display:-webkit-box;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}
    .col-md-4{-ms-flex:0 0 33.333333%;-webkit-box-flex:0;flex:0 0 33.333333%;max-width:33.333333%}
    .col-md-6{-ms-flex:0 0 50%;-webkit-box-flex:0;flex:0 0 50%;max-width:50%}
    .col-md-8{-ms-flex:0 0 66.666667%;-webkit-box-flex:0;flex:0 0 66.666667%;max-width:66.666667%}
    .col-md-12{-ms-flex:0 0 100%;-webkit-box-flex:0;flex:0 0 100%;max-width:100%}
    .text-center{text-align:center !important}
    .text-danger{color:#dc3545 !important}
    .text-right{text-align:right !important}
    .mt-3,.my-3{margin-top:1rem !important}
    .text-dark{color:#343a40 !important}a.text-dark:focus,a.text-dark:hover{color:#121416 !important}
    .float-left{float:left !important}
    .mb-2,.my-2{margin-bottom:.5rem !important}
    .p-2{padding:.5rem !important}
    .float-right{float:right !important}
</style>

</head>

<body>
   
    



<div id="s_g_5_field"   width="100%" height="100%">

 <div class="row">



        <div class="col-md-6" >
            <b style="border-style: solid;border-width: 1.7px; background-color:rgb(216,216,216);font-size:18px" class="text-dark float-left mb-5  p-2 ">{{$emp_profile_tbls[0]->emp_id}}</b>
        </div>
        <div class="col-md-6" style="margin-left:460px">
            <b style="border-style: solid;border-width: 1.7px; background-color:rgb(216,216,216);font-size:18px;" class="text-dark float-right mb-2  p-2">{{$emp_profile_tbls[0]->emp_name}}</b>
        </div>
        <div class="col-md-12" >
            <table width="100%" style="margin-top:50px" class="tbl_style2 tbl_style_c  common_tbl border_td">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center p-2" style="font-size:20px" colspan="4">Full & Final Settlement</th>
                    </tr>
                </thead>
                <tbody >
                    <tr><td>Employee Code&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">{{$emp_profile_tbls[0]->emp_id}}<td >Employee Status&nbsp;<td >PROBATION</td></td></tr>
                    <tr><td>Employee Name&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">{{$emp_profile_tbls[0]->emp_name}}</td><td >Notice Period to be served(In days)&nbsp;<td >15</td></td></tr>
                    <tr><td>Date of Joining&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">{{$doj}}</td><td >Notice Period Served(In days)&nbsp;<td >{{$nps1}}</td></td></tr>
                    <tr><td>Date of Resignation&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">{{$dor}}</td><td >Notice Period Recovery(In days)&nbsp;<td >{{$npr}}</td></td></tr>
                    <tr><td>Last Date of Working&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">{{$lwd}}</td><td >PL Balance(In days)&nbsp;<td >0</td></td></tr>
                    <tr><td>Group DOJ&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">-</td><td >Location&nbsp;<td >{{$get_ff_tracker_data[0]->work_location}}</td></td></tr>
                    <tr><td>Salary processed till&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">-</td><td >Grade&nbsp;<td >{{$get_ff_tracker_data[0]->grade}}</td></td></tr>
                    <tr><td>Band&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">{{$get_ff_tracker_data[0]->grade_set}}</td><td >Total Experience&nbsp;<td >{{$experience}}</td></td></tr>
                    <tr><td>Division&nbsp;</td><td style="border-right: 1px solid black;height: 14px;">Professional Care</td><td >Designation&nbsp;<td >Senior Technical Trainer</td></td></tr>
                </tbody>
            </table>
        </div>
       
        <div class="col-md-12" >
            <table width="100%" class="tbl_style2 tbl_style_c common_tbl border_td">
                <thead>
                    <tr>
                        <th>Salary Earnings</th>
                        <th>Rate Salary</th>
                        <th>SAP Salary Processed for the month</th>
                        <th>Salary Deduction</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
       
                    <tr><td>Basic Pay</td><td >{{round(intval($get_ff_tracker_data[0]->basic)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Provident Fund</td><td>0</td></tr>
                    <tr><td>House Rent Allowance</td><td>{{round(intval($get_ff_tracker_data[0]->hra)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>ESI</td><td>0</td></tr>
                    <tr><td>Dearness Allowance</td><td>{{$get_ff_tracker_data[0]->da}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>P TAX</td><td>0</td></tr>
                    <tr><td>Additional HRA</td><td>{{round(intval($get_ff_tracker_data[0]->addl_hra)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Income Tax</td><td>0</td></tr>
                    <tr><td>Conveyance Allowance</td><td>{{round(intval($get_ff_tracker_data[0]->conveyance)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>LWF</td><td>0</td></tr>
                    <tr><td>Special Allowance</td><td>{{round(intval($get_ff_tracker_data[0]->spl_allowance)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Canteen Deduction</td><td>0</td></tr>
                    <tr><td>Medical Allowance</td><td>{{round(intval($get_ff_tracker_data[0]->medical)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Product Subsidiary</td><td>0</td></tr>
                    <tr><td>Other Allowance 1</td><td>{{round(intval($get_ff_tracker_data[0]-> _allowance)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Telephone Deduction</td><td>0</td></tr>
                    <tr><td>LTA</td><td>{{round(intval($get_ff_tracker_data[0]->lta)/12)}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Parental Insurance</td><td>0</td></tr>
                    <tr><td>Fixed Veh Allowance</td><td>0</td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Loan</td><td>0</td></tr>
                    <tr><td>Sales Incentive</td><td>0</td><td style="border-right: 1px solid black;height: 14px;">0</td><td></td><td></td></tr>
                    <tr><td>Attendance Bonus</td><td>0</td><td style="border-right: 1px solid black;height: 14px;">0</td><td></td><td></td></tr>
                    <tr><td>Over Time</td><td>{{$get_ff_tracker_data[0]->ot}}</td><td style="border-right: 1px solid black;height: 14px;">0</td><td></td><td></td></tr>
                    <tr><td>Referral Bonus</td><td>0</td><td style="border-right: 1px solid black;height: 14px;">0</td><td></td><td></td></tr>
                    <tr style="font-weight: bold;border: 1px solid black;"><td>Gross Earning Total (A)</td><td >47016</td><td style="border-right: 1px solid black;height: 14px;">4702</td><td>Gross Deduction Total (B)</td><td>180</td></tr>
                    <tr style="font-weight: bold; border: 1px solid black;"><td style="border-right: 1px solid black;height: 14px;">April-2022 Net Pay </td><td>TOTAL(C) = A-B</td><td style="border-right: 1px solid black;height: 14px;"></td><td>4522</td><td>Salary Hold/Release</td></tr>
                    <tr style="font-weight: bold; border: 1px solid black;"><td>OTHER EARNINGS</td><td>Days</td><td>Amount</td><td>OTHER DEDUCTIONS</td><td>Amount</td></tr>
                    <tr><td>Leave Encashment</td><td></td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Notice Period Recoverable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>12</span></td><td>5417</td></tr>
                    <tr><td>Graduity</td><td></td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Income Tax (FFS</td><td>0</td></tr>
                    <tr><td>Travel Conveyance</td><td></td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Imprest Amount</td><td>0</td></tr>
                    <tr><td>Bonus & Sales Incentives</td><td></td><td style="border-right: 1px solid black;height: 14px;">0</td><td>Other Recovery (For Travel AP Training)</td><td>{{$rec_data[0]}}</td></tr>
                    <tr><td>Apr-22 Net Pay</td><td></td><td style="border-right: 1px solid black;height: 14px;">12037</td><td></td><td></td></tr>
                    <tr style="font-weight: bold; border: 1px solid black;"><td>Other Earnings Total (C)</td><td></td><td style="border-right: 1px solid black;height: 14px;">12037</td><td>Other DeductionTotal (D)</td><td>10417</td></tr>
                    <tr style="font-weight: bold; border: 1px solid black;"><td>TOTAL (F) = C + D - E</td><td></td><td style="border-right: 1px solid black;height: 14px;"></td><td style="border-right: 1px solid black;height: 14px;">Payable/Recoverable</td><td>1620</td></tr>
                </tbody>
            </table>
        </div>
         <div class="col-md-4 mt-3 margin_tbl" >
            <table class="tbl_style1 common_tbl" width="100%">
                <tr>
                    <th><b class="text-danger">*</b>Enter Manually</th>
                    <th>Days</th>
                </tr>
                 <tr>
                    <td>Actual No of Days</td>
                    <td id="actual_no_days">30</td>
                </tr>
                <tr>
                    <td>No of days Processed in SAP</td>
                    <td>0</td>
                </tr>
                 <tr>
                    <td>Actual No of Days to be Processed</td>
                    <td id="p_actual_no_days">2</td>
                </tr>
            </table>
        </div>
        <div class="col-md-8">
            <h6 class="text-center text-dark " style="margin-left:85px;margin-bottom:-0.5px">01-Jan-22</h6>
            <table class="tbl_style tbl_style_c common_tbl"  width="100%">
              
                <tr>
                    <th >Leave eligible per Annum</th>
                    <th id="leave_annum_pl" >14</th>
                    <th id="leave_annum_sl" >12</th>
                    <th id="leave_annum_cl" >12</th>
                </tr>
                 <tr>
                    <td><b>Leave Type</b></td>
                    <td>PL</td>
                    <td>SL</td>
                    <td>CL</td>
                </tr>
                <tr>
                    <td>Cal Yr 2017 Balance</td>
                    <td id="yr_bal_pl">0</td>
                    <td id="yr_bal_sl">0</td>
                    <td id="yr_bal_cl">0</td>
                </tr>
                <tr>
                    <td>Leave Balance as on Today in portal</td>
                    <td id="leave_bal_pl">0</td>
                    <td id="leave_bal_sl">0</td>
                    <td id="leave_bal_cl">0</td>
                </tr>
                 <tr>
                    <td>Actual Leave eligible from 1/1/22 to LWD</td>
                    <td id="actual_pl_leave">3.81</td>
                    <td id="actual_sl_leave">0.00</td>
                    <td id="actual_cl_leave">0.00</td>
                </tr>
                 <tr>
                    <td>Total Eligible Leave</td>
                    <td id="pl_total"></td>
                    <td id="sl_total"></td>
                    <td id="cl_total"></td>
                </tr>
                 <tr>
                    <td><b>Leave Encahment</b></td>
                    <td id="leave_enc"></td>
                    <td><b>Total Leave</b></td>
                    <td></td>
                </tr>
            </table>
            <table width="70%"  class="mt-3  common_tbl tbl_style_c">
                <tr>
                    <th style="{{$color}}" >{{$gt_tl}}</th>
                    <th>{{$gt}}</th>
                </tr>
            </table>

             <table class="mt-3 common_tbl tbl_style_c" width="50%">
                <tr>
                    <th   style="background-color:rgb(255,217,101);">Actual Salary Processed for the month</th>
                    <th   style="background-color:rgb(255,217,101);">Excess Process</th>
                </tr>
                <tr>
                    <td class="sap_basic">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_house">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_da">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_a_hra">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_ca">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_sa">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_ma">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_oa">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_lta">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_fva">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_si">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_ab">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_ot">0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td class="sap_rb">0</td>
                    <td>0</td>
                </tr>
                <tr style="background-color:rgb(255,217,101);">
                    <td class="t_sap_salary">0</td>
                    <td>0</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>

</html>
