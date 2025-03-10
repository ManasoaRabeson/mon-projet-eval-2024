<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Etudiants extends Model
{
    use HasFactory;
    protected $table = 'etudiants';
    public function traitement_liste_etudiants($data){
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
        return $etudiants;
    }

}
