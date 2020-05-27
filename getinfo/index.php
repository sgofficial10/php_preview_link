<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include __DIR__.'/../vendor/autoload.php';
// Use default config as template
$options = \Embed\Embed::$default_config;
// Do some config modifications
$options['min_image_width'] = 60;
$options['min_image_height'] = 60;
$options['html']['max_images'] = 10;
$options['html']['external_images'] = false;
//use env variables
if (is_file(__DIR__.'/../env.php')) {
    include __DIR__.'/../env.php';
    $options['google']['key'] = getenv('GOOGLE_KEY');
    $options['facebook']['key'] = getenv('FACEBOOK_KEY');
}
function getUrl()
{
    if (!isset($_GET['url'])) {
        return '';
    }
    $url = $_GET['url'];
    //fix for unescaped urls
    foreach ($_GET as $name => $value) {
        if ($name === 'url') {
            continue;
        }
        $url .= "&{$name}={$value}";
    }
    return $url;
}
function getEscapedUrl()
{
    return htmlspecialchars(getUrl(), ENT_QUOTES, 'UTF-8');
}
function printAny($text)
{
    if (is_array($text)) {
        printArray($text);
    } else {
        printText($text);
    }
}
function printText($text)
{
    return htmlspecialchars($text, ENT_IGNORE);
}
function printImage($image)
{
    if ($image) {
//         echo <<<EOT
//         <img src="{$image}"><br>
// EOT;
//         printUrl($image);
        return $image;
    }
}
function printUrl($url)
{
    if ($url) {
//         echo <<<EOT
//         <a href="{$url}" target="_blank">Open (new window)</a> | {$url}
// EOT;
        return $url;
    }
}
function printArray($array)
{
    if ($array) {
        return htmlspecialchars(print_r($array, true), ENT_IGNORE);
    }
}
function printHeaders($array)
{
    $headers = [];
    foreach ($array as $name => $values) {
        $headers[$name] = implode(', ', $values);
    }
    printArray($headers);
}
function printCode($code, $asHtml = true)
{
    // if ($asHtml) {
    //     echo $code;
    // }
    if ($code) {
        //echo '<pre>'.htmlspecialchars($code, ENT_IGNORE).'</pre>';
        return htmlspecialchars($code, ENT_IGNORE);
    }
}
$providerData = [
    'title' => 'printText',
    'description' => 'printText',
    'url' => 'printUrl',
    'type' => 'printText',
    'tags' => 'printArray',
    'imagesUrls' => 'printArray',
    'code' => 'printCode',
    'feeds' => 'printArray',
    'width' => 'printText',
    'height' => 'printText',
    'authorName' => 'printText',
    'authorUrl' => 'printUrl',
    'providerIconsUrls' => 'printArray',
    'providerName' => 'printText',
    'providerUrl' => 'printUrl',
    'publishedTime' => 'printText',
    'license' => 'printUrl',
];
$adapterData = [
    'title' => 'printText',
    'description' => 'printText',
    'url' => 'printUrl',
    'type' => 'printText',
    'tags' => 'printArray',
    'image' => 'printImage',
    'imageWidth' => 'printText',
    'imageHeight' => 'printText',
    'images' => 'printArray',
    'code' => 'printCode',
    'feeds' => 'printArray',
    'width' => 'printText',
    'height' => 'printText',
    'aspectRatio' => 'printText',
    'authorName' => 'printText',
    'authorUrl' => 'printUrl',
    'providerIcon' => 'printImage',
    'providerIcons' => 'printArray',
    'providerName' => 'printText',
    'providerUrl' => 'printUrl',
    'publishedTime' => 'printText',
    'license' => 'printUrl',
];
    $dispatcher = new Embed\Http\CurlDispatcher();
    $info = Embed\Embed::create(getUrl(), $options, $dispatcher);
    $finalarray = array();
        foreach ($adapterData as $name => $fn){ 
            $finalarray[$name] = $fn($info->$name);                 
           
        }         
        header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        echo json_encode($finalarray);
?>







