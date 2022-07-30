<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Validator;

class FileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $firmwares = FileUpload::paginate(10);

        $response = [
            'data' => $firmwares,
            'message' => "all firmwares",
        ];

        return response()->json($response);        
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
        $validator = Validator::make($request->all(), [
            'device_model' => 'required',
            'firmware_version' => 'required',         
            'firmware_file' => 'required',
        ]);

        if($validator->fails()){
            return  response()->json($validator->errors(), 400);       
        }
      
        $input = $request->all();
        Log::info("upload file starting".$request->file('firmware_file'));

        //file 1 store      
        if ($file = $request->file('firmware_file')) {
            Log::info("inside upload url");

            $extension = $request->file('firmware_file')->getClientOriginalExtension();
            
            $filename = $file->getClientOriginalName(); 
            
            $path = Storage::disk('local')->putFileAs('/firmwares/', $request->file('firmware_file'), $filename);

            $input['firmware_file'] = storage_path('firmwares/').$filename;
            
            Log::info("FILE STORED".$input['firmware_file']);
        }

        $firmware = FileUpload::create($input);

        $response = [
            'data' => $firmware,
            'message' => "Successfull upload",
        ];

        return response()->json($response);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function show(FileUpload $fileUpload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function edit(FileUpload $fileUpload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FileUpload $fileUpload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileUpload $fileUpload)
    {
        //
    }
}
