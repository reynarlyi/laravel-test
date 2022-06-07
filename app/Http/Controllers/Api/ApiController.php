<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Class ApiController
 * @package App\Http\Controllers\Api
 */
class ApiController extends Controller
{
    /**
     * @param Request $request
     * @throws Exception
     */
    public function auth(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

        $token = Auth::user()->createToken(config('app.name'));
        $tokenObject = $token->accessToken;
        $tokenObject->save();

        return response()->json([
            'token' => $tokenObject->token,
        ], 200);
    }

    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if (!$validator) {
            return response()->json([
                'message' => 'Not valid data'
            ], 200);
        }

        $user = User::create(array_merge(
            $request->only('username', 'fullname'),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'You were successfully registered.'
        ], 200);
    }

    public function profile(Request $request)
    {
        if (! $request->header('token')) {
            return response()->json([
                'message' => 'You miss token'
            ], 401);
        }
        $userId = PersonalAccessToken::where('token' , $request->header('token'))->first()->tokenable_id;

        if (! $userId) {
            return response()->json([
                'message' => 'Your token is bad'
            ], 401);
        }

        $fullname = User::find($userId)->first()->fullname;

        return response()->json([
            'fullname' => $fullname
        ], 200);
    }
}
