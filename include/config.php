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



?>
