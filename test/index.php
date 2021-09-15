<?php
require_once('../lib/IGDisplayApi.php');

$accessToken = 'ACCESS-TOKEN';

$params = array(
    'get_code' => isset($_GET['code']) ? $_GET['code'] : '',
    'access_token' => $accessToken,
    'user_id' => 'USER-ID'
);
$ig = new IGDisplayApi($params);
?>

<h1>Instagram Basic Display API</h1>
<hr />

<a href="<?php echo $ig->authorizationUrl; ?>">
		Authorize w/Instagram
</a>