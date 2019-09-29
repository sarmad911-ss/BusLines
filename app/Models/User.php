<?php

namespace App\Models;

use App\Models\core\AcsimaModel;
use App\Models\core\File;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use IvanLemeshev\Laravel5CyrillicSlug\Slug;

/**
 * Class User
 * @package App\Models
 * @property string email
 * @property string password
 * @property integer role_id
 * @property string name
 * @property integer photo_id
 * @property Carbon dob
 * @property string misc
 * @property string photo_url
 * @property string phone
 * @property boolean can_delete
 * @property boolean can_edit
 * @property string representative_name
 * @property string representative_last_name
 * @property string code
 * @property string city
 */
class User extends AcsimaModel
{
    protected $fillable = ['email', 'role_id', 'name', 'password','photo_id','dob','misc','photo','phone','representatives','representative_name','address',"representative_last_name","code","city"];
	private $photo_url, $can_delete, $can_edit;

    protected $hidden = ['password'];

    public function setPasswordAttribute($val)
    {
        if (!empty($val)) {
            $this->attributes['password'] = \Hash::make($val);
        }
    }

    function generateRandomPassword() {
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->password = $randomString;
        return $randomString;
    }

    public function getDobAttribute(){
    	if(!empty($this->attributes['dob']))
    		return Carbon::createFromTimestamp($this->attributes['dob']);
    	return null;
    }

    public function setDobAttribute($date){
    	$date = Carbon::createFromFormat("Y-m-d",$date);
    	$this->attributes['dob'] = $date->timestamp;
    }

    public function setPhotoAttribute(UploadedFile $photo){
    	$file = new File();
    	$file->file = $photo;
    	$file->save();
    	$this->attributes['photo_id'] = $file->id;
    }

    public function photo(){
	    return $this->belongsTo(File::class,'photo_id');
    }

	public function makePhoto($filename = null){
		if(!empty($this->photo->id)){
			$this->photo->delete();
		}
		$text = implode("",array_map(function($text){return mb_substr($text ?? "",0,1);},array_slice(explode(" ",$this->name),0,2)));
		$imageX = 400;
		$imageY = 400;
		$textsize = min($imageX,$imageY)*0.4;
		$font = public_path()."/fonts/Inter-Medium.ttf";
		$box = imagettfbbox ($textsize,0,$font,$text);
		$textWidth = $box[2];
		$textHeight = abs($box[7]);
		while ($textWidth >= $imageX*0.5 || $textHeight >= $imageY*0.5){
			$textsize = $textsize-5;
			$box = imagettfbbox ($textsize,0,$font,$text);
			$textWidth = $box[2];
			$textHeight = abs($box[7]);
		}
		$image = imagecreatetruecolor($imageX,$imageY);
		$color = imagecolorallocate($image,rand(0,140),rand(0,140),rand(0,140));
		imagefill($image,0,0,$color);
		$x = floor(($imageX-$textWidth)/2);
		$y = floor(($imageY-$textHeight)/2);
		$y = $imageY-$y;
		$textcolor = imagecolorallocate($image,255,255,255);
		imagettftext($image,$textsize,0,$x,$y,$textcolor,$font,$text);
		if(!file_exists(\Storage::path("public/avatars")))
			\Storage::makeDirectory("public/avatars");
		$nameSlug = (new Slug())->make($this->attributes['name']);
		if(empty($filename))
		    $filename = time()."-".$nameSlug.".png";
		$path = 'avatars/';
		imagepng($image,\Storage::path("public/".$path.$filename));
		$size = \Storage::size("public/".$path.$filename);
		$file =File::where(['path'=>$path,'name'=>$filename])->get();
		if($file->count()==0) {
            $file = new File(['path' => $path, 'name' => $filename, 'size' => $size]);
            $file->save();
            $this->photo_id = $file->id;
            $this->save();
        }
		imagedestroy($image);
	}

	public function getPhotoUrlAttribute(){
		if(empty($this->attributes['id']))
			return "";
		if($this->photo()->get()->count()==0)
			$this->makePhoto();
		else{
            if(!file_exists($this->photo()->get()->first()->full_path)){
                $this->makePhoto($this->photo()->get()->first()->name);
            }
        }
		return $this->photo()->get()->first()->url;
	}

	public function getCanDeleteAttribute(){
    	if(!session()->has("user")) return false;
    	return $this->id !== session()->get("user")->id;
	}

	public function getCanEditAttribute(){
    	return true;
//    	return (session()->has("user"));
	}

	public function representatives(){
    	return $this->hasMany(Representative::class,'user_id','id');
	}

	public function setRepresentativesAttribute($representatives){
		$this->representatives()->delete();
		$representativesCollection = collect([]);
		foreach ($representatives as $representativeData){
			$representative = new Representative($representativeData);
			$representativesCollection->push($representative);
		}
		$this->representatives()->saveMany($representativesCollection);
	}

	public static function searchClient($value)
    {
        if(empty($value)){
            return false;
        }
        return User::where('role_id', UserRole::CLIENT)
            ->where('name', 'ILIKE', "%$value%")
            ->pluck('id')
            ->toArray();
    }
}
