@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-success" onclick="window.location ='{{route('add-job')}}'">New Job +</button>
                    </div>

                    <div class="card-body table-responsive">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <table class="table table-bordered table-dark">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Algorithm</th>
                                    <th scope="col">Crack</th>
                                    <th scope="col">Submit At</th>
                                    <th scope="col">Finished At</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listJob as $i)
                                @component('row-job')
                                        @slot('algorithm')
                                            {{ $i->algorithm }}
                                        @endslot
                                        @slot('crack')
                                            {{$countCrackHash[$i->id]}} / {{ $countUnCrackHash[$i->id] }}
                                        @endslot
                                        @slot('submit')
                                            {{ $i->created_at }}
                                        @endslot
                                        @slot('finished')
                                            @if($i->status == 'finished') {{$i->updated_at}} @endif
                                        @endslot
                                        @slot('status')
                                            {{ ucfirst($i->status) }}
                                        @endslot
                                        @slot('job_id')
                                            {{ $i->id }}
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
