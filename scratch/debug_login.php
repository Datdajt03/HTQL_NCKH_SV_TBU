<?php
$ch = curl_init('http://localhost/web1/HTQL_NCKH_SV_TBU/index.php?action=login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['username' => 'SV001', 'password' => '123456']));
$res = curl_exec($ch);
echo $res;
