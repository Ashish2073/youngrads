<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\PageMeta;

class PageController extends Controller
{
    public $metaInsertion;
    public function __construct()
    {
        $this->middleware('auth:admin')->except([
            'show'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::get();

        if (request()->ajax()) {
            return DataTables::of($pages)
                ->addColumn('action', function ($row) {
                    return "<a  href=" . route('admin.page.edit', $row->id) . " class='btn btn-primary'>Update</a>";
                })
                ->editColumn('url', function ($row) {
                    return "<a  href=" . url($row->url) . " target='_blank'>$row->url</a>";
                })
                ->rawColumns(['action', 'url'])
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link'=>"admin.home",'name'=>"Dashboard"], ['name'=>"Pages"]
            ];
            return view('dashboard.page.index', [
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
        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['link' => 'admin.pages', 'name'=>"Pages"], ['name' => 'Create Page']
        ];
        return view('dashboard.page.create', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required|unique:pages',
            'content' => 'required',

        ], [
            'url.unique' => "This Url is already used by someone",
            //'meta_key.numeric'=> "Please Enter Numeric Value For Meta Key"
        ]);



        $page = new Page;
        $page->title = $request->title;
        $page->url =  $request->url;
        $page->content = $request->content;
        $page->save();

        if (!empty(array_filter($request->meta_key)) && !empty(array_filter($request->meta_value))) {

            session(['id' => $page->id]);

            array_map(function ($metaKey, $metaValue) {
                $this->metaInsertion[] = ['page_id' => session()->get('id'), 'meta_key' => $metaKey, 'meta_value' => $metaValue];
            }, $request->meta_key, $request->meta_value);

            $pageMeta = PageMeta::insert($this->metaInsertion);
        }

        if ($page->save()) return redirect()->route('admin.pages')->with('success', 'Page Added Successfully');
        else return redirect()->route('admin.pages')->with('error', 'Something Went Wrong');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($url)
    {
        $page = Page::where('url', $url)->first();
        if ($page) {
            return view('page', compact('page'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $pageMetas = PageMeta::where('page_id', "=", $page->id)->get();

        $breadcrumbs = [
            ['link'=>"admin.home",'name'=>"Dashboard"], ['link' => 'admin.pages', 'name'=>"Pages"], ['name' => 'Update Page']
        ];
        return view('dashboard.page.edit', compact('breadcrumbs', 'page', 'pageMetas'));
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
        $request->validate([
            'title' => 'required',
            'url' => 'required|unique:pages,url,' . $id,
            'content' => 'required'
        ]);


        $page = Page::findOrFail($id);
        $page->title = $request->title;
        $page->url =  $request->url;
        $page->content = $request->content;
        $pageMeta = true;
        // delete previous metadata
        // $pageMetas = PageMeta::where('page_id', "=", $id)->delete();

        // if (!empty(array_filter($request->meta_key)) && !empty(array_filter($request->meta_value))) {
        //     //insert new metadata
        //     session(['id' => $id]);

        //     array_map(function ($metaKey, $metaValue) {
        //         $this->metaInsertion[] = ['page_id' => session()->get('id'), 'meta_key' => $metaKey, 'meta_value' => $metaValue];
        //     }, $request->meta_key, $request->meta_value);


        //     $pageMeta = PageMeta::insert($this->metaInsertion);
        // }

        if ($page->save() && $pageMeta) return redirect()->route('admin.pages')->with('success', 'Page Edit Successfully');
        else return redirect()->route('admin.pages')->with('error', 'Something Went Wrong');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();
        if($page->save()){
          return redirect()->route('admin.pages')->with('success', 'Page Delete Successfully');
        }else{
          return redirect()->route('admin.pages')->with('error', 'Something Went Wrong');
        }
    }

    public function page($url)
    {
        $page = Page::where('url', $url)->get();
        return $page;
    }
}
