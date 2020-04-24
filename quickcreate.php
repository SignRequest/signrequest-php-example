<?php

// Install the SignRequest php library from https://github.com/SignRequest/signrequest-php-client
// 
// use as: 
// $ export SR_API_TOKEN=your_api_token
// $ php quickcreate.php from_signer_email@example.com to_signer_email@example.com

require_once(__DIR__ . '/vendor/autoload.php');

function isCli(){
    return php_sapi_name() == "cli";
}

function returnResult($result){
    if (isCli()){
        print_r($result);
    } else {
        header("Content-type: application/json; charset=utf-8");
        if (is_string($result)){
            $data = array('message' => $result);
        } else {
            $data = $result."";  // this serializes the swagger codegen object to json
        }
        echo $data;
    }
    exit;
}

$token = getenv('SR_API_TOKEN');
if (empty($token)){
    returnResult('SR_API_TOKEN environment variable is not set. use as:\n SR_API_TOKEN=your_token php quickcreate.php');
}

if (isCli()) {
    // In cli-mode
    $from_email = $argv[1];
    $signer_email = $argv[2];
    if (empty($from_email) || empty($signer_email)){
        returnResult('Signer emails not set, use as:\n php quickcreate.php from_signer_email@example.com to_signer_email@example.com');
    }
} else {
    // Not in cli-mode
    $from_email =  isset($_POST["from_email"]) ? $_POST["from_email"] : null;
    $signer_email =  isset($_POST["signer_email"]) ? $_POST["signer_email"] : null;
    if (empty($from_email) || empty($signer_email)){
        returnResult("from_email and signer_email not given!");
    }
}

// Configure API key authorization: Token
$config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $token);
$config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');

$apiInstance = new SignRequest\Api\SignrequestQuickCreateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$data = new \SignRequest\Model\SignRequestQuickCreate();

$signer = new \SignRequest\Model\Signer();
$signer->setEmail($signer_email);
// Use embedded signing 
// embed_url_user_id has to be set in order to get back an embed_url for this signer, see: https://signrequest.com/api/v1/docs/#section/Additional-signing-methods/Embed-url
$signer->setEmbedUrlUserId('some_external_id');
// set redirect urls
$signer->setRedirectUrl('https://www.google.com/search?q=signrequest%20signed');  // a url to go when signed
$signer->setRedirectUrlDeclined('https://www.google.com/search?q=signrequest%20declined');  // a url to go when declined
// force english language
$signer->setLanguage('en');
$signer->setForceLanguage(true);
$data->setSigners(array($signer));

// Prefill the signer name tag that has the id 'example_signer_name'
$prefill_tag = array('external_id' => 'example_signer_name', 'text' => 'Some Signer Name');
$data->setPrefillTags(array($prefill_tag));

// Change / set the document name
$data->setName('Example Contract');
// Set the document content as base64 encoded html (preferably application create pdf's themselves for ultimate control on layouts!)
$html = file_get_contents('./doc.html');
$data->setFileFromContent(base64_encode($html));
$data->setFileFromContentName('example.html');  // must be .html to tell SignRequest this is an html file
// set the sender email
$data->setFromEmail($from_email);

try {
    $result = $apiInstance->signrequestQuickCreateCreate($data);
    returnResult($result);
} catch (Exception $e) {
    echo 'Exception when calling SignrequestQuickCreateApi->signrequestQuickCreateCreate: ', $e->getMessage(), PHP_EOL;
}
