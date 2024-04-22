<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        if(Auth::user()->admin == 0) { 
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            return view('profile.view', compact('user'));
        }
        else {
            return redirect('/admin-panel');
        }
    }

    
    public function edit()
    {
        if(Auth::user()->admin == 0) { 
            $id = Auth::user()->id;
            $user = User::find($id);
            return view('profile.edit', compact('user'));
        }
        else {
            return redirect('/admin-panel');
        }
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
    
        $validatedData = $request->validate([
            'name' => 'required|max:40',
            'email' => 'required|max:190',
            'password' => 'nullable|min:8|confirmed',
        ]);
    
        $user = User::find($id);
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        $user->country = $request->country;
    
        // Only update the password if a new password is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        if ($request->hasFile('image')) {
            $user->image = $request->image->store('profile_pics', 'public');
        }
    
        $user->save();
    
        return redirect('/home')->with('msg_success', 'Your Profile is Updated');
    }
    

    
}
