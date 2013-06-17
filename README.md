captcha-laravel
===============
Composer.json:

require: "wikichua/captcha": "dev-master"

Provider:

'Wikichua\Captcha\CaptchaServiceProvider',

Alias:

'Captcha'  	  => 'Wikichua\Captcha\Facades\Captcha',

Sample usage:

Route::any('test',function() {
    if (Request::getMethod() == 'POST')
    {
        $rules =  array('thecaptcha' => array('required', 'captcha'));
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            echo '<p style="color: red;">Captcha failed</p>';
        }
        else
        {
            echo '<p style="color: blue;">Captcha passed</p>';
        }
    }

    $content = Form::open(['to'=>URL::to(Request::segment(1))]);
    $content .= '<p>' . Captcha::imgUrl(['class'=>'testing']) . '</p>';
    $content .= '<p>' . Form::text('thecaptcha') . '</p>';
    $content .= '<p>' . Form::submit('Check') . '</p>';
    $content .= Form::close();
    return $content;
});
