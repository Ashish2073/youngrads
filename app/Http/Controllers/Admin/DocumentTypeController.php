<?php

namespace App\Http\Controllers\Admin;

use App\Models\DocumentType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
  public $documentLimits = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

  public function __construct()
  {
    $this->middleware('auth:admin');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $documentTyps = DocumentType::all();
    if (request()->ajax()) {

      return Datatables::of($documentTyps)
        ->editColumn('is_required', function ($row) {
          if ($row->is_required === 1) {
            return "Yes";
          } else if ($row->is_required == 0) {
            return "No";
          }
        })
        ->rawColumns(['is_required'])
        ->make(true);
    } else {
      $breadcrumbs = [
        ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Document Type"]
      ];
      return view('dashboard.document_type.index', [
        'breadcrumbs' => $breadcrumbs
      ]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('dashboard.document_type.create', ['documentLimits' => $this->documentLimits]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), ['title' => 'required']);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      $request->flash();
      return view('dashboard.document_type.create', ['documentLimits' => $this->documentLimits])->withErrors($validator);
    }

    $documentType = new DocumentType;
    $documentType->title = $request->title;
    // $documentType->is_required = $request->document_required;
    // $documentType->document_limit = $request->document_limit;
    if ($documentType->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Document Type  added successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.document_type.create', ['documentLimits' => $this->documentLimits]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\DocumentType  $documentType
   * @return \Illuminate\Http\Response
   */
  public function show(DocumentType $documentType)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\DocumentType  $documentType
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $documentType = DocumentType::find($id);
    return view('dashboard.document_type.edit', ['documentType' => $documentType, 'documentLimits' => $this->documentLimits]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\DocumentType  $documentType
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {

    $documentType = DocumentType::find($id);
    $validator = Validator::make($request->all(), ['title' => 'required']);

    if ($validator->fails()) {
      $validator->errors()->add('form_error', 'Error! Please check below');
      $request->flash();
      return view('dashboard.document_type.edit', ['documentType' => $documentType, 'documentLimits' => $this->documentLimits])->withErrors($validator);
    }


    $documentType->title = $request->title;
    // $documentType->is_required = $request->document_required[0];
    // $documentType->document_limit = $request->document_limit;
    if ($documentType->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Congratulations',
        'message' => 'Document Type  updated successfully',
        'success' => true
      ]);
    } else {
      return view('dashboard.document_type.edit', ['documentType' => $documentType, 'documentLimits' => $this->documentLimits]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\DocumentType  $documentType
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $documentType = DocumentType::find($id);
    $documentType->delete();
    if ($documentType->save()) {
      return response()->json([
        'code' => 'success',
        'title' => 'Deleted',
        'message' => 'Document Type deleted successfully',
        'success' => true
      ]);
    }

  }
}
