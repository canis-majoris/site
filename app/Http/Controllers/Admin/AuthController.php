<?php
namespace App\Http\Controllers\Admin; //admin add

use App\Http\Controllers\Admin\AppController;
use Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests\Auth\LoginRequest;
use App\User;
use App\Models\Region;
use App\Role;
use App\Models\Country;
use App\Models\CartModel;
use App\Jobs\SendMail;
use Illuminate\Http\Request;
use Validator;
use Mail;
use Illuminate\Support\Facades\Redis;
class AuthController extends AppController {

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    protected $redirectPath = '/auth/login';
    protected $loginPath = '/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api', ['except' => 'postLogout']);
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
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username'       => $data['firstname'] . ' ' . $data['lastname'],
            'email'          => $data['email'],
            'password'       => bcrypt($data['password']),
            'region'         => (isset($data['region']) ? json_encode($data['region']) : 'all'),
            'city'           => ($data['city'] ? $data['city'] : null),
            'phone'          => ($data['phone'] ? $data['phone'] : null),
            'country'        => ($data['country'] ? $data['country'] : null),
            'salt'           => $data['salt']
        ]);
    }

    protected function checkEmail(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            $users = User::where('email', '=', $data['email'])->select();
            if ($request->has('id')) {
                $users = $users->where('id', '!=', $request->get('id'));
            }
            if($users->count() > 0) {
                return 'false';
            }
            return 'true';
        }
    }

    public function showErrors(){
        return view('errors');
    }

    public function getLogin($message = null, $type = null) {
        //return view('admin.login');
       // var_dump('lololo');
      	$message = (Session::has('message') ? Session::get('message') : $message);
        $mesType = (Session::has('mesType') ? Session::get('mesType') : $type);
            //return \View::make('panelViews::login')->with('message', $message)->with('mesType', $mesType);
        return View::make('auth.sign-in', compact('message', 'mesType'));
    }

    public function postLogin(LoginRequest $request, Guard $auth) {
        //return Redirect::route('admin-dashboard');
        //Config::set('auth.model', 'Serverfireteam\Panel\Admin');
        //
        //var_dump('expression'); die;

        if ($request->ajax()) {

            $throttles = in_array(
                ThrottlesLogins::class, class_uses_recursive(get_class($this))
            );




            if ($throttles && $this->hasTooManyLoginAttempts($request)) {
                return response()->json(['success' => false, 'message_code'=>0, 'message' => trans('login.maxattempt')]);
            }

            $userdata = array(
                    'email' 	=> $request->input('email'),
                    'password' 	=> $request->input('password'),
            );

            // attempt to do the login
            if(!$auth_user = Auth::attempt($userdata,filter_var($request->input('remember'), FILTER_VALIDATE_BOOLEAN))) {
                //dd($userdata);
                if ($throttles) {
                    $this->incrementLoginAttempts($request);
                }

                return response()->json(['success' => false, 'message_code'=>0, 'message' => trans('auth.failed')]);
            }
            
            $user = $auth->getLastAttempted();

            $permission = 1;

            if($user->status && $user->hasRole(['superadmin', 'admin', 'editor', 'manager', 'finances'])) {

                if ($throttles) {
                    $this->clearLoginAttempts($request);
                }

                $auth->login($user, $request->has('memory'));
                $user->last_login = date('Y-m-d H:i:s');
                $user->activity = 1;
                $user->save();

                if($request->session()->has('user_id')) {
                    $request->session()->forget('user_id');
                }
                return response()->json(['success' => true, 'message_code'=>1, 'message' => 'welcome!', 'redirect' => session()->get('intended_page_url')]);
            }
            
            $request->session()->put('user_id', $user->id); 

            return response()->json(['success' => false, 'message_code'=>0, 'message' => 'Authentication Failed']);
        }   
    }

    public function postLogout() {
    	Auth::logout(); 
        return redirect('login');
    }

    public function getRegister() {
        $message = (Session::has('message') ? Session::get('message') : 'Sign in');
        $mesType = (Session::has('mesType') ? Session::get('mesType') : 'message');
        $regions = Region::all();
        $countries = Country::all();
            //return \View::make('panelViews::login')->with('message', $message)->with('mesType', $mesType);
        return View::make('auth.sign-up', compact('message', 'mesType', 'regions', 'countries'));

    }

    /**
     * Register new user
     */
    public function postRegister(){
        $data = Input::all();
        $data['salt'] = str_random(30);
        $validator = Validator::make($data, User::rules());
        if($validator->fails()){
            return response()->json(['success' => false, 'message_code'=>0, 'message' => $validator->errors()->all()]);
        }else{

            if($user = $this->create($data)) {
                /*$mail_data = [
                    'title'  => trans('verify.email-title'),
                    'intro'  => trans('verify.email-intro'),
                    'link'   => trans('verify.email-link'),
                    'confirmation_code' => $user->remember_token
                ];
                
                Mail::send('emails.auth.verify', $mail_data, function($message) use($user){
                    $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                            ->subject(trans('verify.email-title'));
                });*/
                Session::put('ok', trans('verify.message'));
                return response()->json(['success' => true, 'message_code'=>0, 'message' => trans('verify.message')]);
            }
            return response()->json(['success' => false, 'message_code'=>0, 'message' => 'something went wrong..']);
        }
    }

    /**
     * Handle a confirmation request.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  string  $confirmation_code
     * @return Response
     */
    public function confirm_registration($code) {
        $user = User::whereSalt($code)->firstOrFail();

        if ($user->id) {
            $user->confirmed = true;
            $user->salt = null;
            $user->save();

            return redirect('admin/login')->with('ok', trans('verify.success'));
        }

        return view('errors.404');
        
    }


    // // Overriding the authenticated method from Illuminate\Foundation\Auth\AuthenticatesUsers
    // protected function authenticated(Request $request, $user)
    // {
    //     // Building namespace for Redis
    //     $id = $user->id;
    //     $browser = $request->server('HTTP_USER_AGENT');
    //     $namespace = 'users:'.$id.$browser;

    //     // Getting the expiration from the session config file. Converting from minutes to seconds.
    //     $expire = config('session.lifetime') * 60;

    //     // Setting redis using id as value
    //     Redis::SET($namespace,$id);
    //     Redis::EXPIRE($namespace,$expire);
    // }

    // // Overriding the logout method from Illuminate\Foundation\Auth\AuthenticatesUsers
    // public function logout(Request $request)
    // {
    //     // Building namespace for Redis
    //     $id = Auth::user()->id;
    //     $browser = $request->server('HTTP_USER_AGENT');
    //     $namespace = 'users:'.$id.$browser;

    //     // Deleting user from redis database when they log out
    //     Redis::DEL($namespace);

    //     $this->guard()->logout();

    //     $request->session()->flush();

    //     $request->session()->regenerate();

    //     return redirect('/');
    // }
}