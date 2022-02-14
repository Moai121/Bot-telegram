<?php
$token = "5261731576:AAGdSJRelD3k5Hl-Wnkudj1lPdm3W1mVSa4";
$website = 'https://api.telegram.org/bot'.$token;

$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];

switch($message) {
    case '/start':
        $response = 'Me has iniciado';
        sendMessage($chatId, $response);
        break;
    case '/info':
        $response = 'Hola! Soy @Botnoticiero';
        sendMessage($chatId, $response);
        break;
    case '/noticias':
        mostrarnoticias($chatId);
        break;
    default:
        $response = 'No te he entendido';
        sendMessage($chatId, $response);
        break;
}

function sendMessage($chatId, $response) {
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
    file_get_contents($url);
}
// function buscarnoticia($chatId,$destino,$inicio,$localicacion){
//     $tokengoogle="AIzaSyAkr2AnND93qyJcRn1TDgR_UwWPdmjQoiA";
//     $url="https://maps.googleapis.com/maps/api/directions/json?origin=".urlencode($inicio)."&destination=".urlencode($destino)."&key=".$tokengoogle;
//     $resultado=file_get_contents($url);
//     $resultado=json_decode($resultado,TRUE);
// }
function mostrarnoticias($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.europapress.es/rss/rss.aspx";
    $xmlsrting=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlsrting,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='>".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo);
}
?>