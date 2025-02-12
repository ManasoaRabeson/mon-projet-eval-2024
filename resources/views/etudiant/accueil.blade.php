@extends('baseEtudiant')
@section('content')
    <h1>Welcome student : {{$etudiant->etu}}</h1>
    <h4>Nom : {{$etudiant->nom}}</h4>
    <h4>Prenom : {{$etudiant->prenom}}</h4>
@endsection
