<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikation extends Model
{
  use HasFactory;

  protected $guarded = [];

  public $timestamps = false;
  protected $casts = [
    'authors' => 'array',
    'organisation' => 'array',
    'fullText' => 'array',
    'refBoocks' => 'array',
    'langs' => 'array',
    'UDC' => 'array',
    'FTfiles' => 'array',
    'RBfiles' => 'array',
  ];


  public function aut()
  {
    return $this->hasMany(Author::class, 'pub_id', 'id');
  }

}
