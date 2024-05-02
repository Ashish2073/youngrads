<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intake;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $intakes = Intake::select('name', 'id')->get();
        $programs = Program::select('name', 'id')->get();
        return view('home', compact('intakes', 'programs'));
    }

    public function earlyaccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'personal_number' => 'required|string|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
        ]);

        // If validation fails, return back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->personal_number = $request->input('personal_number');
        $user->save();

        return redirect()->back()->with('success', 'Your access Successfully in Youngrads!');

    }
}
