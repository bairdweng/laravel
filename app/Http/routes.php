<?php

Route::get('/', function () {
    return view('welcome');
});
//设置中间键。
Route::group(['middleware'=>'tokenvalidation'],function(){
    //注册用户信息。
    Route::post('xgg/register', 'xggControllers@Registerinfo');
    //用户登录。
    Route::post('xgg/login', 'xggControllers@Login');
});
/*
Route::post('/hello',['as'=>'hello',function(){
    return '卧槽';
}]);

Route::get('/HI', function () {
    return "HI";
});
//这样是可以的.
Route::post('/file-upload',function() {
   return "dawdawd";
});
//定位到控制器里面去.
Route::controller('request','RequestController');
Route::group(['middleware'=>'testcors'],function(){
        //获取个人信息.
        Route::post('getuserinfo', 'RequestController@Getuserinfo');

        //获取个人详情页面.
        Route::post('getuserdetailedinfo22','RequestController@Getuserdetailedinfo');

        //添加个人信息.
        Route::post('adduserinfo', 'RequestController@Adduserinfo');

        //提交id删除个人信息
        Route::post('deleteuserinfo', 'RequestController@Deleteuserinfo');

        //更新个人信息.
        Route::post('updatetheuserinfo','RequestController@Updatetheuserinfo');

        //获取上传的图片列表
        Route::post('getuploadimg','RequestController@Getuploadimg');
});
//上传图片,使用中间键莫名其妙报错,天啊鲁,卧槽你大爷.
Route::post('uploadimage','RequestController@Uploadimage');

//中间键测试模块.
Route::group(['middleware'=>'test'],function(){
 Route::get('/write/laravelacademy',function(){
     //使用Test中间件
 });
 Route::get('/update/laravelacademy',function(){
    //使用Test中间件
 });
});
Route::get('/age/refuse',['as'=>'refuse',function(){
 return "未成年人禁止入内！";
}]);
*/