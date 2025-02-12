<?php

namespace App\Http\Controllers;

use App\Imports\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importCsv(Request $request){
        //CONFIG
        $rr = Excel::toArray(new Import(),$request->file('config'))[0];
        $ruless = [
            'code' => 'required',
            'config' =>'required',
            'valeur' =>'required'
        ];
        $customMessagess = [
            'code.required' => 'code est requis',
            'config.required' =>'config est requis',
            'valeur.required' =>'config est requis'
        ];
        $i=1;
        foreach ($rr as $row){
            $validator = Validator::make($row, $ruless, $customMessagess);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                foreach ($errors as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            } else {
                try{
                    DB::table('configuration')->insert([
                        'code' => $row['code'],
                        'config' =>$row['config'],
                        'valeur' => $row['valeur']
                        ]);
                }catch(\Exception $e){
                    $erreur[]=$e->getMessage().' : Erreur a la ligne'.' '.$i.' : '.$row['type'].','.$row['commission'];
                }
            }
            $i++;
        }

        //NOTE
        $r = Excel::toArray(new Import(),$request->file('note'))[0];

        $rules = [
            'numetu' => 'required',
            'nom' =>'required',
            'prenom' => 'required',
            'genre' =>'required',
            'datenaissance' =>'required',
            'promotion' =>'required',
            'codematiere' =>'required',
            'semestre' =>'required',
            'note' =>'required'

        ];
        $customMessages = [
            'numetu.required' => 'NumETU est requis',
            'nom.required' =>'Nom est requis',
            'prenom.required' => 'Prenom est requis',
            'genre.required' =>'Genre est requis',
            'datenaissance.required' => 'DateNaissance est requis',
            'promotion.required' => 'Promotion est requis',
            'codematiere.required' => 'CodeMatiere est requis',
            'semestre.required' => 'Semestre est requis',
            'note.required' => 'Note est requis'
        ];

        $erreur = [];
        $validation=[];
        $i=1;
        foreach ($r as $row){
            $validator = Validator::make($row, $rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                foreach ($errors as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            } else {
                try{

                    DB::table('import')->insert([
                        'etu' => $row['numetu'],
                        'nom' =>$row['nom'],
                        'prenom' => $row['prenom'],
                        'genre' => $row['genre'],
                        'date_de_naissance' => $row['datenaissance'],
                        'promotion' => $row['promotion'],
                        'code_matiere' => $row['codematiere'],
                        'semestre' => $row['semestre'],
                        'note' => str_replace(',', '.',$row['note']),
                    ]);
                }catch(\Exception $e){
                    $erreur[]=$e->getMessage().' : Erreur a la ligne'.' '.$i.' : '.$row['numetu'].','.$row['nom'].',
                    '.$row['prenom'].','.$row['genre'].','.$row['datenaissance'].','.$row['promotion'].
                        ','.$row['codematiere'].','.$row['semestre'].','.$row['note'];
                }
            }
            $i++;
        }
        try{
            DB::insert('insert into promotion(nom) select promotion from import
                                                       group by promotion');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        try{
            DB::insert('insert into etudiants(etu,nom,prenom,genre,date_de_naissance,promotion)
                select im.etu,im.nom,im.prenom,im.genre,im.date_de_naissance,pr.id_promotion
                from import im
                join promotion pr on im.promotion = pr.nom
               group by im.etu,im.nom,im.prenom,im.genre,im.date_de_naissance,pr.id_promotion');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }
        try{
            DB::insert('insert into notes(etudiant_id,matiere_id,note)
                select e.id_etudiant,m.id_matiere,im.note
                from import im
                    join  matiere m  on m.code = im.code_matiere
                     join  etudiants e on im.etu = e.etu
                    ');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }


        return view('admin.Import',[
            'validation' => $validation,
            'erreur' => $erreur,
        ]);
    }
}
