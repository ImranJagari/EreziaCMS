<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	protected $fillable = [];

	protected $table = 'comments';

	public $timestamps = false;

	public function author()
	{
		return $this->hasOne('App\Account', 'Id', 'author_id');
	}

}
