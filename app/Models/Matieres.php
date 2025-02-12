<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Table;

class Matieres extends Model
{
    use HasFactory;
    protected $table = 'matiere';
    public function getSemestre(){
        return DB::table('semestre')
            ->get();
    }
}
