<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index() {
        $users = User::get();

        return Inertia::render('Admin/User/Index',['users'=>$users]);
    }
    //update
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        // dd($user);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->isAdmin = $request->isAdmin;

        $user->update();
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // // delete
    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();
    //     return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    // }

}
