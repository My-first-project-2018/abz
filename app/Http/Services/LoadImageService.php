<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Exceptions\ErrorUploadImageException;
use Illuminate\Http\Request;


/**
 * Class LoadImageService
 *
 * @package App\Http\Services
 */
class LoadImageService {
	
	private $storage;
	
	public function __construct ()
	{
		$this->storage = \Storage::disk('uploads');
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return string
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	public function upload (Request $request): string
	{
		if($request->hasFile('img') && $request->file('img')->isValid())
		{
			$imageName = str_random(8) . '.' . $request->file('img')->getClientOriginalExtension();
	
			$result = $request->file('img')->storeAs('',$imageName,'uploads');
			
			if($result)
				return $this->storage->url($imageName);
			
		}
		
		throw new ErrorUploadImageException('error upload image employee.');
	}
}