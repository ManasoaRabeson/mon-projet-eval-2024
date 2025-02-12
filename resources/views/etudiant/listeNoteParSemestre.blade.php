@extends('baseEtudiant')
@section('content')
    <div class="row">
        <div class="card">

            <div class="table-responsive text-nowrap">
                <p>Nom : {{$etudiants->nom}}
                    <br>
                    Prénom(s) : {{$etudiants->prenom}}
                    <br>
                    Né(e) : {{$etudiants->date_de_naissance}}
                    <br>
                    N° d'inscription  : {{$etudiants->etu}}
                    <br>
                    a obtenu les notes suivantes :
                </p>
                <table class="table">
                    <thead>
                    <tr>
                        <th>UE</th>
                        <th>Intitulé</th>
                        <th>Credit</th>
                        <th>Note / 20</th>
                        <th>resultat</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach ($note as $n)

                        <tr>
                            <td>{{$n->code}}</td>
                            <td>{{$n->nom}}</td>
                            <td>{{$n->credit_obtenu}}</td>
                            <td>{{number_format($n->note,2,',' ,' ')}}</td>
                            <td>{{$n->resultats}}</td>

                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>Semestre {{$semestre}}</td>
                        <td>{{$credit}}</td>
                        <td>{{number_format($moyen, 2, ',', ' ')}}</td>
                        <td></td>
                    </tr>


                    </tbody>
                </table>
                <p>Resultat : <br>credit :{{$credit}}
                    <br> Moyenne generale : {{number_format($moyen, 2, ',', ' ')}}
                    <br>
                    Mention : {{$mention}}
                    <br>
                    @if($moyen<10) Ajourne @else Admis
                    @endif

                </p>

            </div>
        </div>

    </div>
@endsection
