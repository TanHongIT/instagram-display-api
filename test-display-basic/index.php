<?php
// config INSTAGRAM_APP_REDIRECT_URI is test
require_once('../lib/IGDisplayApi.php');

$params = array(
    'get_code' => isset($_GET['code']) ? $_GET['code'] : '',
    'user_id' => ''
);
$ig = new IGDisplayApi($params);

if ($ig->hasUserAccessToken) :
    var_dump($ig->getThisUserAccessToken());
    echo '<br>';
    echo 'Expires in: ' . $ig->getUserAccessTokenExpires() . '= ' .  ceil($ig->getUserAccessTokenExpires() / 86400) . ' days';
else :
?>
    <h1>Instagram Basic Display API</h1>
    <hr />

    <a href="<?php echo $ig->authorizationUrl; ?>">
        Authorize w/Instagram
    </a>
<?php endif; ?>