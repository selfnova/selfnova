<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class News extends Model
{
	use HasFactory, AsSource, Filterable, Attachable;

	protected $casts = [
		'created_at' => 'datetime:d.m.Y в H:i',
	];

	protected function serializeDate($date)
	{
		return $date->timezone('Europe/Moscow')->format('Y-m-d H:i:s');
	}
}
