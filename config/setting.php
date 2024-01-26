<?php
return [
	'univ_type' => [
		1 => "University",
		2 => "College"
	],
	'intake_names' => [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
		'Spring', 'Winter', 'Summer', 'Autumn'

	],
	'delete_notice' => 'Cannot delete this record as it is being used.',
	'intake_type' => [
		'Month',
		'Season'
	],
	'units' => [
		'percentage' => 'Percentage',
		'GPA' => 'GPA'
	],
	'gender' => [
		'male' => 'Male',
		'female' => 'Female',
		'other' => 'Other'
	],
	"sallaryMode" => [
		'Cash', 'Account',
	],
	'status' => [
		'single' => 'Single',
		'marrried' => 'Marrried'
	],
	'AdminEmail' => 'testnetz321@gmail.com',
	'application' => [
		'status' => [
			'pending' => "Pending",
			// 'open' => "Open",
			// 'close' => "Close",
			'submit_to_ygrad' => 'Submitted to YGrad',
			'ygrad_review' => 'Y-Grad Review',
			'submitted_to_university' => 'Submitted to University',
			'applicant_action_required' => 'Applicant Action Required',
			'decision_taken' => 'Decision Taken',
			'offer_received' => 'Offer Received',
			'archive' => 'Archived'
		],
		'status_meta' => [
			'pending' => ['icon_class' => 'feather icon-plus', 'color' => 'warning', 'description' => 'Application Created'],
			'submit_to_ygrad' => ['icon_class' => 'feather icon-log-in', 'color' => 'success'],
			'ygrad_review' => ['icon_class' => 'feather icon-eye', 'color' => 'info'],
			'submitted_to_university' => ['icon_class' => 'fa fa-building', 'color' => 'success'],
			'decision_taken' => ['icon_class' => 'feather icon-git-branch', 'color' => 'info'],
			'offer_received' => ['icon_class' => 'fa fa-handshake-o', 'color' => 'success'],
			'archive' => ['icon_class' => 'feather icon-archive', 'color' => 'danger'],
			'applicant_action_required' => ['icon_class' => 'feather icon-user', 'color' => 'danger'],
		]
	],
	'email' => [
		'application' => [
			'contact' => [
				'name' => 'Catherine',
				'email' => 'catherine@youngrads.com'
			],
			'footer' => 'If you need any support at this stage you can send me an email at: <a target="_blank" href="mailto:catherine@youngrads.com">catherine@youngrads.com</a>',
			'footer_2' => 'Wishing you all the best. Youngrads is committed to make your dream a reality!',
			'status' => [
				'submit_to_ygrad' => [
					'content' => 'Thanks for submitting your application. The Youngrads application officer will be allocated to review the application in next 2 working days. It is important that all required documents as requested in the program are uploaded with this submission.'
				],
				'ygrad_review' => [
					'content' => 'Thanks for submitting your application. The Youngrads application officer has been allocated and application is under review. Please expect an update on this application within next 3 working days. It is important that all required documents as requested in the program are uploaded with this submission.'
				],
				'applicant_action_required' => [
					'content' => 'Further inputs are needed from you to complete the application. Please check the application in <a href="'. config('app.url') .'">' . config('app.url') . '</a> for actions.'
				],
				'submitted_to_university' => [
					'content' => 'The Youngrads team has reviewed the application and submitted to university. Please expect an update on this application from university soon. Wishing you all the best.'
				],
				'offer_received' => [
					'content' => 'Congratulations on receiving an offer from the university. The Youngrads team will be in touch with you shortly to discuss the next steps.'
				],
				'decision_taken' => [
					'content' => 'The screening of your application for the program is done. Your application was considered but you were not selected for the program. We encourage you to continue visiting the Youngrads website for alternative programs.'
				],
			]
		]
	]
];
