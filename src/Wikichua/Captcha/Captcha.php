<?php namespace Wikichua\Captcha;

use Sessions, Config, URL, HTML;

class Captcha {

    protected $str; 
    protected $img;
    protected $font;
    protected $fSize;
    protected $height;
    protected $width;
    protected $possibleString;
    protected $stringLength;
    protected $backColorRGB;
    protected $lineColorRGB;
    protected $textColorRGB;

    public function __construct()
    {
        $this->font=Config::get('captcha::font'); 
        $this->count=Config::get('captcha::count');  
        $this->fSize=Config::get('captcha::fontSize');  
        $this->height=Config::get('captcha::height');  
        $this->width=Config::get('captcha::width');
        $this->stringLength=Config::get('captcha::stringLength');
        $this->possibleString=Config::get('captcha::possibleString');
        $this->backColorRGB = Config::get('captcha::backColorRGB');
        $this->lineColorRGB = Config::get('captcha::lineColorRGB');
        $this->textColorRGB = Config::get('captcha::textColorRGB');
    }

    public function createCaptcha() 
    {
        $this->img=imagecreate($this->width,$this->height) or die("GD not found!"); 
        
        list($r,$g,$b) = explode(',',$this->backColorRGB);
        $backColor=imagecolorallocate($this->img,$r,$g,$b);

        list($r,$g,$b) = explode(',',$this->lineColorRGB);
        $lineColor=imagecolorallocate($this->img,$r,$g,$b);

        list($r,$g,$b) = explode(',',$this->textColorRGB);
        $textColor=imagecolorallocate($this->img,$r,$g,$b);

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
        $res = array();
        $i = 0;

        while($i < $this->stringLength)
        {
            $res[] = $possibleStrings[array_rand($possibleStrings)];
        }

        return implode($res);
    }

    public function imgUrl($attributes = array())
    {
        return HTML::image(URL::to('/wikichua/captcha?' . mt_rand(100000, 999999)), 'Captcha image', $attributes);
    }

    public function checkCaptcha($user_input)
    {
        if(strtoupper(Session::get('captchaKey')) == strtoupper($user_input))
        {
            return true;
        }

        return false;
    }
}