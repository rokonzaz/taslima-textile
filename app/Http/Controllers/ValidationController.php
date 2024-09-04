<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function liveValidateSingleData(Request $request)
    {
        $a=$request->a;
        $val=$request->val;
        switch ($a){
            case 'user-email':
                $user=User::where('email', $val);
                if($request->except!=''){
                    $user->whereNot('id', $request->except);
                }
                $user->withTrashed();
                if($user->count()==0) return response()->json(['status'=>1, 'msg'=>'Usable']);
                else return response()->json(['status'=>0, 'msg'=>'Already Exists']);
                break;
            default:
                break;
        }
    }
}
