<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class updateUserDetailsAction {
    public function run($request, $userId) {
        $user = User::findorFail($userId);
        $user->phone = $request['phone'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['phone']);
        return $user->save();
    }
}
