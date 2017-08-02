<?php
/*
	Author: Reto Arnold
	Class:  csServerInfo
	*/
class csServerInfo{      
    private $timeout = 3;
    private $ip;
    private $port;
    private $protocol;
    protected $index;
    protected $resource;
    protected $byte_array;
    protected $challenge;
    
    public function __construct($address, $port){
        if ($this->validateAddress($address) === false){
            return 104;
        }
                
        $this->ip   = $address;
        $this->port = $port;
        
        if (!$this->connect()){
            return 103;
        }
        $this->protocol = 0;
        $this->challenge = false;
    }
      
    public function getDetails(){
        if ($this->validateAddress($this->ip) === false){
            return 104;
        }
   
        $start = $this->getTime();
        $this->writeDdata("\xFF\xFF\xFF\xFFTSource Engine Query\x00");
        
        $this->byte_array   = fread($this->resource, 4096);
        $status             = socket_get_status($this->resource);
        
        if ($status['eof'] == 1){
            return 103;
        }        
        if ($status['timed_out']){
            return 101;
        }
		
        $stop               = $this->getTime();
        $server_ping        = (int)( ($stop-$start)*1000 );
        
        $this->index = 0;  
        $this->skipIndex(4);     
        $type               = $this->getChar();
        $server             = array();
        
        if ($type == 'm'){            
            $this->getString();
            $server['address']       = $this->ip.':'.$this->port;
            $server['hostname']      = $this->getString();
            $server['map']           = $this->getString();
            $server['dir']           = $this->getString();
            $server['desc']          = $this->getString();
            $server['appid']         = 10;
            $server['players_on']    = $this->getByte();
            $server['players_max']   = $this->getByte();
            $server['protocol']      = $this->getByte();
            $server['type']          = $this->getChar();
            $server['os']            = $this->getChar();
            $server['password']      = $this->getByte();
            $server['ping']          = $server_ping;
            $this->protocol          = $server['protocol'];
        }
        elseif ($type == 'I'){
            $server['address']        = $this->ip.':'.$this->port;
            $server['protocol']       = $this->getByte();
            $server['hostname']       = $this->getString();
            $server['map']            = $this->getString();
            $server['dir']            = $this->getString();
            $server['desc']           = $this->getString();
            $server['appid']          = $this->getShort(); 
            $server['players_on']     = $this->getByte();
            $server['players_max']    = $this->getByte();
            $server['bots']           = $this->getByte();
            $server['type']           = $this->getChar();
            $server['os']             = $this->getChar();
            $server['password']       = $this->getByte();
            $server['secure']         = $this->getByte();
            $server['is_mod']         = false;
            $server['ping']           = $server_ping;
            $server['version']        = $this->getString();            
            $this->protocol           = $server['protocol'];
        }
		else{
            $server['error']          = 105;
        }
        return $server;
    } 
    
    private function writeDdata($command){       
        return ( fwrite($this->resource, $command, strlen($command)) === false) ? false : true; 
    }
    
    public function validateAddress($address){
        if (!$this->isDns($address) AND !$this->isIp($address) ){
            return false;
        }
        if ($this->isDns($address)){
            $this->ip = gethostbyname($address);
        }
        return true;
    }
    
    public function isDns($domain){
        return (preg_match('/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i', $domain) === 0) ? false : true;
    }
    
	private function isIp($ip_addr){
		if (preg_match("/^(\d{1,3})\.$/", $ip_addr) || preg_match("/^(\d{1,3})\.(\d{1,3})$/",
			$ip_addr) || preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr) ||
			preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr))
		{
			$parts = explode(".", $ip_addr);
			foreach ($parts as $ip_parts){
				if (intval($ip_parts) > 255 || intval($ip_parts) < 0){
					return false;
				}
			}
			return true;
		}
		else{
			return false;
		}
	}
    
    private function get($length){
        $data = substr($this->byte_array, $this->index, $length);
        $this->index += $length;      
        return $data;
    }
    
    private function getByte(){
		return ord($this->get(1));
	}
        
    private function getShort(){
        $data = unpack('v', $this->get(2));
        return $data[1];
    }
    
    private function getString(){
        $tmp = strpos($this->byte_array, "\0", $this->index);
        
        if ($tmp === false){
            return '';
        }
        else{
            $string = $this->get($tmp - $this->index);
            $this->index++;
            return $string;
        }
    }
    
    private function getChar(){
        return substr($this->byte_array,$this->index++,1);
    }
       
    private function skipIndex($length){
		$this->index += $length;
	}
    
    public function getTime(){
        $t = explode(' ', microtime());
        return (float)$t[0] + (float)$t[1];
    }
        
    public function isConnected(){
        return (is_resource($this->resource)) ? true : false;
    } 
  
	public function connect(){
		$this->disconnect();
        if ( $this->resource = @fsockopen('udp://'. $this->ip, $this->port)){
            socket_set_timeout($this->resource, $this->timeout);
        }
        return $this->isConnected();
	}
    
    public function disconnect(){
        if ($this->isConnected()){
            fclose($this->resource);
        }
    }
    
	public function __destruct(){
		$this->disconnect();
	}
}