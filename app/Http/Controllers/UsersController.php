<?php namespace App\Http\Controllers;

	use DB;
	use App\User;
	use Validator;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Hashing\BcryptHasher;

	class UsersController extends Controller
		{

			// Limiting the Methods that could be
			// used without an 'api_token'
			function __construct()
				{
					$this->middleware('auth', ['only' => ['edit', 'delete', 'groups']]);
				}

			//method to create a new account
			public function add(Request $request)
				{
			    $data = [
			    	'email' => $request['email'],
			    	'password' => $request['password'],
			    	'name' => $request['name'],
			    	'gender' => $request['gender']
			    ];

			    $validator = Validator::make($data, [
			        'email' => 'required',
			        'password' => 'required',
			        'name' => 'required',
			        'gender' => 'required'
			    ]);

			    if ($validator->fails()) 
				    {
							return response()->json('You have to fill the Email, Password, Name, Gender Form', 422);
				    }

					$request['api_token'] = str_random(60);
					$request['password'] = app('hash')->make($request['password']);
					$user = User::create($request->all());
					// dd($request->all());

					return response()->json($user, 200);
				}

			//method to view an account based on the given 'id'
			public function view($id)
				{
					$user = User::find($id);

					return response()->json($user, 200);
				}

			//method to edit an account based on the given 'id'
			public function edit(Request $request, $id)
				{
			    $data = [
			    	'email' => $request['email'],
			    	'password' => $request['password'],
			    	'name' => $request['name'],
			    	'gender' => $request['gender']
			    ];

			    $validator = Validator::make($data, [
			        'email' => 'required',
			        'password' => 'required',
			        'name' => 'required',
			        'gender' => 'required'
			    ]);

			    if ($validator->fails()) 
				    {
							return response()->json('You have to fill the Email, Password, Name, Gender Form', 422);
				    }

					$user = User::find($id);
					$request['password'] = app('hash')->make($request['password']);
					$user->update($request->all());

					return response()->json($user, 200);
				}

			//method to delete an account based on the given 'id'
			public function delete($id)
				{
					$user = User::find($id);
					$user->delete();

					return response()->json('Removed Successfully', 200);
				}

			//method to display all accounts in the database
			public function allUsers()
				{
					// $users = User::all();
					$users = DB::table('users')->get();

					return response()->json($users, 200);
				}

			// Get the Groups that the User with the
			// given $id is enrolled in
			public function groups($id)
				{
					$groups = User::find($id)->groups;

					return response()->json($groups, 200);
				}
		}
 ?>