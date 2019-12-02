<?php

namespace App\Http\Controllers\Api;

use App\Mail\ForgotPass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            // return $this->respondWithToken($token);
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        // return $this->respondWithToken(auth()->refresh());
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            "success"      => true,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Forgot password by user email.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function forgot(Request $request)
    {
        $user = new User;

        $user = $user->where('email', $request->email)->first();
        if ( ! empty($user)) {
            $data    = $user;
            $id      = $data->id;
            $expired = time() + (60 * 60); // one hour expiry
            $link    = url('/')."/auth/reset?token=".urlencode(encrypt($expired.','.$id));

            Mail::to($request->email)->send(new ForgotPass($link, $data));

            return response()->json([
                "success" => true,
                "message" => 'Check email for reset password',
            ], 201);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Email not found in database',
            ], 201);
        }

    }

    /**
     * Reset password of user.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {

        $data            = [];
        $data["success"] = false;
        $data["reset"]   = false;

        if ($request->token) {
            try {
                $decrypted = decrypt($request->token);
            } catch (DecryptException $e) {
                return view('auth/reset', $data);
            }
            $key = explode(",", $decrypted);

            if ($id = $key[1] && time() <= $key[0]) {

                if ($user = User::find($key[1])) {
                    if ($request->password) {

                        $user->password = Hash::make($request->password);
                        if ($user->save()) {
                            $data["success"] = true;
                        }
                        $data["reset"] = true;
                    } else {
                        $data["success"] = true;
                        $new_reset_token = encrypt($key[0].','.$key[1]);
                        $decrypted       = decrypt($new_reset_token);
                        $keyz            = explode(",", $decrypted);

                        $data["token"] = $new_reset_token;
                        $data["user"]  = $user;
                    }

                    return view('auth/reset', $data);
                }
            } else {
                $data["expired"] = false;
            }
        }

        return view('auth/reset', $data);
    }
}
