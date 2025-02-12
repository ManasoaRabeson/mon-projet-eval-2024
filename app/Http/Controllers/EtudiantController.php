<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use App\Models\Notes;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EtudiantController extends Controller
{
    public function Accueil(){
        $id = Session::get('ETU');
        $etudiant = Etudiants::where('id_etudiant',$id)->first();
        return view ('etudiant.accueil',['etudiant'=>$etudiant]);
    }
    public function liste_semestre()
    {
        $id = Session::get('ETU');
        $data =  Semestre::all();
        $table = [];
        foreach ($data as  $d){
            $datasemestre =  DB::select('Select * from v_note_general where id_semestre = ? and  etudiant_id = ? ',[$d->id,$id]) ;
            $notes = 0;
            $credit = 0;
            foreach ($datasemestre as $ds )
            {
                $notes =$notes +  $ds->note * $ds->credit;
                $credit = $credit + $ds->credit;
            }
            $moyen  = $credit ?  $notes /$credit : 0;
            $table[$d->id] = round($moyen,2);


            }
        return view ('etudiant.liste_semestre',['semestre'=>$data,'table'=>$table]);
    }
    public function liste_note($semestre){
        $id = Session::get('ETU');
        $resultat = Notes::getSemestre($id,$semestre);
        $Etudiants = Etudiants::where('id_etudiant', $id)->first();
        return view('etudiant.listeNoteParSemestre',[
            'note'=>$resultat['data'],
            'moyen'=>$resultat['moyen'],
            'credit'=>$resultat['credit_obtenu'],
            'semestre'=>$resultat['semestre'],
            'etudiants'=>$Etudiants,
            'mention'=>$resultat['mention']
        ]);
    }
}
