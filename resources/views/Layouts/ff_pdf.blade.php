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
        .heading_css{
        text-decoration: underline;
        text-align:center;
        margin-top:10px;
        }
        .heading_css1{
        text-decoration: underline;
        text-align:center;
        margin-top:25px;
        margin-bottom:-10px;
        }
        table, th, td {
            font-size: 13px;
        }
        .head{
            border: 1px solid black;
            color:black;
            font-size: 20px;
            border-bottom:none;
        }
       
    </style>

</head>
 <body>
 
    <div class="head" >
        <h5 style="margin-left:10px;text-align:left;padding-top:-30px;" ><span>FULL AND FINAL SETTLEMENT</span><span><img  height="40" style="margin: -40px 0px 10px 560px"  src="{{public_path()."/assets/img/logo.png"}}"></span></h5>
    </div>
        <div  style="border: 1px solid black;margin-top:-28px;padding:10px 10px;color:black">
            <table width="100%">
                <tr>
                    <td>Name</td>
                    <td style="font-weight:bold"><span>: </span>{{$emp_profile_tbls[0]->emp_name}}</td>
                    <td >Date of Joining</td>
                    <td ><span>: </span>{{$doj}}</td>
                </tr>
                <tr>
                    <td>Personnel No</td>
                    <td ><span>: </span>{{$emp_profile_tbls[0]->emp_id}}</td>
                    <td>Date of Resignation</td>
                    <td  ><span>: </span>{{$dor}}</td>
                </tr>
                <tr>
                    <td>Designation</td>
                    <td><span>: </span>Senior Technical Trainer-Technical</td>
                    <td>Date of last Working</td>
                    <td  ><span>: </span>{{$lwd}}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td><span>: </span>{{$get_ff_tracker_data[0]->work_location}}</td>
                    <td>Basic</td>
                    <td ><span style="font-family: DejaVu Sans; sans-serif;">: ₹ </span>{{number_format(round(intval($get_ff_tracker_data[0]->basic)/12))}}</td>
                </tr>
                    <tr>
                    <td></td>
                    <td></td>
                    <td>Gross</td>
                    <td  ><span style="font-family: DejaVu Sans; sans-serif;">: ₹ </span><span>{{number_format($gross)}}</span></td>
                </tr>
            </table>

            <h6 class="heading_css">EARNINGS</h6>

            <table width="100%">
                <tr>
                    <td width="25%">{{$my}} Net Pay</td>
                    <td width="25%">&nbsp;</td>
                    <td width="25%">&nbsp;</td>
                    <td width="25%" style="text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">₹ </span><span>{{number_format($net_pay)}}</span></td>
                </tr>
            </table>

            <h6 class="heading_css1">EARNINGS TOTAL</h6><h6 style="text-align:right;margin-top:-50px;font-size:15px"><span style="font-family: DejaVu Sans; sans-serif;">₹ </span><span id="e_total">{{number_format($net_pay)}}</span></h6>
            <h6 class="heading_css">DEDUCTIONS</h6>
            <table width="100%">
                <tr>
                    <td width="75%">Notice Period Recoverable</td>
                    <td width="2%">&nbsp;</td>
                    <td width="3%">&nbsp;</td>
                    <td width="20%" style="text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">₹ </span><span>{{number_format($rec_data[0])}}</span></td>
                </tr>
                <tr>
                    <td width="75%">Other Recovery (For Travel AP Training)</td>
                    <td width="2%">&nbsp;</td>
                    <td width="3%">&nbsp;</td>
                    <td width="20%" style="text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">₹ </span><span >{{number_format($rec_data[1])}}</span></td>
                </tr>
            </table>

            <h6 class="heading_css1">DEDUCTIONS TOTAL</h6><h6 style="text-align:right;margin-top:-50px;font-size:15px"><span style="font-family: DejaVu Sans; sans-serif;">₹ </span><span>{{number_format($deduction_total)}}</span></h6>
            <table width="100%" style="margin-top:-20px">
                <tr>
                    <td style="font-weight:bold;font-size:15px" width="75%">Amount to be given to Employee</td>
                    <td width="2%">&nbsp;</td>
                    <td width="3%">&nbsp;</td>
                    <td width="20%" style="text-align:right;font-weight:bold;font-size:20px;"><span style="font-family: DejaVu Sans; sans-serif;">₹ </span><span>{{number_format($total_amount)}}</span></td>
                </tr>
            </table>
            <img   height="100" style="margin-left:562px;margin-top:5px" src="{{public_path()."/assets/img/sign.png"}}">
        </div >
    </body>
</html>

<script>
   

</script>