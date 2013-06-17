<?php

Route::get('/wikichua/captcha', ['as'=>'captcha','do'=>function()
{
    return Captcha::createCaptcha();
}]);