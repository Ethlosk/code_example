<?php
class My_Net_Match(){

  public function net_match($ip, $range) {
	if(strpos($range, '/') == false) {
		$range .= '/32';
		}
	$ip_arr = explode("/", $range, 2);
	$range_comp = ip2long($ip_arr[0]) >> (32 - $ip_arr[1]);
	$ip_comp = ip2long($ip) >> (32 - $ip_arr[1]);
	return ($ip_comp == $range_comp);
	}
	
	}
	?>