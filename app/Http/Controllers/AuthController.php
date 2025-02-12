<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function creerAdmin(){
        User::create([
            'email' => 'Admin@gmail.com',
            'password' => 'mdpadmin'
        ]);
        return  to_route('auth.login');
    }

    public function login() {
        return view('auth.login');
    }
    public  function  doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required|min:4'
        ]);

        if (Auth::attempt($credentials)){

            return redirect()->intended('/admin');
        }
        return  to_route('auth.login')
            ->withErrors(['email'=>"email ou mot de pass invalid"])
            ->onlyInput('email');
    }
    public function trun(){
        $exeption = ['matiere','semestre','semestre_matiere','semestre_etudiants'];
        $tables = Schema::getAllTables();
        DB::statement('SET session_replication_role =replica');
        DB::beginTransaction();

        try{
            foreach ($tables as $table){
                if (!in_array($table->tablename,$exeption))
                {
                    DB::table($table->tablename)->truncate();
                }

            }
            DB::commit();
        }catch (\Exception $e){

        } finally {
            DB::statement('SET session_replication_role = DEFAULT');
            User::create([
                'email' => 'Admin@gmail.com',
                'password' => 'mdpadmin',
            ]);


        }
    }
    public function logoutAdmin(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('auth.login');
    }
}
