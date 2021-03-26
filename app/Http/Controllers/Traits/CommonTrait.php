<?php
namespace App\Http\Controllers\Traits;
use Session;
use Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait CommonTrait
{
    /**
     * Returns message from via via session class
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function message($type, $msg)
    {
        if ($type == 1) {
            Session::flash('success', $type);
            Session::flash('message',  $msg);

        } else {
            Session::flash('success', $type);
            Session::flash('message',  $msg);

        }
    }

    /***
        @param1 Eloquent or QueryBuilder QUERY result
        @param2 cache key name
     ***/
    public function saveCache($query, $name)
    {
        //retrieving cache  Cache::get($name);
        if (cache()->has($name)) {
            Cache::forget($name);
            Cache::add($name, $query);

        } else {
            Cache::add($name, $query); // Cached it forever unless change happens
        }

    }

    /**
     * @param1 cache key or name
     */
    public function removeCache($name)
    {
        if (cache()->has($name)) {
            Cache::forget($name);
        }
    }

    public function sendEmail($data,$sourceEmail,$header,$cc=array())
    {
        $file = null;
        $name = null;
        $receiver = $data['receiver'];
        $subject = $data['subject'];

        if (isset($data['file']))
            $file = $data['file'];

        if (isset($data['name']))
            $name = $data['name'];

        Mail::send($data['template'],$data, function ($message) use ($receiver,$name,$subject,$sourceEmail,$header,$cc,$file) {
            $message->from($sourceEmail);

            if ($file != null)
                $message->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                ]);

            if (count($cc) > 0)
                $message->cc($cc);

            ($name != null) ?  $message->to($receiver, $name)->subject($subject)->from($sourceEmail) :
                $message->to($receiver)->subject($subject)->from($sourceEmail);

        });


        if (count(Mail::failures()) > 0) {
            return [
                'success' =>false,
                'data' => Mail::failures()
            ];
        }

        return [ 'success' =>true ];
    }


    public function toTxtFile($destination,$filename,$mode,$content=null)
    {
        $response = ['success'=>false,'data'=>null];
        switch($mode) {
            case "put":
                createDir($destination);
                $path = $destination . $filename . '.txt';
                File::put($path, $content);

                $response = [
                    'success' =>true,
                    'data' => $content
                ];
                break;

            case "get":
                $filename = $filename.'.txt';
                $path = $destination.''.$filename;
                $content = '';

                if (file_exists($path)) {
                    $content = File::get($path);
                    $response = [
                        'success' =>true,
                        'data' => $content
                    ];
                } else{

                    $response = [
                        'success' =>false,
                        'data' => $content
                    ];
                }
                break;

            case "local-get":
                $exists = Storage::disk('public')->exists($destination.'/'.$filename);

                if ($exists === true) {
                    $content = Storage::disk('public')->get($destination . '/' . $filename);
                    $response = [
                        'success' => true,
                        'data' => $content
                    ];
                }
                break;

            case "local-put":
                $resultCreateFile = Storage::disk('public')->put($destination.'/'.$filename,$content);
                $response = [
                    'success' =>$resultCreateFile,
                    'data' => $content
                ];

            default:
                $response = [
                    'success' =>false,
                    'data' => 'invalid mode'
                ];
        }

        return $response;
    }


    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

}
