<?php
// use App\Models\UserAccess;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

function send_error($message, $errors = null, $code = 404)
{
    $response = [
        'status' => false,
        'message' => $message,
    ];
    !empty($errors) ? $response['errors'] = $errors : null;

    return response()->json($response, $code);
}

// upload image
function imageUpload($request, $image, $directory, $code)
{
    $doUpload = function ($image) use ($directory, $code) {
        $extention = $image->getClientOriginalExtension();
        $imageName = $code . '_' . uniqId() . '.' . $extention;
        $image->move($directory, $imageName);
        return $directory . '/' . $imageName;
    };
    if (!empty($image) && $request->hasFile($image)) {
        $file = $request->file($image);
        if (is_array($file) && count($file)) {
            $imagesPath = [];
            foreach ($file as $key => $image) {
                $imagesPath[] = $doUpload($image);
            }
            return $imagesPath;
        } else {
            return $doUpload($file);
        }
    }

    return false;
}

// code generate
function generateCode($model, $prefix = '', $organization_id = null)
{
    $code = "00001";
    $modelName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $model)) . 's';
    $clause = "";
    if ($organization_id != null) {
        $clause .= "and organization_id = '$organization_id'";
    }
    $model = DB::select("select * from `$modelName` where 1 = 1 $clause");

    $num_rows = count($model);
    if ($num_rows != 0) {
        $newCode = $num_rows + 1;
        $zeros = ['0', '00', '000', '0000'];
        $code = strlen($newCode) > count($zeros) ? $newCode : $zeros[count($zeros) - strlen($newCode)] . $newCode;
    }
    return $prefix . $code;
}

// make slug
function make_slug($string)
{
    return strtolower(preg_replace('/\s+/u', '-', trim($string)));
}

//credentials check
function credentials($username, $password)
{
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        return ['email' => $username, 'password' => $password];
    } else {
        return ['username' => $username, 'password' => $password];
    }
}

// user access
function checkAccess($accessName)
{
    $status = false;
    // $userAccess = UserAccess::where('user_id', Auth::user()->id)->first();
    if (Auth::user()->role == 'Superadmin' || Auth::user()->role == 'admin') {
        $status = true;
    } else {
        if (!empty($userAccess) && $userAccess->access != NULL) {
            $access = json_decode($userAccess->access, true);
            if (in_array($accessName, $access)) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }
    }

    return $status;
}

// user action
function userAction($action)
{
    $status = false;
    if (Auth::user()->role == 'Superadmin' || Auth::user()->role == 'admin') {
        $status = true;
    } else {
        $actionbtn = explode(",", Auth::user()->action);
        if (in_array($action, $actionbtn)) {
            $status = true;
        } else {
            $status = false;
        }
    }

    return $status;
}
