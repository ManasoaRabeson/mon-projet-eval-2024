<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use App\Models\Matieres;
use App\Models\Notes;
use Carbon\Carbon;
use App\Models\Promotion;
use App\Models\Semestre;
use App\Models\Tableau_de_bord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function formulaireNote()
    {
        return view('admin.formulaireNotes');
    }
    public function insertNotes(Request $request)
    {
        $request->validate([
            'etu' => 'required',
            'matiere' => 'required',
            'notes' => 'required|numeric|min:0|max:20',
        ]);
        $etu =$request->input('etu');
        $matiere_code =$request->input('matiere');
        $notes =$request->input('notes');
        $Etudiants = Etudiants::where('etu', $etu)->first();
        $matiere = Matieres::where('code', $matiere_code)->first();
        if($Etudiants && $matiere)
        {
            Notes::insert([
                'etudiant_id' => $Etudiants->id_etudiant,
                'matiere_id' => $matiere->id_matiere,
                'note'=>$notes
            ]);
            return redirect()->intended('Admin/formNote')->with('success','note bien ajoute');
        }
        return redirect()->intended('Admin/formNote')->with('error','verifiez etudiant ou matiere');
    }
    public function getListeEtudiants(Request $request )
    {
        $etudiant = new Etudiants();
        $data = $request->input();
        $promo = Promotion::all();
        $etudiants = $etudiant->traitement_liste_etudiants($data);
        return view('admin.listeEtudiant',['promotion'=>$promo,'etudiants'=>$etudiants]);
    }
    public function listeSemestre($id){
        $semestre = new Semestre();
        $data = $semestre->getSemestre();
        $table = $semestre->moyenne_semestre($id);
        return view('admin.listeSemestre',['semestre'=>$data,'idetudiant'=>$id,'table'=>$table]);
    }
    public function listeNoteParSemestre($id,$semestre){
        $data = Notes::getSemestre($id,$semestre);
        $Etudiants = Etudiants::where('id_etudiant', $id)->first();
        return view('admin.listeNoteParSemestre',[
            'note'=>$data,
            'etudiants'=>$Etudiants 
        ]);
    }
    public  function tableau_de_bord()
    {
        $note = new Notes();
        $conf = $note->getconfiguration();
        $note_ajournee = $conf[0]->valeur;
        $limit_ajour = $conf[1]->valeur;
        $etudiants = Etudiants::all();
        $semestre = new Matieres();
        $data = $semestre->getSemestre();
        $tableau_de_bord = new Tableau_de_bord();
        $table = $tableau_de_bord->admis_et_non_admins($etudiants,$data,$limit_ajour);
        return view('admin.table_de_bord',
            [
                'nbr_etudiant'=>$table['nbr_etudiant'],
                'nbr_tsy_afaka'=>$table['tsy_afaka'],
                'nbr_afaka'=>$table['afaka'],
                'admis'=>$table['admis'],
                'non_admis'=>$table['non_admis']
            ]);
    }

    public function listeAnnee($id){
        return view('admin.liste_annee',['id_etudiant'=>$id]);
    }
    public function listeNoteParAnnee($id,$annee)
    {
        $Etudiants = Etudiants::where('id_etudiant',$id)->first();
        $data = Notes::getNotesParAnne($annee,$id);
        $situation_et_moyenne = Notes::situation_et_moyenne($data);
        return view('admin.liste_note_par_annee',
        [
            'data' =>$data,
            'etudiants'=>$Etudiants,
            'moyenGenerale'=>$situation_et_moyenne['moyen_general'],
            'Situation'=>$situation_et_moyenne['situation']
        ]
        );

    }

}
