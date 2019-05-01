@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="alert alert-success" role="alert">
                          You are logged in as a user
                    </div>
                    </br>
                    <h4> Welcome back <?=$name = Auth::user()->name;?> </h4></br>
                    <a href="{{ route('myanimals') }}" class="btn btnprimary">My aminals </a> </br> </br>
                    <a class="btn btnprimary" href="{{ url('showavaliable') }}" >Show Avaliable aminals </a> </br> </br>
                    <a class="btn btnprimary" href="{{ url('showadaptions') }}" >Show My Adoption requests </a></br> </br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
