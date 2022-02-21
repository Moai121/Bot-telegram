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
            $response = 'Hola! Soy @Botnoticiero, si quieres saber sobre mi, te recomiendo que pruebes comandos tales como /noticias o /menu ';
            sendMessage($chatId, $response,FALSE);
            break;
        case '/atencion':
            $response='Hola, como te encuentras? Necesitas hablar?';
            sendMessage($chatId,$response,TRUE);
        case '/noticias':
            mostrarnoticias($chatId);
            break;
        case '/sanidad👩‍🔬':
            $response='Las noticias sobre sanidad';
            sanidad($chatId);
            break;
        case '/deportes🏓':
            $response='Las noticias sobre deportes';
            deportes($chatId);
            break;
        case '/economia💰':
            $response='Las noticias sobre economia';
            economia($chatId);
            break;
        case '/tecnologia📡':
            $response='Las noticias sobre tecnologia';
            tecnologia($chatId);
            break;
        case '/menu':
            $keyboard = array('keyboard' =>
            array(array(
            array('text'=>'/tecnologia📡','callback_data'=>"1"),
            array('text'=>'/sanidad👩‍🔬','callback_data'=>"2"),
            array('text'=>'/economia💰','callback_data'=>"3")
            ),
            array(
                array('text'=>'/deportes🏓','callback_data'=>"4")
            )), 'one_time_keyboard' => false, 'resize_keyboard' => true
            );

            file_get_contents('https://api.telegram.org/bot5261731576:AAGdSJRelD3k5Hl-Wnkudj1lPdm3W1mVSa4/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($keyboard).'&text=Cargando...');
            break;
        default:
            $response = 'No te he entendido';
            sendMessage($chatId, $response,FALSE);
            break;
        }
    }
else{
            switch($message){
                case 'economia':   
                    economia($chatId,false);
                    break;
                case 'deportes':   
                    deportes($chatId,false);
                    break;
                case 'sanidad':   
                    sanidad($chatId,false);
                    break;
                case 'tecnologia':   
                    tecnologia($chatId,false); 
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
    sendMessage($chatId,$titulo,FALSE);
}
function deportes($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.elperiodico.com/es/rss/deportes/rss.xml";
    $xmlstring=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlstring,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo,TRUE);
}
function sanidad($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.elperiodico.com/es/rss/sanidad/rss.xml";
    $xmlstring=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlstring,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo,TRUE);
}
function tecnologia($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.elperiodico.com/es/rss/tecnologia/rss.xml";
    $xmlstring=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlstring,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo,TRUE);
}
function economia($chatId){
    $context=stream_context_create(array('http'=>array('header'=>'Accept:application/xml')));
    $url="https://www.elperiodico.com/es/rss/economia/rss.xml";
    $xmlstring=file_get_contents($url,false,$context);
    $xml=simplexml_load_string($xmlstring,"SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE); 

    for($i=0;$i <9;$i++){
        $titulo=$titulo."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'>+info</a>";
    }
    sendMessage($chatId,$titulo,TRUE);
}
 ?>