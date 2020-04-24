<?php

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\QuestionModel;
use App\Models\FeedbacksModel;
use App\Models\TestsModel;
use App\Models\HistoryModel;
use Validator as validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('', function(){
    return response()->json([
        'success'=>true,
        'message'=>'Welcome'
    ]);
});

//Admin api

// getting registered users
Route::get('user/all', function(){
    $count = UserModel::count();
    $users = UserModel::get();
    return response()->json([
        'data'=>$count,
        'result'=>$users
    ]);
});
// get all courses details
Route::get('courses/details', function(){
    $count = CourseModel::count();
    $course = CourseModel::get();
    return response()->json([
        'data'=>$count,
        'courses'=>$course
    ]);
});


//get courses
Route::get('courses/all', function(){
    $course = CourseModel::get();
    return response()->json([
        'courses'=>$course
    ]);
});
//course addition
Route::post('courses/add', function(Request $request){
    $rules = [
        'course_name' => 'required',
    ];
    $validator = validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            $validator->errors(),
            'success'=>'false',
    ], 400);
    }
    $course = CourseModel::create($request->all());
    return response()->json([
        'data'=>$course,
        'success'=>true
    ], 200);
});

//course delete
Route::post('courses/delete', function(Request $request){
    $id = $request->course_id;
    $course = CourseModel::find($id);
    $course->delete();
    return response()->json([
        'success'=>true
    ]);
});
        
// question addition
Route::post('questions/add', function(Request $request){
    $rules = [
        'question' => 'required|unique:test_questions',
    ];
    $validator = validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            $validator->errors(),
            'success'=>'false',
    ], 400);
    }
    $question = QuestionModel::create($request->all());
    return response()->json([
        'data'=>$question,
        'success'=>true
    ], 200);
});


// available questions
Route::get('questions/details', function(){
    $count = QuestionModel::count();
    $question = DB::table('test_questions')->select('course_name','year', 'question' , 'incorrect_answer1', 'incorrect_answer2', 'incorrect_answer3', 'correct_answer')->get();
    return response()->json([
        'data'=>$count,
        'questions'=>$question
    ]);
});
// questions by course
Route::post('questions/courses', function(Request $request){
    $course_name = $request->course_name;
    $count = $request->count;
    $year = $request->year;
    $questions = QuestionModel::orderByRaw('RAND()')->select('question', 'incorrect_answer1', 'incorrect_answer2', 'incorrect_answer3', 'correct_answer', 'explanation')->where('course_name','=', $course_name)->where('year','=', $year)->take($count)->get();
    return response()->json([
        'questions'=>$questions
    ]);
});

// get all feedbacks
Route::get('feedbacks/details', function(){
    $count = FeedbacksModel::count();
    $feedback = FeedbacksModel::get();
    return response()->json([
        'data'=>$count,
        'feedbacks'=>$feedback
    ]);
});

// add to history
Route::post('user/history/add', function(Request $request){
    $rules = [
        'device_id' => 'required'
    ];
    $validator = validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            $validator->errors(),
            'success'=>'false',
    ], 400);
    }
    $user = HistoryModel::create($request->all());
    return response()->json([
        'data'=>$user,
        'success'=>true
    ], 200);
});

// get all history by device_id
Route::post('user/history/get', function(Request $request){
    $device_id = $request->device_id;
    $history = HistoryModel::select('createdAt', 'course_name', 'numberCorrect', 'total')->where('device_id','=', $device_id)->get();
    return response()->json([
      'history'=>$history
    ]);
});


// user api

//add user feedback
Route::post('feedbacks/add', function(Request $request){
    // setting parameters
    $name = $request->name;
    $email = $request->email;

    $rules = [
        'name' => 'required',
        'email' => 'required',
        'feedback' => 'required',
        'date' => 'required',
    ];
    $validator = validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            $validator->errors(),
            'success'=>'false',
    ], 400);
    }
    $question = FeedbacksModel::create($request->all());
    return response()->json([
        'success'=>true,
    ], 200);

});










//general api

// user login
Route::post('user/login', function(Request $request){
    $device_id = $request->device_id;
    $query = UserModel::where('device_id','=',$device_id);

    if($query->count()>0) {
        $user = $query->select('e_date')->first();
        $details = $query->select()->first();
        return response()->json([
            'success'=>true,
            'data'=>$user,
            'details'=>$details
        ]);
    } else {
        return response()->json([
            'success'=>false,
            'error'=>true
        ], 404);
    }
    return response()->json($user, 200);
});
//user registration
Route::post('user/register', function(Request $request){
    $rules = [
        'name' => 'required|unique:users',
        'email' => 'required|unique:users',
        'phone' => 'required|unique:users',
        'device_id' => 'required|unique:users',
    ];
    $validator = validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            $validator->errors(),
            'success'=>'false',
    ], 400);
    }
    $user = UserModel::create($request->all());
    return response()->json([
        'data'=>$user,
        'success'=>true
    ], 200);
});
// get user details
Route::post('user/details', function(Request $request){
    $device_id = $request->device_id;
    $query = UserModel::where('device_id','=',$device_id);

    if($query->count()>0) {
        $name = $query->select('name')->first();
        $email = $query->select('email')->first();
        $phone = $query->select('phone')->first();
        $address = $query->select('address')->first();
        $r_date = $query->select('r_date')->first();
        $e_date = $query->select('e_date')->first();
        $role = $query->select('role')->first();
        return response()->json([
            'success'=>true,
            'data'=>[$name, $email, $phone, $address, $r_date, $e_date, $role]
        ]);
    } else {
        return response()->json([
            'success'=>false,
            'error'=>true
        ], 404);
    }
    return response()->json($user, 200);
});

// get user profile
Route::post('user/profile/details', function(Request $request){
  $device_id = $request->device_id;
  $query = UserModel::where('device_id','=',$device_id);

  if($query->count()>0) {
      $details = $query->select('name', 'email', 'phone', 'address', 'e_date')->first();
      return response()->json([
        'details'=>$details
      ]);
  } else {
      return response()->json([
          'success'=>false,
          'error'=>true
      ], 404);
  }
  return response()->json($user, 200);
});

// get user settings
Route::post('user/settings/details', function(Request $request){
  $device_id = $request->device_id;
  $query = UserModel::where('device_id','=',$device_id);

  if($query->count()>0) {
      $details = $query->select('count', 'duration')->first();
      return response()->json([
          'details'=>$details
      ]);
  } else {
      return response()->json([
          'success'=>false,
          'error'=>true
      ], 404);
  }
  return response()->json($user, 200);
});

//update user settings of question count and duration
Route::post('settings/update', function(Request $request){
    // setting parameters
    $device_id = $request->device_id;
    $count = $request->count;
    $duration = $request->duration;
    // querying and updating the user's settings in the database
    DB::table('users')
            ->where('device_id','=', $device_id)
            ->update(['count' => $count, 'duration' => $duration]);
    return response()->json([
        'success'=>true
    ]);
});

