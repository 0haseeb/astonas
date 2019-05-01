<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Animal;
use App\Adoption;
use Gate;
use Auth;

class AnimalController extends Controller
{
    // only allow method in controller if users is logged in.
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if gate denies return to home screen
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        // add all animals to $animals
        $animals = Animal::all()->toArray();
        // generate a redirect HTTP with Response animals array
        return view('animals.index', compact('animals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // accesschk gate denied means its a user. so, return home view
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        // else show create page
        return view('animals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        // form validation
        $animal = $this->validate(request(), [
        'name' => 'required',
        'dob' => 'required',
        'type' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2500',
      ]);
        //Handles the uploading of the image
        if ($request->hasFile('image')) {
            //Gets the filename with the extension
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            //just gets the filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Just gets the extension
            $extension = $request->file('image')->getClientOriginalExtension();
            //Gets the filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //Uploads the image
            $path =$request->file('image')->storeAs('public/images', $fileNameToStore);
        } else {
            // if request has no image set $fileNameToStore to noimage.jpg.
            $fileNameToStore = 'noimage.jpg';
        }
        // create a Animal object and set its values from the input
        $animal = new Animal;
        $animal->created_at = now();
        $animal->name = $request->input('name');
        $animal->dob = $request->input('dob');
        $animal->userid = 1;
        $animal->description = $request->input('description');
        $animal->type = $request->input('type');
        $animal->image = $fileNameToStore;
        // save the Animal object
        $animal->save();
        // generate a redirect HTTP response with a success message
        return back()->with('success', 'Animal has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find animal by the passed prameter.
        $animal = Animal::find($id);
        // direct to animals/show view with risponce animal array
        return view('animals.show', compact('animal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        $animal = Animal::find($id);
        return view('animals.edit', compact('animal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        $animal = Animal::find($id);
        $this->validate(request(), [
          'name' => 'required',
          'dob' => 'required',
          'type' => 'required',
          'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2500',
        ]);
        // set request inputo db colums
        $animal->name = $request->input('name');
        $animal->dob = $request->input('dob');
        $animal->description = $request->input('description');
        $animal->type = $request->input('type');
        $animal->updated_at = now();
        //Handles the uploading of the image
        if ($request->hasFile('image')) {
            //Gets the filename with the extension
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            //just gets the filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Just gets the extension
            $extension = $request->file('image')->getClientOriginalExtension();
            //Gets the filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //Uploads the image
            $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }
        $animal->image = $fileNameToStore;
        $animal->save();
        return redirect('animals')->with('success', 'Animal has been updated');
    }

    /**
     * handles adoption request
     *
     * id of the animal
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adopt($id)
    {
        //find animal by $id
        $animal = Animal::find($id);
        //$animal = Animal::select('userid')->where('id', $id)->get();
        // if gate 'accesschk' denies
        if (Gate::denies('accesschk')) {
            //if animlal is owned by a user
            if ($animal->userid != 1) {
                // generate a redirect HTTP response with a error message
                return back()->with('error', 'Animal already adopted');
            } else { // if animal is owned by admin
                // call adoption method and pass it $id
                $this->adoption($id);
                return back()->with('success', 'Adoption Request Send, You will recieve this animal if request is aproved');
            }
        }
        // if admin is adopting the animal back
        // if userid field on animlas table = 1,(admin already owns the animal)
        if ($animal->userid == 1) {
            return back()->with('error', 'Animal hasnot been adopted yet');
        } else {// if animal is owned by a user
            // call adoption method and pass it $id
            $this->adoption($id);
            // generate a redirect HTTP response with a success message
            return back()->with('success', 'You will recieve this animal back once you accept the request');
        }
    }

    /**
     * Store adoption request
     *
     * id of the animal
     * @param  int  $id
     */
    public function adoption($id)
    {
        $userId = Auth::id();
        // create a Adoption object and set its values
        $adoption = new Adoption;
        $adoption->created_at = now();
        $adoption->animalid = $id;
        $adoption->userid = $userId;
        // save the Adoption object
        $adoption->save();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // return normal users to home only allow admin
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        // find animal by id
        $animal = Animal::find($id);
        // delete the record
        $animal->delete();
        // generate a redirect HTTP response with a success message
        return redirect('animals')->with('success', 'Animal has been deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Animals id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showtouser($id)
    {
        $animal = Animal::find($id);
        return view('showtouser', compact('animal'));
    }

    /**
     * show each user thier past Adoption Requests but show all Adoption requests to admin
     *
     * @return \Illuminate\Http\Response
     */
    public function showadaptions()
    {
        // add all adoption requests to $adoptionsQuery
        $adoptionsQuery = Adoption::all();
        // if its a nornal user then only add adoption with user id of logged in user
        if (Gate::denies('accesschk')) {
            $adoptionsQuery=  $adoptionsQuery->where('userid', auth()->user()->id);
        }
        // generate a redirect HTTP response with $adoptionsQuery array
        return view('/showadaptions', array('adoptions'=>  $adoptionsQuery));
    }

    /**
     * show pending adoptions
     *
     * @return \Illuminate\Http\Response
     */
    public function showpendingadaptions()
    {
        // return normal users to home only allow admin
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        // load all adoptions
        $adoptionsQuery = Adoption::all();
        // set $adoptionsQuery to adoption request where status is pending
        $adoptionsQuery=$adoptionsQuery->where('status', 'pending');
        return view('showpendingadaptions', array('adoptions'=>$adoptionsQuery));
    }

    /**
     * show the form for handling adoption request.
     *
     * adoption id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function handleadoptionrequest($id)
    {
        // return normal users to home only allow admin
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        // find adoption by id
        $adoption = Adoption::find($id);
        // generate a redirect HTTP response with $adoption
        return view('handleadoptionrequest', compact('adoption'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     *adoptoipn id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adoptionrequest(Request $request, $id)
    {
        // return normal users to home only allow admin
        if (Gate::denies('accesschk')) {
            return view('home');
        }
        //find adoption by id
        $adoption = Adoption::find($id);
        //set $userid to userid in adoption record
        $userid = $adoption->userid;
        //set $animalid to animalid in adoption record
        $animalid=$adoption->animalid;
        //find animal by $animalid
        $animal = Animal::find($animalid);
        //
        $status = $request->input('status');
        // if status is denied change the status value to denied in data base record
        if ($status =='denied') {
            $adoption->updated_at = now();
            $adoption->status = $status;
            $adoption->save();
            return redirect('showpendingadaptions')->with('success', 'Adoption Request sucessfully Denied');
        }
        // if status is approved, change the status value to approved in adoption table record and set userid in animls table to userid of user who requested the adoption
        if ($status =='approved') {
            $adoption->updated_at = now();
            $adoption->status = $status;
            $animal->userid =$userid;
            if($userid==1){
              $animal->availability = 'available' ;
            }else{
            $animal->availability = 'unavailable' ;
            }
            $animal->updated_at = now();
            // save the adoption object
            $adoption->save();
            // save the animal object
            $animal->save();
            return redirect('showpendingadaptions')->with('success', 'Adoption Request sucessfully Aproved');
        }
        // if satus is left on pending then show message no change applied
        if ($status =='pending') {
            return redirect('showpendingadaptions')->with('success', 'No Change Made');
        }
    }


    /**
     * show animal owned by user, however admin can see all animal and who owns them
     *
     * @param  int
     * @return \Illuminate\Http\Response
     */
    public function myanimals()
    {
      // load all animal
        $animalssQuery = Animal::all();
        // if gate denis "accesschk" (if its a normal user)
        if (Gate::denies('accesschk')) {
          // only show animls where field userd is id of loggen in user
            $animalssQuery=$animalssQuery->where('userid', auth()->user()->id);
        }
        return view('/myanimals', array('animals'=>$animalssQuery));
    }


    /**
     * used to search animal by a spicified type.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchtype(Request $request)
    {
        // load all animals
        $animalssQuery = Animal::all();
        $this->validate(request(), [
          'type' => 'required',
        ]);
        $type=$request->input('type');
        // put animals with type $type in $animalssQuery
        $animalssQuery=$animalssQuery->where('type', $type) ;
        // generate a redirect HTTP response with $animalssQuery array
        return view('/showavaliable', array('animals'=>$animalssQuery));
    }
    /**
     * show animals avaliable for adoption
     *
     * @param  int
     * @return \Illuminate\Http\Response
     */
    public function showavaliable()
    {
        $animalssQuery = Animal::all();
        if (Gate::denies('accesschk')) {
          // show animls where User id = 1 ( show animals which have not been adopted yet)
            $animalssQuery=$animalssQuery->where('userid', 1);
        }
        return view('/showavaliable', array('animals'=>$animalssQuery));
    }
}
