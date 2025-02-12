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
        <form id="" class="mb-3" action="{{ url('Admin/insertNote') }}" method="GET">
            <div class="row p-4">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">ETU ETUDIANT</label>
                                <input
                                    type="text"
                                    class="form-control @error('etu') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="etu"
                                    value="{{ old('etu') }}"
                                />
                                @error('etu')
                                <div class="xxx">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Code MATIERE</label>
                                <input
                                    type="text"
                                    class="form-control @error('matiere') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="matiere"
                                    value="{{ old('matiere') }}"
                                />
                                @error('matiere')
                                <div class="xxx">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Notes</label>
                                <input
                                    type="number"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="notes"
                                    value="{{ old('notes') }}"
                                />
                                @error('notes')
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

@endsection
