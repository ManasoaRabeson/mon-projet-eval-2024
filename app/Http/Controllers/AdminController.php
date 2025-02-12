<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use App\Models\Matieres;
use App\Models\Notes;
use App\Models\Promotion;
use Carbon\Carbon;
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
        $promo = Promotion::all();

        $data = $request->input();
        if (empty($data))
        {
            $etudiants = Etudiants::all();
        }
        else {
            $prom = intval($data['idprom']);
            if ($data['nom'] == null){
                $nom = "";
            }
            else $nom  = strtolower($data['nom']);
            $query_prom  = "";
            if ($prom != 0)
            {
                $query_prom = "and promotion =".$prom;
            }
            $query = "SELECT * FROM etudiants where lower(CONCAT(nom,'',prenom)) LIKE '%".$nom."%'".$query_prom;
            $etudiants = DB::select($query);
        }
        return view('admin.listeEtudiant',['promotion'=>$promo,'etudiants'=>$etudiants]);
    }
    public function listeSemestre($id){
        $semestre = new Matieres();
        $data = $semestre->getSemestre();
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
        return view('admin.listeSemestre',['semestre'=>$data,'idetudiant'=>$id,'table'=>$table]);
    }
    public function listeNoteParSemestre($id,$semestre){

        $note = new Notes();
        $conf = $note->getconfiguration();


        $note_ajournee = $conf[0]->valeur;
        $limit_ajour = $conf[1]->valeur;


         $data = $note->getNote($id,$semestre);
         $notes = 0;
         $credit = 0;
         $credit_obtenu = 0;
         $nbr_ajour = 0;
         $echec = 0;
            foreach ($data as $d )
            {
                $notes =$notes +  $d->note * $d->credit;
                $credit = $credit + $d->credit;
                $credit_obtenu = $credit_obtenu + $d->credit_obtenu;
                if ($d->note <10){
                    $nbr_ajour = $nbr_ajour +1 ;
                }
                if ($d->note < 6)
                {
                    $echec ++;
                }
            }
            $moyen  = $notes /($credit);

            if ($moyen >= 10)
            {
                foreach ($data as $ds )
                {
                    if ($ds->note < $note_ajournee)
                    {
                        $ds->resultats = 'Aj';
                    }elseif($ds->note >= $note_ajournee && $ds->note < 10 && $nbr_ajour <= $limit_ajour){
                        $ds->resultats = '--';
                        $credit_obtenu = $credit;
                    }

                }
            }
            $mention = '';
            if ($moyen >= 10 && $moyen< 12){
                $mention = 'moyenne';
            }elseif ($moyen>=12 && $moyen<14)
            {
                $mention = 'assez bien';
            }elseif ($moyen>=14 && $moyen<17)
            {
                $mention = 'bien';
            }elseif ($moyen>=17){
                $mention = 'tres bien';
            }elseif ($moyen<10)
            {
                $mention = '--';
            }


        $Etudiants = Etudiants::where('id_etudiant', $id)->first();
        return view('admin.listeNoteParSemestre',[
            'note'=>$data,
            'moyen'=>$moyen,
            'credit'=>$credit_obtenu,
            'semestre'=>$semestre,
            'etudiants'=>$Etudiants,
            'mention'=>$mention
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
        $data = [];

            if($annee == 1)
            {
                for ($i =1 ;$i<=2 ; $i++)
                {
                    $data []= Notes::getSemestre($id,$i);
                }
            }
            if($annee == 2)
            {
                for ($i =3 ;$i<=4 ; $i++)
                {
                    $data[] = Notes::getSemestre($id,$i);
                }
            }
            if($annee == 3)
            {
                for ($i =5 ;$i<=6 ; $i++)
                {
                    $data[] = Notes::getSemestre($id,$i);
                }
            }

        $moyenGlobale = 0;
        $echec = 0;
        $credit = 0;
            foreach ($data as $d)
            {
                $moyenGlobale =$moyenGlobale + $d['moyen'];
               $credit = $credit + $d['credit_obtenu'] ;

            }
            $moyenGenerale = $moyenGlobale /2;
            $Situation = '';
            if ( $credit == 60)
            {
                $Situation = 'Admis';
            }elseif ($credit < 60)
            {
                $Situation = 'Ajournee';
            }

        return view('admin.liste_note_par_annee',
        [
            'data' =>$data,
            'etudiants'=>$Etudiants,
            'moyenGenerale'=>$moyenGenerale,
            'Situation'=>$Situation
        ]
        );

    }

}
