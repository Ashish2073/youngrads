<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
     public function __construct() {
         $this->middleware('auth:admin');
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies =  Currency::get();
        if(request()->ajax()){
          return DataTables::of($currencies)
                 ->make(true);
        }else{
            $breadcrumbs = [
                ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Currency"]
            ];
            return view('dashboard.curruncy.index', [
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
        return view('dashboard.curruncy.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'symbol' => 'required',
            'rate' => 'required',
            'code' => 'required'
        ]);

        if($validator->fails()){
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.curruncy.create')->withErrors($validator);
        }

        $feetype = new  Currency;
        $feetype->name = $request->name;
        $feetype->symbol = $request->symbol;
        $feetype->rate = $request->rate;
        $feetype->code = $request->code;

        if($feetype->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Curruncy Added successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.curruncy.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return view('dashboard.curruncy.edit',compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'symbol' => 'required',
            'rate' => 'required',
            'code' => 'required'
        ]);

        if($validator->fails()){
            $validator->errors()->add('form_error', 'Error! Please check below');
            return view('dashboard.curruncy.edit')->withErrors($validator);
        }

        $feetype = Currency::findOrFail($id);
        $feetype->name = $request->name;
        $feetype->symbol = $request->symbol;
        $feetype->rate = $request->rate;
        $feetype->code = $request->code;

        if($feetype->save()){
            return response()->json([
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Curruncy Edit successfully',
                'success' => true
            ]);
        } else {
            return view('dashboard.curruncy.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //
    }
}
