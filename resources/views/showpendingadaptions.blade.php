@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 ">
      <div class="card">
        <div class="card-header">Pending Adoption Requests
        </div>
        @if (\Session::has('success'))
        <div class="alert alert-success">
          <p>{{ \Session::get('success') }}
          </p>
        </div>
        <br />
        @endif
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Request ID
                </th>
                <th>Animal's id
                </th>
                <th>User id
                </th>
                <th>Status
                </th>
                <th colspan="1">Action
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach($adoptions as $adoption)
              <tr>
                <td>{{$adoption['id']}}
                </td>
                <td>{{$adoption['animalid']}}
                </td>
                <td>{{$adoption['userid']}}
                </td>
                <td>{{$adoption['status']}}
                </td>
                  <td>
                    <a href="{{action('AnimalController@handleadoptionrequest', $adoption['id'])}}" class="btn btn- primary">Resolve Adoption Request
                    </a>
                  </td>
                                  {{--
                                <td>
                                  <a href="{{action('AnimalController@edit', $animal['id'])}}" class="btn
                                                                                                        btn- warning">Edit
                                  </a>
                                </td>
                                <td>
                                  <form action="{{action('AnimalController@destroy', $animal['id'])}}"
                                        method="post"> @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger" type="submit"> Delete
                                    </button>
                                  </form>
                                </td>
                              </tr>
                            --}}


              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
