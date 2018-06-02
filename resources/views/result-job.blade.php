@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Result</div>

                    <div class="card-body table-responsive">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <div class="row mb-2">
                                <div class="col-sm-2">id job </div>
                                <div class="col-sm-4">: {{$job->id}}</div>
                                <div class="col-sm-4">status :{{$job->status}}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2">Algorithm </div>
                                <div class="col-sm-4">: {{$job->algorithm}}</div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2">Hash count </div>
                                <div class="col-sm-4">: {{$countResult}}</div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-2">Submit At </div>
                                <div class="col-sm-4">: {{$job->created_at}}</div>

                            </div>

                            <table class="table table-bordered table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">Hash</th>
                                        <th scope="col">Plain</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($listResult as $i)
                                    @component('row-result')
                                        @slot('hash')
                                            {{$i->hash}}
                                        @endslot
                                        @slot('plain')
                                            {{$i->plain}}
                                        @endslot
                                    @endcomponent
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
