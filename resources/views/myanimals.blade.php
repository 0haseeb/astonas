@extends('layouts.app')
@section('content')
<div class="container">
 <div class="row justify-content-center">
 <div class="col-md-8">
 <div class="card">
 <div class="card-header">My Animals</div>
 <div class="card-body">
 @if (session('status'))
 <div class="alert alert-success">
 {{ session('status') }}
 </div>
 @endif
 <table class="table table-striped table-bordered table-hover">
 <thead>
 <tr>
 <th> id</th><th> User-id</th><th> Animal's Name</th>
 <th> Date of bith </th><th> Type </th> <th> More Details </th>
 </tr>
 </thead>
 <tbody>
@foreach($animals as $animal)
<tr>
<td> {{$animal->id}} </td>
<td> {{$animal->userid}} </td>
<td> {{$animal->name}} </td>
<td> {{$animal->dob}} </td>
<td> {{$animal->type}} </td>
<td>
  <a href="{{action('AnimalController@showtouser', $animal['id'])}}" class="btn btn- primary">Details
  </a>
</td>

</tr>
@endforeach
 </tbody>
 </table>
 </div>
 </div>
 </div>
 </div>
</div>
@endsection
