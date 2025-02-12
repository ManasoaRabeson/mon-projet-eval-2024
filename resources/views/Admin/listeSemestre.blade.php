@extends('baseAdmin')
@section('content')
    <div class="row">
        <div class="card">
            <h5 class="card-header">Liste semestre</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>numero</th>

                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @if ($semestre !=null)
                        @foreach ($semestre as $sm)

                            <tr>
                                <td><a href="{{route('admin.listeNote',['id'=>$idetudiant,'semestre'=>$sm->id])}}">{{ $sm->id}}</a></td>
                                <td>{{$table[$sm->id]}}</td>
                            </tr>

                        @endforeach
                    @endif

                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
