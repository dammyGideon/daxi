<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Documents;
use Validator;

class DocumentController extends Controller
{

    //Drivers IDcard
    public function indentityCard(Request $request){
        $user=$request->user()->id;

        $username=User::where('id',$user)->value('name');
        $validator = Validator::make($request->all(),[
        'id_card'            => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($validator->fails()){
            return response([
                'error' =>$validator->errors()->all()
            ], 422);
        }

        if ($request->hasFile('id_card')) {
            $files = $request->file('id_card');
            $filename = $username. '.'. $files->getClientOriginalExtension();
            $destinationPath = public_path('/idCards');
            $files->move($destinationPath, $filename);
            $imagePath='http://daxi.idealloan.com.ng/idCards/'.$filename;

            $document= new Documents();
            $document->user_id=$user;
            $document->idCard=$imagePath;
            $document->save();
        }
    }

    //Driver licence
    public function licence(Request $request){
        $user=$request->user()->id;

        $username=User::where('id',$user)->value('name');
        $validator = Validator::make($request->all(),[
        'licence'            => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($validator->fails()){
            return response([
                'error' =>$validator->errors()->all()
            ], 422);
        }

        if ($request->hasFile('licence')) {
            $files = $request->file('licence');
            $filename = $username. '.'. $files->getClientOriginalExtension();
            $destinationPath = public_path('/licences');
            $files->move($destinationPath, $filename);
            $imagePath='http://daxi.idealloan.com.ng/licences/'.$filename;

            $document= new Documents();
            $document->user_id=$user;
            $document->licence=$imagePath;
            $document->save();
        }
    }

    

}
