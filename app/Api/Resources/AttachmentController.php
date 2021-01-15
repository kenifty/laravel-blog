<?php


namespace App\Api\Resources;


use App\Api\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:30,60');
    }

    public function upload(Request $request)
    {
        $isFounder = Auth::check() && Auth::user()->hasTeamRole('','Founder'); //todo change TeamRole

        $rules = [
          'file' => 'required|image|mimes:png,jpg,jpeg,gif|max:'.($isFounder?2048:1024),
        ];

        $this->validate($request,$rules);

        $disk = Storage::disk('public');
        $path = $disk->putFile('tmp',$request->file('file'));

        $data = [
          'url' => $disk->url($path)  // 上传附件成功 返回url
        ];

        return new Resource($data);
    }
}
