<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Validator;

class StudentController extends Controller
{
    public function index(){
        return Student::all();
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         //
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         $rules = [
            'name' => 'required|unique:students',
            'city' => 'required',
            'fees' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
 
        if ($validator->fails()) {
            return response([
                'status' => 'fail',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 400);
        }

         return Student::create($request->all());
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         return Student::find($id);
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         //
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
         $data = Student::find($id);
         return $data->update($request->all());
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         return Student::find($id)->delete();
     }
 
     public function search($city){
         return Student::where('city',$city)->get();
     }
}
