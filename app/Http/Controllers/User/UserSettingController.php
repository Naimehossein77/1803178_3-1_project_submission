<?php

namespace App\Http\Controllers\user;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AppAudit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserSettingController extends Controller
{
    public function index(){
        try {
            $users = User::all();
            return view('backend.content.user.index', compact('users'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    
    public function store(Request $request){
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);
            toast('User added successfully','success');
            return redirect()->back();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function edit($id){
        try {
            $user = User::find($id);
            return view('backend.content.user.profile', compact('user'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function update(Request $request,$id){
        try {
            // return $request;
            $user = User::find($id);
            if($request->hasFile('image')){
                // return "image received";
                Storage::disk('public')->delete('user_images/'.$user->image);
                $img = Image::make($request->image);
                $img->resize(150,150)->encode('png');
                $image_name = time().'_'.$id.'.png';
                Storage::disk('public')->put('user_images/'.$image_name,$img);
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'contact' => $request->contact,
                    'password' => Hash::make($request->password),
                    'image' => $image_name
                ]);
            }else{
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'contact' => $request->contact,
                    'password' => Hash::make($request->password),
                ]);
            }
            toast('Update Successful','success');
            return redirect()->route('home');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function destroy($id){
        try {
            if(auth()->user()->id == $id){
                toast('You cannot delete yourself!','error');
                return redirect()->back();
            }
            $user = User::find($id);
            Storage::disk('public')->delete('user_images/'.$user->image);
            $user->delete();
            toast('User deleted successfully','success');
            return redirect()->back();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function auditSettings(){
        try {
            $models = [];
            $modelsPath = app_path('Models');
            $modelFiles = File::allFiles($modelsPath);
            foreach ($modelFiles as $modelFile) {
                $models[] = $modelFile->getFilenameWithoutExtension();
            }
        
            return view('backend.content.audit.index', compact('models'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getAudits(Request $request){
        try {
            // return $request;
            $startDate = Carbon::parse($request->from)->format('Y-m-d');
            $endDate = Carbon::parse($request->to)->format('Y-m-d');
            $model = "App\\Models\\".$request->model;
            $audits = AppAudit::with('user:id,name')
                    ->where('auditable_type',$model)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            $model = $request->model;
            // return $audits;
            return view('backend.content.audit.index', compact('audits','model'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

}
