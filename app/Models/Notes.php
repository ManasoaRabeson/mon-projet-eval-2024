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

return DB::table('configuration')
    ->get();

    }

    public static function getSemestre($id,$semestre){
        $note = new Notes();
        $conf = $note->getconfiguration();


        $note_ajournee = $conf[0]->valeur;
        $limit_ajour = $conf[1]->valeur;


        $note = new Notes();
        $data = $note->getNote($id,$semestre);

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

            $resultat  =[];
            $resultat['moyen']=$moyen;
            $resultat['data']=$data;
            $resultat['credit_obtenu']=$credit_obtenu;
            $resultat['semestre']=$semestre;
            $resultat['mention']=$mention;
            return $resultat ;
    }
}
