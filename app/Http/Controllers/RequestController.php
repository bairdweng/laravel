<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
//use Illuminate\Database\Query\Builder;
class RequestController extends Controller{
    public function getSaveinfo(Request $request){
        $input = $request->input('test');
        echo $input;
    }
    /*
      添加用户信息.
    */
    public function Adduserinfo(Request $request){
        $allData = $request->all();
        $affected = DB::insert('insert into hv_user (firstName, lastName, gender,phone,face) values (?,?,?,?,?)',
        [$allData['firstName'],
        $allData['lastName'],
        $allData['gender'],
        $allData['phone'],
        "http://photocdn.sohu.com/20160511/Img448787219.jpg"]);

        if($affected == 1){
         return response()->json(['result'=>'1']);
        }
        else{
         return response()->json(['error'=>'添加失败']);
        }
   }
   /*
     查询所有用户信息
   */
   public function Getuserinfo(){
    $users = DB::table('hv_user')->select('firstName','lastName','phone','gender','face','id')->get();
    return response()->json($users);
   }

     /*
      获取个人详细页面.
      */
    public function Getuserdetailedinfo(Request $request){
         $postJson = file_get_contents('php://input');
         $allData = json_decode($postJson, true);
        $result = DB::table('user_img')->select('Img')->where('id','=','1')->get();
        return response()->json($result);
    }

   /*
   根据id删除用户的信息.
   */
    public function Deleteuserinfo(Request $request){
        $postJson = file_get_contents('php://input');
        $allData = json_decode($postJson, true);

        $affected = DB::table('hv_user')->where('id', '=', $allData['id'])->delete();

        if($affected == 1){
           return response()->json(['result'=>'1']);
        }
        else{
           return response()->json(['error'=>'删除失败']);
        }
    }
   /*
   更新用户数据.
   */
   public function Updatetheuserinfo(Request $request){
         $postJson = file_get_contents('php://input');
         $allData = json_decode($postJson, true);
         $affected = DB::table('hv_user')->where('id',$allData['id'])->update([
         'firstName' => $allData['firstName'],
         'lastName' => $allData['lastName'],
         'gender' => $allData['gender'],
         'phone' => $allData['phone'],
         'face'=> $allData['face']
         ]);
        if($affected == 1){
           return response()->json(['result'=>'1']);
        }
        else{
           return response()->json(['error'=>'修改失败']);
        }
   }

   //上传图片.
    public function Uploadimage(Request $request){
        $file = $request->file('file');
        if(!$file->isValid()){
         return response()->json(['error'=>'文件上传失败']);
        }
        $newFileName = md5(time().rand(0,10000)).'.'.$file->getClientOriginalExtension();
         $savePath = 'Images/';
         $result = $file->move($savePath, $newFileName);
         //写入数据库.
       DB::table('userImage')->insert([
           ['id'=>1,'filename'=>$newFileName]
           ]);
        return response()->json(['result'=>'1']);
    }
    //获取上传的图片.
    public function Getuploadimg(Request $request){
        $result = DB::table('userImage')->select('filename')->where('id','=',1)->get();
        function object_array($array){
          if(is_object($array)){
            $array = (array)$array;
          }
          if(is_array($array)){
            foreach($array as $key=>$value){
              $array[$key] = object_array($value);
            }
          }
          return $array;
        }
        $array = object_array($result);
        $server_name = $_SERVER['SERVER_NAME'].":8080";

        for($i=0;$i<count($array);$i++){
          $filename = $array[$i]["filename"];
          $paths="http://".$server_name."/Images/".$filename;
          $array[$i]["filename"] = $paths;
        }
        return json_encode($array);
    }

}