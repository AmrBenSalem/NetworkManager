<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
	public $timestamps = false;
    protected $table = 'scans';
}
