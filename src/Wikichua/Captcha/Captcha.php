<?php namespace Wikichua\Captcha;

use Sessions, Config, URL;

class Captcha {

    protected $str; 
    protected $img;
    protected $font;
    protected $fSize;
    protected $height;
    protected $width;
    protected $possibleString;
    protected $stringLength;

    public function __construct()
    {
        $this->font='public/packages/wikichua/captcha/fonts/luxisr.ttf'; 
        $this->count=40;  
        $this->fSize=20;  
        $this->height=50;  
        $this->width=150;
        $this->stringLength=5;
        $this->possibleString="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }

    public function createCaptcha() 
    {
        $this->img=imagecreate($this->width,$this->height) or die("GD not found!"); 
        $backColor=imagecolorallocate($this->img,255,255,255);
        $lineColor=imagecolorallocate($this->img,255,238,238);
        $textColor=imagecolorallocate($this->img,0,255,255);
        $this->str = $this->generateKeyString();
        $textbox=imagettfbbox($this->fSize,0,$this->font,$this->str) or die('Error in imagettfbbox function or font!'); 
        $x=($this->width-$textbox[4])/2; 
        $y=($this->height-$textbox[5])/2; 
        imagettftext($this->img,$this->fSize,0,$x,$y,$textColor,$this->font,$this->str) or die('Error in imagettftext function!!'); 
            for($i=0;$i<$this->count;$i++){ 
                $x1=rand(0,$this->width);$x2=rand(0,$this->width); 
                $y1=rand(0,$this->width);$y2=rand(0,$this->width); 
                imageline($this->img,$x1,$y1,$x2,$y2,$lineColor); 
            } 
        $this->showCaptcha(); 
    }

    protected function showCaptcha() 
    { 
    header('Content-Type: image/jpeg'); 
    imagejpeg($this->img,NULL,100); 
    imagedestroy($this->img);

    Session::put('captchaKey', $this->str); 
    }

    protected function generateKeyString()
    {
        $possibleStrings = str_split($this->possibleString);
        $rand_keys = array_rand($possibleStrings, $this->stringLength);
        $res = array();

        if($this->stringLength > 1)
        {
            for($a=0;$a<$this->stringLength;$a++)
            {
                $res[] = $possibleStrings[$rand_keys[$a]];
            }
        }else{
            $res[] = $possibleStrings[$rand_keys];
        }
        return implode($res);
    }

    public function imgUrl() {

        return URL::to('/wikichua/captcha?' . mt_rand(100000, 999999));

    }

    public function checkCaptcha($user_input)
    {
        if(strtoupper(Session::get('captchaKey') == strtoupper($user_input))
        {
            return true;
        }

        return false;
    }
}