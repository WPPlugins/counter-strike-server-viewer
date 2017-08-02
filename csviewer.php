<?php
/*
	Plugin Name: csViewer
	Plugin URI: http://rainbow-six3.com/blog/allgemein/counter-strike-server-viewer/
	Description: CS:S and CS:GO Viewer
	Version: 1.3
	Author: Reto Arnold
	URI: http://rainbow-six3.com
	License: GNU GENERAL PUBLIC LICENSE Version 2, June 1991
	Contact: reto@rainbow-six3.com
	*/
add_shortcode('csviewer','showServerInfo');

function showServerInfo($atts ){
	include_once 'csserverinfo.php';
	
	$serverView = new csServerInfo($atts['ip'], $atts['port']);
	$serverInfo = $serverView->getDetails();

	if($serverInfo == 104){
		$html = "Invalid address!";
		return $html;
	}
	
	if($serverInfo == 105){
		$html = "This is not a Counter Strike Serveradress!";
		return $html;
	}
	
	if($serverInfo == 101 or $serverInfo == 102 or $serverInfo == 103){
		$html = "<div ><table class=\"server_data\">\n"; 
		$html .= "<tbody><tr><td class=\"strong\">Servername:</td> <td id=\"address\" class=\"loading last\">Server Offline!</td></tr>\n"; 
		$html .= "<tr><td class=\"strong\">Address:</td> <td id=\"address\" class=\"loading last\">".$atts['ip'].":".$atts['port']."</a></td></tr>\n";
		$html .= "</tbody></table>\n</div><br />";
		return $html;
	} 
	else {
		if($serverInfo['password'] == 1){
			$serverInfo['password'] = 'Yes';
			if(array_key_exists('password', $atts )){
				$serverInfo['password'] = $atts['password'];
				if(array_key_exists('hidepassword', $atts ) and $atts['hidepassword'] == 'true'){
					$serverInfo['password'] = 'Yes';
				}
			}
		}
		else {
			$serverInfo['password'] = 'No password';
		}
		$html = "<div ><table class=\"server_data\">\n"; 
		$html .= "<tbody><tr><td class=\"strong\">Servername:</td> <td id=\"address\" class=\"loading last\">".$serverInfo['hostname']."</td></tr>\n"; 
		$html .= "<tr><td class=\"strong\">Address:</td> <td id=\"address\" class=\"loading last\"><a href=\"steam://connect/".$atts['ip'].":".$atts['port']."/".$atts['password']."\">".$atts['ip'].":".$atts['port']."</a></td></tr>\n";
		$html .= "<tr><td class=\"strong\">Map:</td> <td id=\"map\" class=\"loading last\">".$serverInfo['map']."</td></tr>\n"; 
		$html .= "<tr><td class=\"strong\">Mod:</td> <td id=\"desc\" class=\"loading last\">".$serverInfo['desc']."</td></tr>\n"; 
		$html .= "<tr><td class=\"strong\">Password:</td> <td id=\"password\" class=\"loading last\">".$serverInfo['password']."</td></tr>\n"; 
		$html .= "<tr><td class=\"strong\">Ping:</td> <td id=\"ping\" class=\"loading last\">".$serverInfo['ping']." ms</td></tr>\n"; 
		$html .= "<tr><td class=\"strong\">Players:</td> <td id=\"players\" class=\"loading last\">".$serverInfo['players_on']."/".$serverInfo['players_max']."</td></tr>\n"; 
		$html .= "</tbody></table>\n</div><br />";
		return $html;
	}
}
?>