 <?php

echo "hola";
$token = "5261731576:AAGdSJRelD3k5Hl-Wnkudj1lPdm3W1mVSa4";
$website = 'https://api.telegram.org/bot'.$token;

$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];
//$reply=$update["message"]["reply_to_message"]["text"];

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
        mostrarnoticias($chatId,$response);
        break;
    case '/categoria':
        elegircategoria($chatId,$response,TRUE);
        break;
    default:
        $response = 'No te he entendido';
        sendMessage($chatId, $response);
        break;
}

function sendMessage($chatId, $response,$repl) {
    // if ($repl == TRUE){ 
    //     $reply_mark = array('force_reply' => True); 
    //     $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($reply_mark).'&text='.urlencode($response); 
   // }else{ 
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
    //} 
    file_get_contents($url);
}
function mostrarnoticias($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.elperiodico.com/es/rss/rss_portada.xml";
    $xmlsrting=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlsrting,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo,TRUE);
}
function elegircategoria($chatId,$response,$repl){
    echo "timidin";
        $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml'))); 
        $url = "https://www.elperiodico.com/es/rss/".$response."/rss.xml"; 
        $xmlsrting=file_get_contents($url,false,$context);
        $xml=simplexml_load_string($xmlsrting,"SimpleXMLElement",LIBXML_NOCDARA);
        $json=json_encode($xml);
        $array=json_decode($json,TRUE);

        for($i=0;$i<9;$i++){
            $categoria=$categoria."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";;
        }
        
        sendMessage($chatId,$categoria,TRUE); 
    }
 ?>