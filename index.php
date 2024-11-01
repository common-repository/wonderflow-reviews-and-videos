<?php

/*
 Plugin Name: Wonderflow
 Plugin URI: http://wonderflow.co
 Description: This plugin publishes automatically high quality reviews and videos on product pages, to sell more.
 Version: 1.0.0-RC1
 Author: Wonderflow.co
 Author URI: http://wonderflow.co/
 */

//require 'mixpanel-php/lib/Mixpanel.php';

add_action( 'wp_head', 'wonderflow_add_script' );
add_action('admin_menu', 'wonderflow_plugin_settings');
register_activation_hook( __FILE__, 'send_alerting_activation_email' );

function send_alerting_activation_email(){

        $admin_email = get_option( 'admin_email' );
        $to='hello@wonderflow.co';
        $subject='Wordpress plugin: someone activated it!';
        $message='Some activated the wordpress plugin. ';
        $message.='His email is: '.$admin_email;

        require_once(ABSPATH . WPINC . '/class-phpmailer.php');
        require_once(ABSPATH . WPINC . '/class-smtp.php');

        $phpmailer = new PHPMailer();
        $phpmailer->SMTPAuth   = true;                  // enable SMTP authentication

        $phpmailer->Username = 'postmaster@sandbox0e8aa8caffdd4a7cb490665e35726536.mailgun.org';
        $phpmailer->Password = '41z40koojrm3';

        $phpmailer->IsSMTP(); // telling the class to use SMTP
        $phpmailer->Host       = "smtp.mailgun.org"; // SMTP server
        $phpmailer->SMTPSecure = "tls";                 // sets the prefix to the servier
        $phpmailer->Port       = 587; 

        $phpmailer->SetFrom('wordpress@wonderflow.co', 'Wordpress Plugin');
        $phpmailer->FromName   = 'wordpress@wonderflow.co';
        $phpmailer->Subject    = $subject;
        $phpmailer->Body       = $message;                      //HTML Body
        $phpmailer->AddAddress($to, 'Wordpress plugin');
        error_log('Before sending email');

        if($phpmailer->Send()) {
                error_log("Message sent!");
        } else {
                error_log("Mailer Error: " . $phpmailer->ErrorInfo);
        }
}


function wonderflow_plugin_settings() {
    add_menu_page('Wonderflow', 'Wonderflow Settings', 'administrator', 'wonderflow_settings', 'wonderflow_display_settings');
}

function wonderflow_display_settings() {

    //$api_key=(get_option('wonderflow_api_key') != '') ? get_option('wonderflow_api_key') : '';

    $close_pre='</pre>';
    $open_pre='<pre>';

    $title='<h2 style="font-size:22pt;">Select your settings</h2>';

    $content="<p style='font-size:14pt;'>Click on the button on the left side of the sales pages to display Wonderflow's widget.</p>";

    $content.="Displaying our widget inside your design is also possible. Drop us a line at <a href='mailto:hello@wonderflow.co'>hello@wonderflow.co</a> and we will take care of you.";

//    $content.="<p style='font-size:14pt;'>Videos and text reviews from Wonderflow can also be configured to be displayed directly inside the page (like a normal div in the page). To do it you have to contact us at <a href='mailto:hello@wonderflow.co'>hello@wonderflow.co</a> so we can easily configure for you the widget in the most offective way. Is requires just few minutes. We don't let you do it to reduce the number of steps you need to switch Wonderflow content on</p>";

    $image_path='http://ps.w.org/wonderflow-reviews-and-videos/assets/screenshot-1.png?rev=910434';
    $content.="<p>Check this example and let us know how do you like it!<br><img style='width:30%;' src='".$image_path."'/></p>";

    $html=$close_pre.$title.$content.$open_pre;

    echo $html;

}




function wonderflow_add_script() {
	$script = '
			<script id="wonderflow-script">			
			(function(){
			    var myscript = document.createElement("script");
			    var url = "http://wonderflow.co/demo/private/wordpress.1";
			    myscript.type = "text/javascript";
			    myscript.src = "http://wonderflow.co/script/get?url=" + url;
			    myscript.async = true;
			    var s = document.getElementById("wonderflow-script");
			    s.parentNode.insertBefore(myscript, s);				

			})();
			</script>			
	';
 
	echo $script;

	//echo '<link rel="stylesheet" type="text/css" href="https://s3-eu-west-1.amazonaws.com/europe.flowsby.com/widget-plugin/widget-plugin-style.min.css"></link>';
//	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/widget-plugin-style.min.css"></link>';
	echo '<link rel="stylesheet" type="text/css" href="'.plugins_url('widget-plugin-style.min.css',__FILE__).'"></link>';
//plugins_url( 'images/wordpress.png' , __FILE__ )
}
