<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!

*/

Route::get('/', function () {
    return redirect('etudiant/login');
});

//LOGIN ADMIN
Route::get('admin/login',[\App\Http\Controllers\AuthController::class,'login'])->name('auth.login');
Route::post('admin/traiteLogin',[\App\Http\Controllers\AuthController::class,'doLogin']);
Route::get('createAdmin', [\App\Http\Controllers\AuthController::class,'creerAdmin']);
Route::get('admin/logout', [\App\Http\Controllers\AuthController::class,'logoutAdmin']);


Route::get('reinitialiser', [\App\Http\Controllers\AuthController::class,'trun']);
Route::group(['prefix'=>'Admin','middleware'=>'admin'],function ()
{
    Route::get('/formNote', [\App\Http\Controllers\AdminController::class,'formulaireNote']);
    Route::get('/insertNote', [\App\Http\Controllers\AdminController::class,'insertNotes']);
    Route::get('/listeEtudiants', [\App\Http\Controllers\AdminController::class,'getListeEtudiants']);
    Route::get('/listeSemestre/{id}', [\App\Http\Controllers\AdminController::class,'listeSemestre']);
    Route::get('/listeNoteParSemestre/{id}/{semestre}', [\App\Http\Controllers\AdminController::class,'listeNoteParSemestre'])
    ->name('admin.listeNote');
    Route::get('/tableauDeBord', [\App\Http\Controllers\AdminController::class,'tableau_de_bord']);
    Route::get('/listeAnnee/{id}', [\App\Http\Controllers\AdminController::class,'listeAnnee']);
    Route::get('/listeNoteParAnne/{id}/{annee}', [\App\Http\Controllers\AdminController::class,'listeNoteParAnnee'])
    ->name('admin.listeNoteAnnee');
});
//IMPORT VIEW
Route::get('Import/formulaire', function () {
    return view('admin.Import');
});
//IMPORT TRAITE
Route::group(['prefix'=>'Import','middleware'=>'admin'],function ()
{
    Route::post('/importCsv', [\App\Http\Controllers\ImportController::class,'importCsv']);
});


//Etudiant login
Route::get('etudiant/login', function () {
    return view('auth.loginEtudiant');
});
Route::get('etudiant/logout', [\App\Http\Controllers\AuthStudentController::class,'Logout']);
Route::post('etudiant/traiteLogin', [\App\Http\Controllers\AuthStudentController::class,'doLoginStudent']);

//ETUDIANT TRAITE
Route::group(['prefix'=>'etudiant','middleware'=>'etudiant'],function ()
{
    Route::get('/accueil', [\App\Http\Controllers\EtudiantController::class,'Accueil']);
    Route::get('/listeSemestre', [\App\Http\Controllers\EtudiantController::class,'liste_semestre']);
    Route::get('/listeNote/{id}', [\App\Http\Controllers\EtudiantController::class,'liste_note'])
        ->name('etudiant.listeNote');
});

Route::get('/admin', function () {
    return view('baseAdmin');
});

