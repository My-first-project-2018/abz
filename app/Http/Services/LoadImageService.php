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
		$this->checkImage($request);
	
		$imageName = str_random(8) . '.' . $request->file('img')->getClientOriginalExtension();

		$result = $request->file('img')->storeAs('',$imageName,'uploads');
		
		return $this->storage->url($imageName);
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return string
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	public function reload (Request $request): string
	{
		$this->checkImage($request);
		
		$oldSrcImage = $request->get('old');
		
		preg_match('%employees\/([^\/]+)%', $oldSrcImage, $matches);
		
		$image = array_last($matches);
		
		if($this->storage->has($image))
		{
			$this->storage->delete($image);
		}
		
		return $this->upload($request);
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	private function checkImage (Request $request): void
	{
		if($request->hasFile('img') && $request->file('img')->isValid())
		return;
		
		throw new ErrorUploadImageException('error upload image employee.');
	}
	
}