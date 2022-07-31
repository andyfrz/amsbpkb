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

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{ttlNeedApproval}</h3>
                <p><?=lang("Need Approval")?></p>
            </div>
            <div class="icon">
                <i class="ion-checkmark-circled"></i>
            </div>
            <a href="<?= site_url() ?>trx/approval" class="small-box-footer">More detail <i class="fa fa-pencil" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
</section>
