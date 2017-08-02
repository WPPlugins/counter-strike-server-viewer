=== CsViewer ===
Contributors: rainbow-six3
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KDU4MTC2EEZPU
Tags: cs,css, counter strike, server, serverview, serverinformation, information
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


With these Plugin you can display your any Source Server(example Counter Strike Server) information on your Wordpress-Hompage.



== Description ==

With these Plugin you can display any Source Server(Couter Strike, Garry's Mod, Team Fortress, .. ) on your Wordpress-Hompage.

It is easy to activate. You must only Downlaod the Plugin and place the String '[csviewer ip="mydomain.com" port="26015"]' 
on your Wordpress Page or Article,

You can change the appearance by using the CSS Tag table.server_data.

More Info: 
[http://rainbow-six3.com/?p=472]



Function:
Auto-connect by clicking on the IP. (steam Protocol must be activated)

Display following Information:

* Servername

* Serveradress (ip/hostname and port)

* Map

* Mod (CS:S or CS:GO)

* Password ('Yes' or 'No password' or the password)

* Ping

* Player (Current Playing Player and Max allowed Player) 

== Installation ==

1. Upload the folder `csViewer` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `[csviewer ip="mydomain.com" port="26015"]` on any Wordpress Page or Article and change the ip and the port.

4.(optional) Password
	
* Show password and add it to the autoconnect-url, so no password request from steam will displayed.
	`[csviewer ip="mydomain.com" port="26015" password="mypassword"]`
	
* You can add the password to the autoconnect-url without displaying the password on the website.(on the HTML you see the password anyway!)
	`[csviewer ip="mydomain.com" port="26015" password="mypassword" hidepassword="true"]`


5.(optional) add this CSS Lines in to the CSS from your Template

	table.server_data { width:60%; max-width: 280px; float:left; margin: 10px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
	table.server_data td { border-right: 1px dotted #ccc; border-bottom: 1px dotted #ccc; padding: 4px 2px 4px 10px; }
	table.server_data td.last { border-right: none; }
	table.server_data td.strong { width: 100px; }


== Frequently Asked Questions ==

= I cannt connect to my Server ? =

Your webhoster must allow the access to the socket-connection, because i use the php function 'fsockopen'.


= How can i display multiple Serverview  ? =

To show more then one Serverview you can make a div with topmargin 250px.

Example:

<div>
<strong>Public War Server</strong>
[csviewer ip="mydomain.com" port="27055"]
</div>

<div style="margin-top:250px;">
<strong>Deathmatch Server</strong>
[csviewer ip="mydomain.com" port="27045"]
</div>

<div style="margin-top:250px;">
<strong>Deathmatch Server</strong>
[csviewer ip="mydomain.com" port="27045"]
</div>



= Can i change the appearance (css) ? =

Yes you can change the CSS for the attributes 'table.server_data'

Example:

	table.server_data { width:60%; max-width: 280px; float:left; margin: 10px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
	table.server_data td { border-right: 1px dotted #ccc; border-bottom: 1px dotted #ccc; padding: 4px 2px 4px 10px; }
	table.server_data td.last { border-right: none; }
	table.server_data td.strong { width: 100px; }

== Screenshots ==

1. To show the Screenshot visite the my Homepage 

http://rainbow-six3.com/?p=472

== Changelog ==

= 1.0 =
* First Version.
* Created the Plugin

= 1.1 =
* Resolve problem "PHP Fatal error:  Cannot redeclare class csServerInfo"

= 1.2 =
* Resolve problem "PHP error:  Cannot modify header information - headers already sent by"
* Tested with Wordpress 4.0

= 1.3 =
* Show Password
* Tested with Wordpress 4.8