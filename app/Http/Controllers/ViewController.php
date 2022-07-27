<?php

namespace App\Http\Controllers;

use App\Models\View;
use App\Models\User;
use Illuminate\Http\Request;

class ViewController extends Controller
{
	public function index(Request $request, $post_id)
	{
		$data = User::whereIn('id', View::where('post_id', $post_id)
				->orderBy('id', 'DESC')
				->pluck('viewer_id')
				->toArray()
			)
			->simplePaginate(50);

		return $data;
	}

    public function mark(Request $request)
	{
		$validated = $request->validate([
			'post_id'  => 'nullable|required_without_all:group_id,user_id|integer|exists:posts,id',
			'group_id' => 'nullable|required_without_all:post_id,user_id|integer|exists:groups,id',
			'user_id'  => 'nullable|required_without_all:post_id,group_id|integer|exists:users,id',
		]);
		$validated['viewer_id'] = $request->user()->id;

		$created = View::create($validated)->id;

		return ['success' => (bool) $created ];
	}
}
