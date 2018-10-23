<?php
require_once('PHPUnit/Framework.php');
require_once('net_match_class.php');

class My_Net_Match_Test extends PHPUnit_Framework_TestCase {
		 

    /**
    * @dataProvider providerNetMatch
    */
    public function testNetMatch($ip, $range)
    {
        $net = new My_Net_Match();
        $this->assertTrue($net->net_match($ip, $range));
    }

    public function providerNetMatch ()
    {
        return array (
            array ('10.1.0.2', '10.1.0.0/24'),
            array ('192.168.1.1', '192.168.0.0/24'),
            array ('192.168.15.1', '192.168.17.1/16'),
            array ('192.168.1.128', '192.128.1.128/16'),
            array ('192.168.1.28', '192.168.1.32/28')
        );
    }
}
?>