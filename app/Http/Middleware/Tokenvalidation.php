<?php
/**
 * Created by PhpStorm.
 * User: Baird
 * Date: 16/10/11
 * Time: 下午2:32
 */
namespace App\Http\Middleware;
use Closure;
//验证token是否正确,否则无法访问
class Tokenvalidation{
    public function handle($request, Closure $next){
        if($request->input('token')=='D82377E8-7F4E-929F-7AD5-858EDE572814'){
            return $next($request);
        }
        else{
            return response()->json(['error'=>'token错误']);
        }
    }
}