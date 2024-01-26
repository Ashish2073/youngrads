<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Notifications\Admin;
use App\Notifications\ContactMessage;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        // die();
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $details = ['name' => $request->name, 'email' => $request->email, 'message' => $request->message];
        //user Email
        Notification::route('mail', $request->email)->notify(new ContactMessage($request->name));
        //Admin Email
        Notification::route('mail', config('setting.AdminEmail'))->notify(new Admin($details));

        if ($contact->save()) {
            return back()->with([
                'code' => 'success',
                'message' => 'Your message sent successfully!'
            ]);
        } else {
            return back()->with([
                'code' => 'danger',
                'message' => 'Error! Something went wrong.'
            ]);
        }
    }

    public function messageListing()
    {
        $this->middleware('auth:admin');
        if (request()->ajax()) {
            $messages = Contact::all();
            return Datatables::of($messages)
                ->editColumn('email', function ($row) {
                    return "<a  href='mailto:" . $row->email . "' target='_blank'>$row->email</a>";
                })
                ->editColumn('message', function ($row) {
                    return tooltip(Str::limit($row->message, 20, '...'), $row->message);
                    ;
                })
                ->rawColumns(['email', 'message'])
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Contact Entries"]
            ];
            return view('dashboard.contact_entries.index', [
                'breadcrumbs' => $breadcrumbs
            ]);
        }
    }

    public function show($id)
    {
        $this->middleware('auth:admin');
        $contact = Contact::findOrFail($id);
        return view('dashboard.contact_entries.show', compact('contact'));
    }


}
