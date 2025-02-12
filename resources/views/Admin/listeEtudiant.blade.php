@extends('baseAdmin')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row">
        <form id="" class="mb-3" action="{{ url('Admin/listeEtudiants') }}" method="GET">
            <div class="row p-4">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="defaultSelect" class="form-label">Promotion</label>
                                <select name="idprom" id="defaultSelect" class="form-select">
                                <option value="0">tous</option>
                                    @foreach($promotion as $prom)
                                        <option value="{{$prom-> id_promotion}}">{{$prom->nom}}</option>
                                    @endforeach

                                        </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nom</label>
                                <input
                                    type="text"
                                    class="form-control @error('nom') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="nom"
                                    value="{{ old('nom') }}"
                                />
                                @error('nom')
                                <div class="xxx">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div><button type="submit" class="btn btn-primary">Valider</button></div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="card">
            <h5 class="card-header">Liste des etudiants</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>etu</th>
                        <th>nom</th>
                        <th>prenom</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @if ($etudiants !=null)
                        @foreach ($etudiants as $et)

                               <tr>
                                <td>{{ $et->etu}}</td>
                                <td>{{ $et->nom}}</td>
                                <td>{{ $et->prenom}}</td>
                                   <td> <a class="dropdown-item" href="{{url('Admin/listeSemestre/'.$et->id_etudiant)}}">
                                      <button class="btn btn-primary">info</button>
                                   </a>
                                   </td>

                                   <td>
                                       <a class="dropdown-item" href="{{url('Admin/listeAnnee/'.$et->id_etudiant)}}">
                                           <button class="btn btn-primary">Annee</button>
                                       </a>
                                   </td>
                            </tr>

                        @endforeach
                    @endif

                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
