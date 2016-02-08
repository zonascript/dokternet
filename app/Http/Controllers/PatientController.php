<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientRegRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Http\Controllers\Controller;
use App\ArticleCategory;

use Auth;
use Hash;
use Mail;
use Session;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    public function login()
    {
        $data = [];
        $data['article'] = ArticleCategory::with( 'articles')->get();

        return view('frontend.pages.patient.login', compact('data'));
    }

    public function postLogin(Request $request){

        $email = $request->get('email');
        $password = $request->get('password');
        $remember_me = true;
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember_me)){
            
            if(Auth::user()->verified == '1')
                return redirect()->route('patient.dashboard');
            else{
                Session::flash('failed', "Akun anda belum terverifikasi silakan cek email anda.");
                Auth::logout();
            }
        }else{
            Session::flash('failed', "Kombinasi email dan password tidak cocok.");
        }
        
        return redirect()->back();
    }

    public function register()
    {
        //
        $data = array();
        $data['content'] = null;

        $data['list_gender'][0] = 'L';
        $data['list_gender'][1] = 'P';

        $data['article'] = \App\ArticleCategory::with( 'articles')->get();

        return view('frontend.pages.patient.register')->with('data', $data);
    }
    public function post_register(PatientRegRequest $request)
    {
        $role = \App\Role::where('default','1')->first();
        $input = $request->all();
        $password = bcrypt($request->input('password'));
        $input['password'] = $password;
        $input['activation_code'] = str_random(10) . $request->input('email');
        $register = \App\User::create($input);
        $register->password = $password;
        $register->save();
        $register->roles()->attach($role->id);
        $data = [
           'first_name' => $input['first_name'],
           'last_name' => $input['last_name'],
           'code' => $input['activation_code']
        ];
        // $this->sendEmail($data, $input);
        Session::flash('success', "Silakan periksa email anda untuk mengaktifkan akun Anda. Jika dalam kurun waktu 24 jam Anda tidak menerima email dari kami, Anda dapat menghubungi kami melalui email di <a href='mailto:support@dokternet.com'>support@dokternet.com</a>");
        
        return redirect()->route('patient.register');
    }

    public function sendEmail($data, $input)
    {

        Mail::send('pages.patient.register-email', $data, function($message) use ($input) {

            $message->from('team@dokternet.com', 'DokterNet-Indonesia');
            $message->to($input['email'], $input['first_name'])->subject('Please verify your account registration!');

        });
    }

    public function activate($code, \App\User $patient)
    {
        if ($patient->activateAccount($code)) {
            Session::flash('success', "Akun anda berhasil diaktifkan, silakan masuk ke sistem.");
            return redirect()->route('patient.login');
        }
        Session::flash('danger', "Akun anda gagal diaktifkan, jika mengalami kesulitan dalam mengaktifkan akun Anda, Anda dapat menghubungi kami melalui email di <a href='mailto:support@dokternet.com'>support@dokternet.com</a>.");
        return redirect()->route('patient.login');
    }
    public function dashboard()
    {
        $data = [];
        $data['article'] = ArticleCategory::with( 'articles')->get();
        $data['content'] = Auth::user();
        return view('frontend.pages.patient.dashboard', compact('data'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('patient.login');
    }

    public function update(PatientUpdateRequest $request)
    {
        $data = \App\User::find(Auth::user()->id);
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->birth_date = $request->birth_date;
        $data->gender = $request->gender;
        $data->mobile = $request->mobile;
        $data->telephone = $request->telephone;
        $data->address = $request->address;
        $data->save();
        Session::flash('success', 'Data anda berhasil diperbarui');
        return redirect()->route('patient.dashboard');
    }
}
