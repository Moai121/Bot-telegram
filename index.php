 <?php
$token = "5261731576:AAGdSJRelD3k5Hl-Wnkudj1lPdm3W1mVSa4";
$website = 'https://api.telegram.org/bot'.$token;

$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];
$reply = $update["message"]["reply_to_message"]["text"];

if(empty($reply)){
    switch($message) {
        case '/start':
            $response = 'Me has iniciado';
            sendMessage($chatId, $response,FALSE);
            break;
        case '/info':
            $response = 'Hola! Soy @Botnoticiero';
            sendMessage($chatId, $response,FALSE);
            break;
        case '/noticias':
            mostrarnoticias($chatId);
            break;
        case '/categoria':
            $response='Las noticias de su categoria';
            sendMessage($chatId,$response,TRUE);
            break;
        default:
            $response = 'No te he entendido';
            sendMessage($chatId, $response,FALSE);
            break;
        }
    }
    else{
    switch($message){
        case '/economia':
            elegircategoria($chatId,1);
            break;
        case '/deportes':
            elegircategoria($chatId,2);
            break;
        case '/tecnologia':
            elegircategoria($chatId,3);
            break;
        case '/sanidad':
            elegircategoria($chatId,4);
            break;
    }
}

function sendMessage($chatId, $response,$repl) {
    if ($repl == TRUE){ 
        $reply_mark = array('force_reply' => True); 
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($reply_mark).'&text='.urlencode($response); 
   }else{ 
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
    } 
    file_get_contents($url);
}
function mostrarnoticias($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.elperiodico.com/es/rss/rss_portada.xml";
    $xmlstring=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlstring,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo,TRUE);
}
function elegircategoria($chatId,$categoria){
        $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));      
    switch($categoria){
        case 1:
            $url="https://www.elperiodico.com/es/rss/economia/rss.xml";
            
            break;
        case 2:
            $url="https://www.elperiodico.com/es/rss/deportes/rss.xml";
            
            break;
        case 3:
            $url="https://www.elperiodico.com/es/rss/tecnologia/rss.xml";
            
            break;
        case 4:
            $url="https://www.elperiodico.com/es/rss/sanidad/rss.xml";
            
            break;
        }
        $xmlstring=file_get_contents($url,false,$context);
        $xml=simplexml_load_string($xmlstring,"SimpleXMLElement",LIBXML_NOCDARA);
        $json=json_encode($xml);
        $array=json_decode($json,TRUE);

        for($i=0;$i<9;$i++){
            $response=$response."\n\n".$array['channel']['item'][$i]['title'].$array['channel']['item'][$i]['link'];
            sendMessage($chatId,$response,true);
        }       
    }
 ?>