<?php
/**
 * Created by PhpStorm.
 * User: Baird
 * Date: 16/10/10
 * Time: 下午6:59
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class xggControllers  extends Controller{
    /*
    * 注册用户,可以使用手机号码,第三方注册。
    * acctype,账号类型。
    * */
    public  function Registerinfo(Request $request){
         //消除对象为空的警告。
        error_reporting(E_ALL ^ E_NOTICE);
        $allData = $request->all();
         $account = $allData['account'];
         $password = $allData['password'];
         $usrname = $allData['usrname'];
         $face = $allData['face'];
         $onlytoken = $allData['onlytoken'];
         $acctype = $allData['acctype'];
         $mtime = time();
         if ($onlytoken){
             $isvalid = self::isvalidtype($acctype);
             if (!$isvalid){
                 return response()->json(['error'=>'账号类型错误,目前支持的类型是 qq weibo weixin']);
             }
             $result1 = DB::table('xggusr')->where('onlytoken','=',$onlytoken)->get();
             if (count($result1)>0){
                 return response()->json(['error'=>'用户已经注册']);
             }
             $result2 = DB::insert('insert into xggusr (account,password,face,usrname,onlytoken,acctype,mtime) values (?,?,?,?,?,?,?)',[
                 $account,
                 $password,
                 $face,
                 $usrname,
                 $onlytoken,
                 $acctype,
                 $mtime
             ]);
             if ($result2 == 1){
                 return response()->json(['result'=>'1']);
             }
             else{
                 return response()->json(['error'=>'注册失败']);
             }
         }
         else{
             $acctype = 'default';
             $isvalid = self::isvalidphone($account);
             if (!$isvalid){
                 return response()->json(['error'=>'请输入正确的账号']);
             }
             else if (!$password){
                 return response()->json(['error'=>'请输入密码']);
             }
             $result1 = DB::table('xggusr')->where('account','=',$account)->get();
             if (count($result1)>0){
                 return response()->json(['error'=>'用户已经注册']);
             }
             $result2 = DB::insert('insert into xggusr (account,password,face,usrname,onlytoken,acctype,mtime) values (?,?,?,?,?,?,?)',[
                 $account,
                 $password,
                 $face,
                 $usrname,
                 $onlytoken,
                 $acctype,
                 $mtime
             ]);
             if ($result2 == 1){
                 return response()->json(['result'=>'1']);
             }
             else{
                 return response()->json(['error'=>'注册失败']);
             }
         }
    }
    /*
     * 手机正则*/
    private function isvalidphone ($phone){
         $search ='/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
         if(preg_match($search,$phone)) {
            return true;
         }
         return false;
    }

    /*
     * 账号类型正则*/
    private function isvalidtype ($type){
        if ($type == 'qq'||$type == 'weibo'||$type == 'weixin'){
            return true;
        }
        return false;
    }
    /*
     * 用户登录
     * 可以使用账号登录,第三方登录。
     * */
    public  function  Login(Request $request){
        error_reporting(E_ALL ^ E_NOTICE);
        $allData = $request->all();
        $onlytoken = $allData['onlytoken'];
        $acctype = $allData['acctype'];
        $account = $allData['account'];
        $password = $allData['password'];
        //第三方登录
        if ($onlytoken){
            $user = DB::select('select * from xggusr where onlytoken = ?', [$onlytoken]);
            if (count($user)>0){
                $acctypeDB = $user[0]->acctype;
                $uid = $user[0]->uid;
                if ($acctype == $acctypeDB){
                    return self::Loginsuccessful($uid);
                }
                else{
                    return response()->json(['error'=>'登录类型错误']);
                }
            }
            else{
                return response()->json(['error'=>'用户不存在,请注册']);
            }
        }
        //普通登录
        else{
            $isvalid = self::isvalidphone($account);
            if (!$isvalid){
                return response()->json(['error'=>'请输入正确的账号']);
            }
            else if(mb_strlen($password)==0){
                return response()->json(['error'=>'请输入密码']);
            }
            else if(mb_strlen($password)<6){
                return response()->json(['error'=>'密码长度不能少于6位']);
            }
            else{
                $user = DB::select('select * from xggusr where account = ?', [$account]);
                if (count($user)>0){
                    $passwordDB = $user[0]->password;
                    $uid = $user[0]->uid;
                    if ($password == $passwordDB){
                       return self::Loginsuccessful($uid);
                    }
                    else{
                        return response()->json(['error'=>'密码错误']);
                    }
                }
                else{
                    return response()->json(['error'=>'用户不存在,请注册']);
                }
            }
        }
    }
    /*
     * 登录成功
     * 需要更新数据库的usertoken
     */
    private function Loginsuccessful($uid){
        $usertoken = md5(uniqid());
        $mtime = time();
        DB::table('xgglogin')->where('uid', '=', $uid)->delete();
        DB::insert('insert into xgglogin (uid,usertoken,mtime) values (?,?,?)',[
            $uid,
            $usertoken,
            $mtime
        ]);
        return response()->json([
            'uid' =>$uid,
            'result'=>'1',
            'usertoken'=>$usertoken
        ]);
    }
}