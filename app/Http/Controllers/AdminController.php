<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use App\Models\Matieres;
use App\Models\Notes;
use Carbon\Carbon;
use App\Models\Promotion;
use App\Models\Semestre;
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

        $nbr_etudiant = 0;
        $nbr_afaka = 0;
        $nbr_tsy_afaka = 0;
        $admis = [];
        $nom_admis = [];

        foreach ($etudiants as $e){
            $echec = 0;
            $valide = 0;
            $total_credit = 0;
            foreach ($data as  $d){
                $datasemestre =  DB::select('Select * from v_note_general where id_semestre = ? and  etudiant_id = ? ',[$d->id,$e->id_etudiant]) ;
                $notes = 0;
                $credit = 0;
                $nb_ajournee = 0;
                $credit_obtenu =0;
                foreach ($datasemestre as $ds )
                {
                $credit_obtenu = $credit_obtenu +$ds->credit_obtenu;
                 $notes =$notes +  $ds->note * $ds->credit;
                 $credit = $credit + $ds->credit;
                    if ($ds->note < 10){
                        $nb_ajournee = $nb_ajournee +1 ;
                    }
                    if ($ds->note < 6)
                    {
                        $echec++;
                    }
                }
                $moyen  = $credit ?  $notes /$credit : 0;
                if ($moyen >= 10 && $nb_ajournee <= $limit_ajour && $echec == 0)
                {
                    $credit_obtenu = $credit;
                 }
                $total_credit =   $total_credit + $credit_obtenu;
            }

            if ($total_credit == 180)
            {
                $nbr_afaka ++;
                $admis []= $e ;
            }
            elseif($total_credit < 180)
            {
                $nbr_tsy_afaka = $nbr_tsy_afaka +1 ;
                    $nom_admis[] = $e;
            }
            $nbr_etudiant = $nbr_etudiant +1;
        }

        return view('admin.table_de_bord',
            [
                'nbr_etudiant'=>$nbr_etudiant,
                'nbr_tsy_afaka'=>$nbr_tsy_afaka,
                'nbr_afaka'=>$nbr_afaka,
                'admis'=>$admis,
                'non_admis'=>$nom_admis
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
