<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<?php
//---API Curl HTTPGET
/*
$date = date("ydm");
$time = date("Hi");
$token = md5($date.'T'.$time);
$data = array(
    'startDateString' => '20220601',
    'endDateString' => '20220610',
    'token' => $token ,
    'request_time' => $time
);
//$data = array(
 //   'token' => $token ,
  //  'request_time' => $time
//);

//$endpoint = "http://36.94.119.139:5100/api/bpkb/getColourDataList";

$endpoint = "http://36.94.119.139:5100/api/bpkb/getAccountDataList";
$url = $endpoint . '?' . http_build_query($data);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
$details = json_decode($resp);
curl_close($curl);
echo "<pre>";
print_r($details);
echo "</pre>";
*/
?>

<?php
/*
$token = md5('221106T1751');
$data = array(
    'token' => 'f63880fdfce453b3463fdba9ee25f0a7' ,
    'request_time' => '1853'
);
 
// Convert the PHP array into a JSON format
$payload = json_encode($data);
 
// Initialise new cURL session
$ch = curl_init('http://36.94.119.139:5100/api/bpkb/getDealerDatalist');

// Return result of POST request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Get information about last transfer
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

// Use POST request
curl_setopt($ch, CURLOPT_POST, true);
$info = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Set payload for POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
// Set HTTP Header for POST request 
/*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload))
);
 
// Execute a cURL session
$result = curl_exec($ch);
 
// Close cURL session
curl_close($ch);
$result = json_decode($result, TRUE);   

// menampilkan hasil curl
echo "<pre>";
print_r($info);
echo "</pre>";
*/
?>

<?php 
//---API curl HTTPPOST
/*
$data = array(
    'UserCode'=>'backup',
    'UserPassword'=>'bfdf97ae01c73d83a58ec41f78a4291f',
    'BranchCode'=>'SYM',
    'DealerCode'=>'SYSYM'
);
 
// Convert the PHP array into a JSON format
$payload = json_encode($data);
 
// Initialise new cURL session
$ch = curl_init('http://36.94.119.139:4000/user/authenticate');

// Return result of POST request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Get information about last transfer
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

// Use POST request
curl_setopt($ch, CURLOPT_POST, true);

// Set payload for POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
// Set HTTP Header for POST request 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload))
);
 
// Execute a cURL session
$result = curl_exec($ch);
$info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// Close cURL session
curl_close($ch);
//$result = json_decode($result, TRUE);   

// menampilkan hasil curl
echo "<pre>";
print_r($info);
echo "</pre>";
*/
?>
<section class="content-header">
    <h1><?=lang("Dashboard")?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Menus") ?></a></li>
		<li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">
<!-- Info boxes -->
<div class="row">
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-green"><i class="ion-archive"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Download</span>
        <span class="info-box-number">1</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-aqua"><i class="ion-checkmark-circled"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Success</span>
        <span class="info-box-number">2</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->

<!-- fix for small devices only -->
<div class="clearfix visible-sm-block"></div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-yellow"><i class="ion ion-load-b"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">On Progress</span>
        <span class="info-box-number">3</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
    <span class="info-box-icon bg-red"><i class="ion-alert-circled"></i></span>

    <div class="info-box-content">
        <span class="info-box-text">Failed</span>
        <span class="info-box-number">4</span>
    </div>
    <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
