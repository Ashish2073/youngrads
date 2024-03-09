<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

use App\Models\Files;
use App\Models\ApplicationMessage;
use App\Models\MessageAttachment;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Admin;

class ApplicationMessageController extends Controller
{
	public function __construct()
	{
		//$this->middleware('auth');

	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{

		



 
		if (auth('admin')->check()) {
			if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
               $userid=auth('admin')->user()->username;
			   $message_status_type="admin_message_status";
			   $role="admin" ;
			 
			 
			   
			$messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $request->id)->update(['admin_message_status' => 'read']);
			   
		

		

			activity('Read ApplicationMessage')  
			->causedBy(Auth::guard('admin')->user())
			->withProperties(['ip' => $request->ip()])
			->log('Read ApplicationMessage By Admin ');
			
			 
			
			
			 
			
			}elseif(in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))){
				$userid=auth('admin')->user()->username;
				$message_status_type="moderator_message_status";
				$messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $request->id)->update(['moderator_message_status' => 'read']);
				$role="moderator";

				activity('Read ApplicationMessage')  
				->causedBy(Auth::guard('admin')->user())
				->withProperties(['ip' => $request->ip()])
				->log('Read ApplicationMessage By moderator');




			}elseif(in_array('supermoderator',json_decode(auth('admin')->user()->getRoleNames()))){
				$userid=auth('admin')->user()->username;
				$message_status_type="supermoderator_message_status";
				$messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $request->id)->update(['supermoderator_message_status' => 'read']);
				$role="supermoderator";

				activity('Read ApplicationMessage')  
				->causedBy(Auth::guard('admin')->user())
				->withProperties(['ip' => $request->ip()])
				->log('Read ApplicationMessage BY SuperModerator');




			}
			
		} elseif(auth('web')->check()) {
			$userid=Auth::id();
			$message_status_type="user_message_status";
			$messageStatis = ApplicationMessage::where('user_id', '!=', $userid)->where('application_id', '=', $request->id)->update(['user_message_status' => 'read']);
			
			$role="user";

			activity('Read ApplicationMessage')  
			->causedBy(Auth::guard('web')->user())
			->withProperties(['ip' => $request->ip()])
			->log('Read ApplicationMessage By Student');






		}

		
 



		
		return view('application_message.index', ['id' => $request->id, 'gaurd' => 'user', 'auth' =>$userid]);
	}

	public function store(Request $request)
	{

		if (isset($request->document)) {

			$arr = ['message' => 'required', 'document' => 'mimes:jpeg,bmp,png,doc,pdf'];
		} else {
			$arr = ['message' => 'required'];
		}

		$validator = Validator::make($request->all(), $arr);
		if ($validator->fails()) {
			return back()->withErrors($validator);
		}

		if (auth('admin')->check()) {
			if(auth('admin')->user()->getRoleNames()[0]=="Admin"){
               $role="admin"; 
			}elseif(in_array('moderator',json_decode(auth('admin')->user()->getRoleNames()))){
				$role="moderator";
			}elseif(in_array('supermoderator',json_decode(auth('admin')->user()->getRoleNames()))){
				$role="supermoderator";
			}
			
		} elseif(auth('web')->check()) {
			
			$role="user";
		}

         


		$applicationMessage = new ApplicationMessage;
		$applicationMessage->application_id = $request->id;
		$applicationMessage->user_id = $request->auth_id;
		$applicationMessage->message = $request->message;
		$applicationMessage->guard = $request->gaurd;
		$applicationMessage->role_name = $role;
		$applicationMessage->message_scenario = $request->message_scenario;
		
		$applicationMessage->save();

		activity('Created ApplicationMessages')
		->causedBy(Auth::guard('admin')->user())
		->withProperties(['ip' => $request->ip()])
		->log('Created ApplicationMessages');
		


	

		if (isset($request->document)) {

			$fileName = "message_document." . time() . "." . Auth::id() . "." . $request->document->getClientOriginalExtension();
			$request->document->move(public_path('user_documents/'), $fileName);

			$files = new Files;
			$files->title = $fileName;
			$files->location = 'user_documents';
			$files->extension = $request->document->getClientOriginalExtension();
			$files->save();

			$attachment = new MessageAttachment;
			$attachment->message_id = $applicationMessage->id;
			$attachment->file_id = $files->id;
			$attachment->save();
		}

		return back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function showMessages(Request $request,$id)
	{
     


		$Messages = ApplicationMessage::where('application_id', '=', $id)
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'application_message.user_id')
					->where('application_message.guard', '=', 'user');
			})
			->leftJoin('admins', 'application_message.user_id', '=', 'admins.username', function ($join) {
				$join->on('admins.username', '=', 'application_message.user_id')
					->where('application_message.guard', '=', 'admin');
			})

			->leftJoin('message_attachments', 'application_message.id', '=', 'message_attachments.message_id')
			->leftJoin('files', 'message_attachments.file_id', '=', 'files.id')
			->select('users.id as user_id','application_message.role_name','admins.username' ,'users.name as user_name', 'admins.first_name as admin_name', 'users.profile_img as user_img', 'admins.id as admin_id', 'admins.profile_image as admin_img', 'application_message.message', 'application_message.created_at as time', 'application_message.id as id', 'message_attachments.file_id as file_id', 'files.title as file', 'application_message.is_important as is_important', 'application_message.user_id as sent_user_id', 'application_message.guard as guard')
			->where('application_message.message_scenario',$request->get('message_scenario'))
			->get();


			

		if (auth('admin')->check()) {
			$id = auth('admin')->user()->username; 
			$logged_guard = 'admin';
		} elseif (auth('web')->check()) {
			$id = auth('web')->user()->id;
			$logged_guard = 'user';
		}

		return Datatables::of($Messages)
			// ->orderColumn('time', 'time')
			->addColumn('html', function ($row) use ($id, $logged_guard) {


				$html = "";
				
                $overallrole=($row->user_name) ? 'systeam' : 'youngrads_consultant';
				$name = ($row->user_name) ? $row->user_name : $row->admin_name;
				$avtar = $name[0];
				$userId = ($row->user_id) ? $row->user_id : $row->admin_id;
				if ($row->user_img) {
					$profile = "uploads/profile_pic/student/ " . $row->user_img;
				} elseif ($row->admin_img) {
					$profile = 'uploads/profile_pic/' . $row->admin_img;
				} else {
					$profile = 'images/portrait/small/avatar-s-11.jpg';
				}




				// $class =  ($id == $userId) ? '' : 'chat-left';
				$class = ($id == $row->sent_user_id && $logged_guard == $row->guard) ? '' : 'chat-left';
				$attchmentClass = ($id == $userId) ? 'badge text-light bg-white' : 'badge text-white bg-primary';
				return view('application_message.message', compact('overallrole','row', 'name', 'avtar', 'userId', 'id', 'attchmentClass', 'class'))->render();
			})

			->addColumn('time', function ($row) {
				return $row->time;
			})
			->rawColumns(['html', 'time'])
			->make(true);
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

	public function setImportant(Request $request)
	{
		$message = ApplicationMessage::find($request->id);
		$message->is_important = $request->important;
		$message->save();
	}



	public static function sharedAttachment($message_scenario,$id)
	{
		$attachments = ApplicationMessage::where('application_id', '=', $id)
			->join('message_attachments', 'application_message.id', '=', 'message_attachments.message_id')
			->join('files', 'message_attachments.file_id', '=', 'files.id')
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'application_message.user_id')
					->where('application_message.guard', '=', 'user');
			})
			->leftJoin('admins', 'application_message.user_id', '=', 'admins.id', function ($join) {
				$join->on('admins.id', '=', 'application_message.user_id')
					->where('application_message.guard', '=', 'admin');
			})
			->select('files.title as file','application_message.role_name as rolename', 'users.name as user_name', 'admins.first_name as admin_name', 'application_message.created_at as time')
			->where('application_message.message_scenario',$message_scenario)
			->get();
 
		return $attachments;
	} 
}
