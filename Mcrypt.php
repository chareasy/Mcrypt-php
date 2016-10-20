<?php
/**
 * Date: 2016/10/20
 * Time: 16:46
 */


class Mcrypt{

    private $_mcrypt;

    private static $_instance;

    public function __construct($cipher,$mode){
        $this->_mcrypt = mcrypt_module_open($cipher, '', $mode, '');

    }

    public static function newInstance($cipher="rijndael-256",$mode="ofb") {
        if (!self::$_instance instanceof Mcrypt) {
            self::$_instance = new Mcrypt($cipher,$mode);
        }
        return self::$_instance;
    }

    public function encrypt($salt, $str){


        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->_mcrypt));
        $ks = mcrypt_enc_get_key_size($this->_mcrypt);
        $key = $this->getMcryptKey($salt,$ks);
        mcrypt_generic_init($this->_mcrypt, $key, $iv);
        $encrypted = mcrypt_generic($this->_mcrypt, $str);
        mcrypt_generic_deinit($this->_mcrypt);
        return bin2hex($iv.$encrypted);
    }



    public function getKey(){
        $length= 32;
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    private function getMcryptKey($salt,$ks){
        return substr(md5($salt), 0, $ks);
    }


    public function decrypt($salt, $str){
        $str = hex2bin($str);
        $iv = substr($str, 0, mcrypt_enc_get_iv_size($this->_mcrypt));
        $ks = mcrypt_enc_get_key_size($this->_mcrypt);
        $key = $this->getMcryptKey($salt,$ks);

        mcrypt_generic_init($this->_mcrypt, $key, $iv);
        $str = substr($str, mcrypt_enc_get_iv_size($this->_mcrypt));
        $decrypted = mdecrypt_generic($this->_mcrypt, $str);

        mcrypt_generic_deinit($this->_mcrypt);
        return ($decrypted);

    }


}
