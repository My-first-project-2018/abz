<?php declare( strict_types = 1 );

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 *
 * @package App\Http\Repositories
 */
abstract class BaseRepository {
	/** @var \Illuminate\Database\Eloquent\Model $model */
	public $model;
	
	/**
	 * @param string $hash
	 *
	 * @return mixed
	 */
	public function onlyHash (string $hash) : Model
	{
		return $this->model->whereHash($hash)->first();
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all () : Collection
	{
		return $this->model->all();
	}
	
	/**
	 * @param string $slug
	 *
	 * @return mixed
	 */
	public function onlySlug (string $slug) : Model
	{
		return $this->model->whereSlug($slug)->first();
	}
	
	/**
	 * @return Model
	 */
	public function getFirstModel () : Model
	{
		return $this->model->first();
	}
}