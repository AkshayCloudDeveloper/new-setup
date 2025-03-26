<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function submitForm(Request $request)
    {
        return response()->json(['message' => 'Form submitted successfully!','data'=>$request->all()]); // Adjust response format as needed (e.g., JSON) if needed. If not, you can return the message directly. 
    }
}
