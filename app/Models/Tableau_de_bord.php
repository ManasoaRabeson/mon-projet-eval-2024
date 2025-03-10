<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Tableau_de_bord extends Model
{
    use HasFactory;
    public function admis_et_non_admins($etudiants,$data,$limit_ajour){
        $nbr_etudiant = 0;
        $nbr_afaka = 0;
        $nbr_tsy_afaka = 0;
        $tableau = [];
        $resultat = [];

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
                $resultat['admis']= $e ;
            }
            elseif($total_credit < 180)
            {
                $nbr_tsy_afaka = $nbr_tsy_afaka +1 ;
                    $resultat['non_admis'] = $e;
            }
            $nbr_etudiant = $nbr_etudiant +1;
        }
        $tableau['afaka'] = $nbr_afaka;
        $tableau['tsy_afaka'] = $nbr_tsy_afaka;
        $tableau['admis'] = $resultat['admis'];
        $tableau['non_admis'] = $resultat['non_admis'];
        $tableau['nbr_etudiant'] = $nbr_etudiant;
        
      return $tableau;
    }
}
