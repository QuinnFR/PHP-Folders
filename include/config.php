<?php

$token = "1958377342:AAEFm3uVHvabDKSHEz3w_M3MomabsmAaKxo"; // TOKEN of BOT
define('API_KEY',"$token"); // Username of BOT
$admin = "989174330"; // Admin Telegram ID


class Telegram {
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    private function bot($method , $data){
        $url = 'https://api.telegram.org/bot'.$this->token.'/'.$method;
        $ch = curl_init($url);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER , TRUE);
        curl_setopt($ch , CURLOPT_POSTFIELDS , $data);

        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res);
    }



// Include Files
require "functions.php"; // Telegram API Method
require "bot.php.php"; // Bot and Administrator datas
require "keyboards.php"; // Keyboards
require "connect.php"; // DataBase Connect
require "varibles.php"; // Variables [php://input]

// Ini Set [Turn off Display errors and reports]
ini_set('error_reporting', 'off');
ini_set('display_errors', 'off');
ini_set('display_startup_errors', 'off');

// Time [Asia/Tashkent]
date_default_timezone_set('Asia/Tashkent');
$time = date('H:i');
$date = date('d.m.Y');


$input = file_get_contents('php://input');
$update = json_decode($input);
$admin = 000000000;// your id
$token = ''; //bot token
$telegram = new Telegram($token);
$message = $update->message;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$text = $message->text;
$data = $update->callback_query->data;

if($text == '/start'){
    if(!file_exists($lang_path)){
        file_put_contents($lang_path , $lang);
        $lang_btn = json_encode(['inline_keyboard' => [
            [['text' => 'English🇬🇧' , 'callback_data' => 'lang-en']],
            [['text' => 'Persian🇮🇷' , 'callback_data' => 'lang-fa']]
        ]]);
        $telegram->sendMessage($chat_id ,$txt['s_lang'], $lang_btn );
    }
    if($chat_id == $admin){
        $telegram->sendMessage($chat_id , $txt['h_admin']);
    }else{
        $aboutBTn = json_encode(['keyboard' => [
            [ ['text' => $txt['about_btn']] ]
        ],'resize_keyboard' => true]);
        $telegram->sendMessage($chat_id,$txt['h_user'],$aboutBTn);
    }
}elseif($text == $txt['about_btn']){
    $telegram->sendMessage($chat_id , $txt['about']);
}elseif(isset($message) && $chat_id != $admin){
    $infoBtn = json_encode(['inline_keyboard' => [
        [ ['text' => $chat_id.':'.$message_id, 'callback_data' => 'rem'] ]
    ]]);
    $telegram->copyMessage($chat_id , $admin , $message_id,$infoBtn);
    $telegram->sendMessage($chat_id , $txt['m_sent']);
}

if($data != null){
    $userid = $update->callback_query->from->id;
    $mid = $update->callback_query->message->message_id;
    if(strstr($data,'lang-') != false){
        $lang = explode('-',$data)[1];
        $lang_path = "data/$userid.txt";
        file_put_contents($lang_path , $lang);
        $cb_id = $update->callback_query->id;
        $telegram->answerCallbackQuery($cb_id , $txt['lang_changed'],true);
        $telegram->sendMessage($userid , $txt['restart']);
    }
    if($data == 'rem' && $userid == $admin){
        $telegram->edit_replay($userid , $mid,null); 
    }
}

if($chat_id == $admin){
    if(isset($message->reply_to_message->reply_markup)){
        $btn = $message->reply_to_message->reply_markup;
        $text = $btn->inline_keyboard[0][0]->text;
        $ex = explode(':',$text);
        $userid = $ex[0];
        $msg_id = $ex[1];
        $telegram->copyMessage($chat_id,$userid, $message_id,null,$msg_id);
        $telegram->sendMessage($chat_id , $txt['m_sent']);
    }
}




?>
