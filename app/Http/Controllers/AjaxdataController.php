<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Student;
use Datatables;

class AjaxdataController extends Controller
{
    function index()
    {
     return view('student.ajaxdata');
    }

    function getdata()
    {
     $students = Student::select('first_name', 'last_name');
     // vrati Datatables plugin iz tabele studentsl obavezno make(true)
     return Datatables::of($students)->make(true);
    }

    //posalji podatke
    function postdata(Request $request)
    {
        //koristi Validator
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);



        //smestanje gresaka u niz
        $error_array = array();
        //pocetna vrednost uspesnog outputa
        $success_output = '';
        // ako propadnu uslovi validacije
        if ($validation->fails())
        {   //
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $student = new Student([
                    'first_name'    =>  $request->get('first_name'),
                    'last_name'     =>  $request->get('last_name')
                ]);
                $student->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }
}
