<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;


use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // $this->middleware('guest');
    }


    public function index()
    {
        $pageTitle = 'List of Users';

        $request=$request = request()->all();
        $users = $this->userService->getUsersList($request);
        return view('auth.index', compact('users', 'pageTitle'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $pageTitle = 'Register User';

        // dd($data);
        $fileName = null;
        if(isset($data['avatar']) && !empty($data['avatar'])){
            $file = $data['avatar'];
            $fileName = time().'.'.$file->getClientOriginalExtension();
            // $file->move(User::UPLOAD_PATH, $fileName);
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => isset($data['is_admin']) ? $data['is_admin'] : 0,

            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ];
        if($data['id']){
            User::where('id', $data['id'])->update(array(
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_admin' => isset($data['is_admin']) ? $data['is_admin'] : 0,

                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ));

        }
        User::create($userData);

        Session::flash('message', 'User created successfully');
        return redirect('users/list', compact('pageTitle'));
    }
    public function update(){
        $pageTitle = 'Register User';
        $data = request()->all();
        $fileName = null;
        if(isset($data['avatar']) && !empty($data['avatar'])){
            $file = $data['avatar'];
            $fileName = time().'.'.$file->getClientOriginalExtension();
            // $file->move(User::UPLOAD_PATH, $fileName);
        }
        User::where('id', $data['id'])->update(array(
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => isset($data['is_admin']) ? $data['is_admin'] : 0,
            ));

        Session::flash('message', config('constants.update'));
        return redirect('users/list', compact('pageTitle'));
    }

    public function showRegistrationForm()
    {
        $pageTitle = 'Register User';
        return view('auth.register',  compact('pageTitle'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Register User';
        $user = User::find($id);
        // dd($user);
        if(empty($user)){
            return abort(404);
        }

        return view('auth.register', compact('user','pageTitle'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = User::destroy($id);
        if($deleted){
            return response()->json(['success'=>'200', 'message'=>config('constants.delete')]);
        }else{
            return response()->json(['error'=>'', 'message'=>config('constants.wrong')]);
        }
    }

}
