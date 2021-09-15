<?php
require_once('../lib/IGDisplayApi.php');

$accessToken = 'ACCESS-TOKEN';

$params = array(
    'get_code' => isset($_GET['code']) ? $_GET['code'] : '',
    'access_token' => $accessToken,
    'user_id' => 'USER-ID'
);
$ig = new IGDisplayApi($params);

if ($ig->hasUserAccessToken) : 
var_dump($ig->getThisUserAccessToken());
?>

<?php endif; ?>