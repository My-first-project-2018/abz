<?php declare( strict_types = 1 );

namespace App\Http\Services;


/**
 * Class LoadImageService
 *
 * @package App\Http\Services
 */
class LoadImageService {
	
	private $storage;
	
	public function __construct ()
	{
		$this->storage = Storage::disc('uploads');
	}
	
	public function upload ()
	{
	
	}
}