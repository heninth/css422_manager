@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add New Job</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li> {!! session()->get('error') !!}</li>
                                </ul>
                            </div>
                        @endif
                        @if (session('duplicate'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li> {!! session()->get('duplicate') !!}</li>
                                </ul>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{route('save-job')}}" method="POST">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-sm-3">Algorithm</div>
                                <div class="col-sm-6 input-group mb-3">
                                    <select class="custom-select" name="algorithm">
                                        <option value="md5">MD5</option>
                                        <option value="sha1">Sha1</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">Plain Text Range</div>
                                <div class="col-sm-3 input-group mb-3">
                                    <select class="custom-select" name="min_length">
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                                to
                                <div class="col-sm-3">
                                    <select class="custom-select" name="max_length">
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">Hash<br>(1 hash per line)</div>
                                <div class="col-sm-8 input-group mb-3">
                                    <textarea class="form-control" name="hash" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-success" style="width: 100px">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
