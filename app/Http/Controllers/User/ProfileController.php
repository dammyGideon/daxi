<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    //

    //profile pictures

    public function profileImages(Request $request){

        $name=$request->user()->name;
        $id=$request->user()->id;

        $validator = Validator::make($request->all(),[
        'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
        return response()->json(['error'=>$validator->errors()], 401);
        }

        if ($request->hasFile('picture')) {
            $files = $request->file('picture');
            $filename = $name. '.'. $files->getClientOriginalExtension();
            $destinationPath = public_path('/profileImage');
            $files->move($destinationPath, $filename);
            $imagePath='http://daxi.idealloan.com.ng/profileImage/'.$filename;
            $user= User::find($id);
            $user->picture= $imagePath;
            $user->update();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "data" => $user
            ]);
        }

       }

    public function updateProfie(Request $request){
        $user= $request->user()->id;

        $db= DB::table('users')->where('id',$user)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'bank'=>$request->bank,
           'Account'=>$request->account,
           'phone'=>$request->phone
        ]);

        return response()->json([

            "status"=>true,
            "message"=>"Profile was successfully updated",
            "data"=>$db

        ]);
    }
}
