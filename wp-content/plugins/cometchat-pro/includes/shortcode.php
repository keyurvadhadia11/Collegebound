<?php

// Return Shortcode for WordPress

function getCometChatProShortCode($cometchat_pro_atts)
{

    global $current_user;

    if (is_user_logged_in()) {
        $uid = $current_user->ID;
    } else {
      return '<div id="cometchat">Please login to use this feature.</div>';
    }

    $default_username = removeAllQuotes($cometchat_pro_atts['default-username']);
    $default_id = removeAllQuotes($cometchat_pro_atts['default-id']);
    $default_type = removeAllQuotes($cometchat_pro_atts['default-type']);
    $widget_id = removeAllQuotes($cometchat_pro_atts['widget-id']);
    $widget_version = removeAllQuotes($cometchat_pro_atts['widget-version']);

    $widget_height = removeAllQuotes($cometchat_pro_atts['widget-height']);
    $widget_width = removeAllQuotes($cometchat_pro_atts['widget-width']);

    $widget_docked = removeAllQuotes($cometchat_pro_atts['widget-docked']);
    $widget_docked_position = removeAllQuotes($cometchat_pro_atts['widget-docked-position']);

    $rounded_corners = removeAllQuotes($cometchat_pro_atts['rounded-corners']);

    $app_region = get_option('cometchat_pro_region');
    $app_id = get_option('cometchat_pro_appid');
    $auth_key = get_option('cometchat_pro_authkey');

    if (!empty($default_username)) {
      $default_user = get_userdatabylogin($default_username);
      if($default_user){
           $default_id = $default_user->ID;
      }
    }

    if (empty($widget_version)) {
      $widget_version = 'v1';
    }

    if (empty($widget_docked)) {
      $widget_docked = 'false';
    }

    if (empty($rounded_corners)) {
      $rounded_corners = 'false';
    }

if ($widget_version == 'v2') {

  wp_enqueue_script('cometchat_pro','https://widget-js.cometchat.io/v2/cometchatwidget.js',null,null,false);

  $html = <<<EOD
<div id="cometchat"></div>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  CometChatWidget.init({    
    'appID': '{$app_id}',    
    'appRegion': '{$app_region}',    
    'authKey': '{$auth_key}'  })
  .then(() => {    
      return CometChatWidget.login({      
        'uid': '{$uid}'    }); })
  .then(() => {    
    CometChatWidget.launch({       
      'widgetID': '{$widget_id}',      
      'defaultID': '{$default_id}',      
      'defaultType': '{$default_type}',      
      'target': '#cometchat',      
      'height': '{$widget_height}',      
      'width': '{$widget_width}',      
      'docked': {$widget_docked},     
      'alignment': '{$widget_docked_position}',
      'roundedCorners': '{$rounded_corners}'
    });  
  }).catch();
});
</script>
EOD;

} else {
$html = <<<EOD
<div id="cometchat"></div>
 <script>
  window.cometchatSettings = {
    // Authentication
    'UID': '{$uid}',

    // Default user/group to show
    'defaultID': '{$default_id}', // UID/GUID
    'defaultType': '{$default_type}', // user or group

    // Widget Settings (DO NOT MODIFY)
    'widget': {
      'widgetID': '{$widget_id}',
      'type': 'embed',
      'region': '{$app_region}'
      }
  }
 </script>
 <script src="https://widget-js.cometchat.io/chat.js"></script>
EOD;
}

return $html;

}

function removeAllQuotes($s) {
    $result = preg_replace("/[^a-zA-Z0-9\%\-]+/", "", html_entity_decode($s, ENT_QUOTES));
    return $result;
}

add_shortcode('cometchat-pro', 'getCometChatProShortCode');
