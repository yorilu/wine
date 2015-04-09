<?php
$WINE_CONFIG = array(
    "APPID" => "wx1a608431d172695b",
    "APP_KEY" => "0f6e0f0d13f6c31b28a2b7a72963fe15"
);

$WINE_CONFIG["TOKEN_URL"] = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$WINE_CONFIG["APPID"]."&secret=".$WINE_CONFIG["APP_KEY"];

$WINE_CONFIG["TICKEY_URL"] = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=";