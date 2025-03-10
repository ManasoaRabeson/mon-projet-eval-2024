<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notes extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $fillable = [
        'etudiant_id',
        'matiere_id',
        'note',
        'credit',
        'resultat',
        'session'
    ];
    public function getNote($id,$semestre)
    {
        return DB::table('v_note_general')
            ->where([
                ['etudiant_id', '=', $id],
                ['id_semestre', '=', $semestre]
            ])
            ->get();
    }
    public function getNoteById($id,$semestre)
    {
        return DB::table('v_note_general')
            ->where([
                ['etu', '=', $id],
                ['id_semestre', '=', $semestre]
            ])
            ->get();
    }
    public function getconfiguration(){
        return DB::table('configuration')->get();
    }

    public static function getSemestre($id,$semestre){
        $note = new Notes();
        $conf = $note->getconfiguration();
        $note_ajournee = $conf[0]->valeur;
        $limit_ajour = $conf[1]->valeur;
        $note = new Notes();
        $data = $note->getNote($id,$semestre);
        $moyen = Notes::getMoyenne($data);
        $credit_data = Notes::credit_obtenu($data,$moyen,$note_ajournee,$limit_ajour);
        $mention = Notes::getMention($moyen['moyenne']);
            $resultat  =[];
            $resultat['moyen']=$moyen['moyenne'];
            $resultat['data']=$credit_data['data'];
            $resultat['credit_obtenu']=$credit_data['credit_obtenu'];
            $resultat['semestre']=$semestre;
            $resultat['mention']=$mention;
            return $resultat ;
    }
    public static  function credit_obtenu($data,$moyen,$note_ajournee,$limit_ajour){
        $data_final = [];
        $credit_obtenu = 0;
        if ($moyen['moyenne'] >= 10)
        {
            foreach ($data as $ds )
            {
                if ($ds->note < $note_ajournee)
                {
                    $ds->resultats = 'Aj';
                }elseif($ds->note >= $note_ajournee && $ds->note < 10 && $moyen['ajour'] <= $limit_ajour){
                    $ds->resultats = '--';
                    $credit_obtenu = $moyen['credit'];
                }
            }
        }
        $data_final['credit_obtenu'] = $credit_obtenu;
        $data_final['data'] = $data;
       return $data_final;
    }

    public static function getMention($moyen){
        $tableau = [];
        $tableau[0]['moyen_min'] = 10;
        $tableau[0]['moyen_max'] = 12;
        $tableau[0]['moyen_valeur'] = 'moyen';
        $tableau[1]['moyen_min'] = 12;
        $tableau[1]['moyen_max'] = 14;
        $tableau[1]['moyen_valeur'] = 'assez bien';
        $tableau[2]['moyen_min'] = 14;
        $tableau[2]['moyen_max'] = 17;
        $tableau[2]['moyen_valeur'] = 'bien';
        $tableau[3]['moyen_min'] = 17;
        $tableau[3]['moyen_max'] = 20;
        $tableau[3]['moyen_valeur'] = 'tres bien';
        $tableau[4]['moyen_min'] = 0;
        $tableau[4]['moyen_max'] = 10;
        $tableau[4]['moyen_valeur'] = 'ajournee';

        $mention = '';
        foreach($tableau as $tab){
            if($moyen >= $tab['moyen_min'] && $moyen < $tab['moyen_max']){
                $mention = $tab['moyen_valeur'];
            }
        }
        return $mention;
    }
    public static function getMoyenne($data){
        $moyen = [];
        $notes = 0;
        $credit = 0;
        $credit_obtenu = 0;
        $nbr_ajour = 0;
        foreach ($data as $d )
        {
            $notes =$notes +  $d->note * $d->credit;
            $credit = $credit + $d->credit;
            $credit_obtenu = $credit_obtenu + $d->credit_obtenu;
            if ($d->note <10){
                $nbr_ajour = $nbr_ajour +1 ;
            }
        }
        $moyen['moyenne']  = $notes /($credit);
        $moyen['credit'] = $credit;
        $moyen['ajour'] = $nbr_ajour;
        return $moyen;
    }
    public static function getNotesParAnne($annee,$id){
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
        return $data;

    }
    public static function situation_et_moyenne($data){
        $tableau = [];
        $Situation = '';
        $moyenGenerale = 0;
        $moyenGlobale =0;
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
        $tableau['situation'] = $Situation;
        $tableau['moyen_general'] = $moyenGenerale;
        return $tableau;
    }
}
