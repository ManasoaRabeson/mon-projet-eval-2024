<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Semestre extends Model
{
    use HasFactory;
    protected $table = 'semestre';
    public function getSemestre(){
        return DB::table('semestre')
            ->get();
    }
    public function moyenne_semestre($id){
        $data = $this->getSemestre();
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
        return $table;
    }

}
