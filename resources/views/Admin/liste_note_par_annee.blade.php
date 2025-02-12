@extends('baseAdmin')
@section('content')
    <div class="row">
        <div class="card">
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
        @for($i = 0;$i<count($data);$i++)

            <div class="table-responsive text-nowrap">
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
                    @foreach ($data[$i]['data'] as $n)
                        <tr>
                            <td>{{$n->code}}</td>
                            <td>{{$n->nom}}</td>
                            <td>{{$n->credit_obtenu}}</td>
                            <td>{{number_format($n->note,2,',' ,' ')}}</td>
                            <td>{{$n->resultats}}</td>


                        </tr>
                    @endforeach


                    </tbody>
                    <tr>
                        <td>MOYEN : {{$data[$i]['moyen']}}</td>
                        <td>SEMESTRE : {{$data[$i]['semestre']}}</td>
                    </tr>

                </table>


            </div>
            @endfor
            <p>Resultat
                <br> Moyenne generale : {{number_format($moyenGenerale, 2, ',', ' ')}}
                <br>
                Mention :@if($Situation == 'Admis')
                <p class="bg bg-success"> {{$Situation}}</p>
            @elseif($Situation == 'Ajournee')
                <p class="bg bg-danger"> {{$Situation}}</p>
            @endif
            <br>


            </p>
        </div>

    </div>
@endsection
