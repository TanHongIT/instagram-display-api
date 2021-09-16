<?php
require_once('../lib/IGDisplayApi.php');
require_once('../config/config.php');

$accessToken = 'ACCESS_TOKEN';

$params = array(
    'get_code' => isset($_GET['code']) ? $_GET['code'] : '',
    'access_token' => $accessToken,
    'user_id' => USER_ID
);
$ig = new IGDisplayApi($params);
?>
<?php
if ($ig->hasUserAccessToken) :
    var_dump($ig->getThisUserAccessToken());
    echo '<br>';
    echo 'Expires in: ' . $ig->getUserAccessTokenExpires() . '= ' .  ceil($ig->getUserAccessTokenExpires() / 86400) . ' days';
endif; 

if ($ig->hasUserAccessToken) :
    $user = $ig->getUser();
    $userMedia = $ig->getUsersMedia();
?>
    <pre>
		<?php print_r($user); ?>
	</pre>
    <pre>
		<?php print_r($userMedia); ?>
	</pre>
<?php endif; ?>