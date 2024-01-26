<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDocument;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;

class OtherDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = UserDocument::where('document_type', 'other')->get();
        return view('student.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        // 	'document_type' => 'required',
        // 	'document_type_id' => 'required',
        // 	'document_file' => 'required'
        // ]);

        // if ($validator->fails()) {
        // }
        // Prepare filename
        $fileName = Str::slug($request->document_name, "_") . "_" . Auth::id() . "_" . time() . "." . $request->document_file->getClientOriginalExtension();



        // Upload File - At present only one file at a time
        $upload_result = $request->document_file->move(public_path('user_documents'), $fileName);
        if (!$upload_result) {
            return response()->json([
                'success' => false,
                'code' => 'error',
                'title' => 'Error!',
                'message' => 'Something went wrong.'
            ]);
        }

        $result = $this->transactionalQuery($fileName, $request);

        if ($result['success']) {
            foreach ($result['files_to_delete'] as $file_name) {
                unlink(public_path($file_name));
            }
            return response()->json([
                'success' => true,
                'title' => 'Congratulations!',
                'message' => 'Document uploaded successfully',
                'code' => 'success'
            ]);
        } else {
            unlink(public_path("user_documents/" . $fileName));
            return response()->json([
                'success' => false,
                'title' => 'Error!',
                'message' => 'Something went wrong',
                'code' => 'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
