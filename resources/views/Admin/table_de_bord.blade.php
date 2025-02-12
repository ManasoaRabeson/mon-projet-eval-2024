@extends('baseAdmin')
@section('content')
{{--    <div class="row">--}}
{{--        <div class="col-lg">--}}
{{--            <div class="card mb-4">--}}
{{--                <h5 class="card-header">Resultat admission en licence</h5>--}}
{{--                <table class="table table-borderless">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}

{{--                        <td class="py-3">--}}
{{--                            <h3 class="mb-0">Nombre etudiant : </h3>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr >--}}

{{--                        <td class="py-3">--}}
{{--                            <h3 class="mb-0"> Non admis : </h3>--}}
{{--                        </td>--}}
{{--                        @foreach($non_admis as $na)--}}
{{--                        <td >--}}

{{--                                {{$na->nom}}--}}
{{--                            {{$na->prenom}}--}}
{{--                           </td>--}}
{{--                        @endforeach--}}
{{--                    </tr>--}}
{{--                    <tr >--}}

{{--                        <td class="py-3">--}}
{{--                            <h3 class="mb-0">Admis : {{$nbr_afaka}}</h3>--}}
{{--                        </td>--}}
{{--                        @foreach($admis as $a)--}}
{{--                            <td >--}}

{{--                               <p>{{$a->nom}}       {{$a->prenom}}</p>--}}

{{--                            </td>--}}
{{--                        @endforeach--}}
{{--                    </tr>--}}

{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <h5 class="pb-1 mb-4">Liste admis en licence</h5>
    <div class="row mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nombre etudiant</h5>
                    <p class="card-text">{{$nbr_etudiant}}</p>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Admis</h5>
                    <p class="card-text">{{$nbr_afaka}}</p>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card text-end mb-3">
                <div class="card-body">
                    <h5 class="card-title">Non admis</h5>
                    <p class="card-text">{{$nbr_tsy_afaka}}</p>

                </div>
            </div>
        </div>
    </div>
@endsection
