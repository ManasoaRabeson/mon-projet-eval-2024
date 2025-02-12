<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthStudentController extends Controller
{
    public function doLoginStudent(Request $request){
        $request->validate([
            'etu' => 'required'
        ]);
        $etu = strtoupper($request->input('etu'));
        $data = Etudiants::where('etu',$etu)->first();
        if ($data)
        {
            Session::put('ETU',$data->id_etudiant);
            return redirect()->intended('etudiant/accueil');
        }else
        {
            return redirect()->intended('etudiant/login')->with('error','etu ne pas xistee');
        }
    }
    public function logout(Request $request){

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->intended('/');

    }
}
