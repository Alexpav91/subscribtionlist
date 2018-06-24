<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Providers\AppServiceProvider;
use App\Providers;
use App\Sublist;
use App\Subscriber;
use App\Allowedsender;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

// Route::get('/', [
    // 'middleware' => 'auth',
    // 'uses' => 'HomeController@index'
// ]);

Route::get('/', function () {

    return redirect('/subscribtion/');
})->middleware('auth');

Route::get('/subscribtion', function () {
	$sublist = DB::table('sublists')->latest()->get();
    return view('subscribtionlist', ['sublist' => $sublist]);
})->middleware('auth');

//Display subscribers
Route::get('/subscriberlist/{id}', function ($id) {
	$subs = DB::table('subscribers')->where('idSubscribtion', '=', $id)->latest()->get();
	$list = DB::table('sublists')->where('id', $id)->get();
	$allowed = DB::table('allowedsenders')->where('idSubscribtion', $id)->latest()->get();
	// dd($list);
	return view('subscriberlist', ['subs' => $subs, 'list' => $list[0], 'allowed' => $allowed]);
})->middleware('auth');

//Display certain subscriber
Route::get('/subscriber/{id}', function ($id) {
	$sub = Subscriber::findOrFail($id);
	// dd($sub);
	return view('sub', ['sub' => $sub]);
})->middleware('auth');

//Display unsubscribe subscriber
Route::get('/unsubscribe/{id}', function () {
	// dd($sub);
	return view('unsubscribe');
});

//Display upload subs
Route::get('/uploadsubs/{id}', function ($id) {
	$list = DB::table('sublists')->where('id', $id)->get();
	return view('uploadsubs', ['list' => $list[0]]);
})->middleware('auth');


//Add subscribtion list
Route::post('/subscribtion', function (Request $request) {
	$validator = Validator::make($request->all(), [
        'name' => 'required|alpha_dash|unique:sublists,name|max:255|min:3',
		'describtion' => 'required',
		'sender' => 'required|email|max:255'
    ]);
	
	$mail = ['mail' => $request->name . '@alerts.prime-intra.net'];
	$valid = Validator::make($mail, [
		'mail' => 'email'
	]);
	
	if ($validator->fails()) {
        return redirect('subscribtion/')
            ->withInput()
            ->withErrors($validator);
    }
	
	if ($valid->fails()) {
        return redirect('subscribtion/')
            ->withInput()
            ->withErrors('"'.$mail['mail'].'"'.' is not a valid email.');
    }	
	
	$sublist = new Sublist;
	$sublist->name = $request->name;
	$sublist->owner = Auth::user()->name;
	$sublist->describtion = $request->describtion;
	$sublist->save();
	
	$id = $sublist->id;
	
	$sender = new Allowedsender;
	$sender->idSubscribtion = $id;
	$sender->sender = $request->sender;
	$sender->save();
	
	return redirect('subscribtion/');
})->middleware('auth');

//Add subscriber
Route::post('/addsub', function (Request $request) {
	$id = $request->idSubscribtion;
	$validator = Validator::make($request->all(), [
        'mail' => 'required|email|max:255|unique:subscribers,mail,null,null,idSubscribtion,'.$request->idSubscribtion,
		'recep' => 'required',
    ]);
	
	if ($validator->fails()) {
        return redirect('./subscriberlist/'.$id)
            ->withInput()
            ->withErrors($validator);
    }
	
	$sub = new Subscriber;
	$sub->idSubscribtion = $id;
	$sub->mail = $request->mail;
	$sub->recepField = $request->recep;
	$sub->save();
	
	return redirect('./subscriberlist/'.$id);
})->middleware('auth');

//Add Sender
Route::post('/addsender', function (Request $request) {
	$id = $request->idSubscribtion;
	$validator = Validator::make($request->all(), [
        'sender' => 'required|email|max:255|unique:allowedsenders,sender,null,null,idSubscribtion,'.$request->idSubscribtion,
    ]);
	
	if ($validator->fails()) {
        return redirect('./subscriberlist/'.$id)
            ->withInput()
            ->withErrors($validator);
    }
	
	$sender = new Allowedsender;
	$sender->idSubscribtion = $id;
	$sender->sender = $request->sender;
	$sender->save();
	
	return redirect('./subscriberlist/'.$id);
})->middleware('auth');

//Upload Excel with subscribers
Route::post('/uploadsubs/{id}', function ($id, Request $request) {
	// dd($request);
	if($request->hasFile('import_file')){
		
		
		$ext = pathinfo($request->file('import_file')->getClientOriginalName(), PATHINFO_EXTENSION);

		if ($ext != 'xlsx') {
			return redirect('./uploadsubs/'.$id)
							->withInput()
							->withErrors($request->file('import_file')->getClientOriginalName() . " is not an Excel document.");
		}
		
		$path = $request->file('import_file')->getRealPath();

		$data = \Excel::load($path)->get();
		if($data->count()){
			
			//Grab all subs from list to check for duplicates
			$subs = DB::table('subscribers')->where('idSubscribtion', $id)->pluck('mail');
			// dd($subs);
			
			foreach ($data as $key => $value) {
				// $arr[] = ['to' => $value->to, 'cc' => $value->cc, 'bcc' => $value->bcc];
				
				$to = $value->to;
				$cc = $value->cc;
				$bcc = $value->bcc;
				
				if ($to) {
					
					if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
						return redirect('./uploadsubs/'.$id)
							->withInput()
							->withErrors($to . " is not a valid email address.");
					}	
					
					$duplicate = false;
					
					foreach ($subs as $key => $sub) {
						if ($sub == $to) {
							$duplicate = true;
							break;
						}
					}
					
					if (!$duplicate) {
						$new = new Subscriber;
						$new->idSubscribtion = $id;
						$new->mail = $to;
						$new->recepField = 1;
						$new->save();
					}

				}				
				if ($cc) {
					
					if (!filter_var($cc, FILTER_VALIDATE_EMAIL)) {
						return redirect('./uploadsubs/'.$id)
							->withInput()
							->withErrors($cc . " is not a valid email address.");
					}	
					
					$duplicate = false;
					
					foreach ($subs as $key => $sub) {
						if ($sub == $cc) {
							$duplicate = true;
							break;
						}
					}
					
					if (!$duplicate) {
						$new = new Subscriber;
						$new->idSubscribtion = $id;
						$new->mail = $cc;
						$new->recepField = 2;
						$new->save();
					}
				}
				if ($bcc) {
					
					if (!filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
						return redirect('./uploadsubs/'.$id)
							->withInput()
							->withErrors($bcc . " is not a valid email address.");
					}	
					
					$duplicate = false;
					
					foreach ($subs as $key => $sub) {
						if ($sub == $bcc) {
							$duplicate = true;
							break;
						}
					}
					
					if (!$duplicate) {
						$new = new Subscriber;
						$new->idSubscribtion = $id;
						$new->mail = $bcc;
						$new->recepField = 3;
						$new->save();
					}
				}
			}
		}
	}
	else {
		return redirect('./uploadsubs/'.$id)
							->withInput()
							->withErrors("No file to upload.");
	}
	
	// $validator = Validator::make($arr, [
        // 'mail' => 'required|email|max:255|unique:subscribers,sender,null,null,id,'.$arr->id,
        // '*.mail' => 'email|max:255|unique:subscribers,mail,null,null,idSubscribtion,'.$id,
    // ]);
	
	// $rules = [];

	// foreach($arr['mail'] as $key => $val) {
		// $rules['mail.'.$key] = 'required|distinct|min:3';
	// }
	
	// $validator = Validator::make($arr, $rules);
	
	// if ($validator->fails()) {
        // return redirect('./uploadsubs/'.$id)
            // ->withInput()
            // ->withErrors($validator);
    // }
	
	return redirect('./subscriberlist/'.$id);
})->middleware('auth');


//Delete subscriber
Route::delete('/deletesub/{id}', function ($id) {
    $sub = Subscriber::findOrFail($id);
	$list = $sub->idSubscribtion;
	$sub->delete();
	
    return redirect('./subscriberlist/'.$list);
})->middleware('auth');

//Delete alloweder Sender
Route::delete('/deletesender/{id}', function ($id) {
    $allow = Allowedsender::findOrFail($id);
	$list = $allow->idSubscribtion;
	$allow->delete();
	
    return redirect('./subscriberlist/'.$list);
})->middleware('auth');
 
 
//Delete subscribtion list
Route::delete('/listdelete/{id}', function ($id) {
    Sublist::findOrFail($id)->delete();
	DB::table('allowedsenders')->where('idSubscribtion', $id)->delete();

    return redirect('subscribtion/');
})->middleware('auth');

//Unsubscribe
Route::delete('/unsubscribe/{id}', function ($id, Request $request) {
    $sub = DB::table('subscribers')->where('mail', '=', $request->mail)->where('idSubscribtion', '=', $id)->get();
	if ($sub->first()) { 
		Subscriber::findOrFail($sub->first()->id)->delete();
		return view('statusunsub', ['status' => true]);
	}
    else {
		return view('statusunsub', ['status' => false]);
	}
});

//Update subscriber
Route::post('/updatesub/{id}', function ($id, Request $request) {
    $sub = Subscriber::findOrFail($id);
	// dd($request->recep);
	$sub->recepField = $request->recep;
	$sub->save();
	
    return redirect('./subscriber/'.$id);
})->middleware('auth');
 
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
