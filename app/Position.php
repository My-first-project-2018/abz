<?php declare( strict_types = 1 );

namespace App;

use App\Http\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Position
 *
 * @package App
 */
class Position extends Model
{
	use SlugTrait;
	
	protected $hidden = ['id', 'department_id'];
	
	protected $fillable = [
		'name',
		'slug',
		'department_id',
		'parent_id',
	];
	
	/**
	 * @return HasMany
	 */
	public function employees (): HasMany
	{
		return $this->hasMany(Employee::class);
	}
	
	/**
	 * @return BelongsTo
	 */
	public function department (): BelongsTo
	{
		return $this->belongsTo(Department::class);
	}
}
