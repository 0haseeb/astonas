@extends('layouts.app', ['title' => 'About'])
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 ">
      <div class="card">
        <div class="card-header">Animals Details
        </div>
        <!-- display the errors -->
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul> @foreach ($errors->all() as $error)
            <li>{{ $error }}
            </li> @endforeach
          </ul>
        </div>
        <br /> @endif
        @if (\Session::has('error'))
        <div class="alert alert-danger">
          <p>{{ \Session::get('error') }}
          </p>
        </div>
        <br /> @endif


        <!-- display the success status -->
        @if (\Session::has('success'))
        <div class="alert alert-success">
          <p>{{ \Session::get('success') }}
          </p>
        </div>
        <br /> @endif
        <div class="card-body">
          <table class="table table-striped" border="2" >

            <tr>
              <th>Animals ID
              </th>
              <td>{{$animal->id}}
              </td>
            </tr><tr>
              <th>Animal's Name
              </th>
              <td>{{$animal->name}}
              </td>
            </tr>
            <tr>
              <th>Dade of birth
              </th>
              <td>{{$animal->dob}}
              </td>
            </tr>
            <tr>
              <th>Type
              </th>
              <td>{{$animal->type}}
              </td>
            </tr>
            <tr>
              <th>Description
              </th>
              <td style="max-width:150px;" >{{$animal->description}}
              </td>
            </tr>
            <tr>
              <th colspan='2' >
                <img style="width:100%;height:100%"
                     src="{{ asset('storage/images/'.$animal->image)}}">
            </td>
            </tr>
          </table>
        <table>
          <tr>
            <td>
              <a href="{{URL::previous()}}"class="btn btn-primary" role="button">Back to list
              </a>
            </td>
            <td>
                <form class="form-horizontal"  action="{{ action('AnimalController@adopt',$animal['id']) }} " enctype="multipart/form-data" >
                  @method('PATCH')
                  @csrf
                  <button class="btn btn-primary" type="submit">Request Adoption
                  </button>
                </form>
            </td>


          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
