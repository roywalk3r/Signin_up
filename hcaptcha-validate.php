header('Content-Type: application/json');

$secret = 'your-secret-key';
$verifyResponse = file_get_contents('https://hcaptcha.com/siteverify?secret=' . $secret . '&response=' . $_POST['h-captcha-response']);
$responseData = json_decode($verifyResponse);

if ($responseData->success) {
// The hCaptcha validation passed
// Handle successful form submission here
echo json_encode(array('success' => true));
} else {
// The hCaptcha validation failed
echo json_encode(array('success' => false, 'error' => 'hCaptcha validation failed.'));
}