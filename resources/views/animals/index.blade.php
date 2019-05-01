@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 ">
      <div class="card">
        <div class="card-header">Show all Animals
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Id
                </th>
                <th>Animal's name
                </th>
                <th>owner id
                </th>
                <th>date of birth
                </th>
                <th>type
                </th>
                <th colspan="3">Action
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach($animals as $animal)
              <tr>
                <td>{{$animal['id']}}
                </td>
                <td>{{$animal['name']}}
                </td>
                <td>{{$animal['userid']}}
                </td>
                <td>{{$animal['dob']}}
                </td>
                <td>{{$animal['type']}}
                </td>
                <td>
                  <a href="{{action('AnimalController@show', $animal['id'])}}" class="btn btn- primary">Details
                  </a>
                </td>
                <td>
                  <a href="{{action('AnimalController@edit', $animal['id'])}}" class="btn btn- warning">Edit
                  </a>
                </td>
                <td>
                  <form action="{{action('AnimalController@destroy', $animal['id'])}}"
                        method="post"> @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" onclick="return confirm('Are you sure, Animal will delete perminantly even if someone owns it?')" type="submit"> Delete
                    </button>
                  </form>
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
