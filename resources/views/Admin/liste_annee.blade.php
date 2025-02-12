@extends('baseAdmin')
@section('content')

    <div class="row">
        <div class="card">
            <h5 class="card-header">Annee parcourue</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Annee</th>


                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @for($i = 1 ;$i<= 3 ;$i++)

                            <tr>
                                <td>
                                    <a class="dropdown-item" href="{{route('admin.listeNoteAnnee',['id'=>$id_etudiant,'annee'=>$i])}}">
                                    {{ $i}} </a></td>
                            </tr>

                        @endfor


                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
