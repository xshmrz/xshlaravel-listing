<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Core;

use App\Traits\TraitUpload;
use Bjerke\ApiQueryBuilder\QueryBuilderModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Migration
 * 
 * @property int $id
 * @property string $migration
 * @property int $batch
 *
 * @package App\Models\Core
 */
class Migration extends Model
{
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const MIGRATION = 'migration';
	const BATCH = 'batch';
	protected $table = 'migration';
	public $timestamps = false;

	protected $columns = [
		'id',
		'migration',
		'batch'
	];

	protected $casts = [
		self::ID => 'int',
		self::BATCH => 'int'
	];

	protected $fillable = [
		self::MIGRATION,
		self::BATCH
	];
}
