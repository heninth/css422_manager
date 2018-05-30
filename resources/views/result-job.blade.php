@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Result</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <div class="row">
                                <div class="col-sm-8">id job: {{$job->id}}</div>
                                <div class="col-sm-4">status: {{$job->status}}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Algorithm: {{$job->algorithm}}</div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Hash count: {{$countResult}}</div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">Submit At: {{$job->created_at}}</div>
                                <div class="col-sm-4"></div>
                            </div>

                            <table class="table table-dark">
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
