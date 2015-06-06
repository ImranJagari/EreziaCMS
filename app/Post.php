<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	protected $table = 'posts';

	public $timestamps = false;

	public function author()
	{
		return $this->hasOne('App\Account', 'Id', 'author_id');
	}

	public function comments()
	{
		return $this->hasMany('App\Comment', 'post_id', 'id')->orderBy('date', 'desc');
	}

}
