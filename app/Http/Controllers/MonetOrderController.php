<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\MonetOrder;
use App\Models\User;
use Illuminate\Http\Request;

class MonetOrderController extends Controller
{
    public function store(Request $request)
	{
		$validated = $request->validate([
			'group_id' => 'nullable|required_without:user_id|integer|exists:groups,id',
			'user_id'  => 'nullable|required_without:group_id|integer|exists:users,id',
		]);
		$validated['sender_id'] = $request->user()->id;

		$check = $this->check($validated);

		if ($check !== true) {
			return [
				'message' => $check,
			];
		}


		$created = MonetOrder::create($validated);

		return [
			'success' => !!$created->id
		];
	}



	private function check($data)
	{
		// пока нельзя подавать заявку на других пользователей
		if ( isset($data['user_id']) && $data['sender_id'] != $data['user_id']) {
			return 'Невозможно подавать заявку на этот профиль';
		}

		if ( MonetOrder::where( $data )->exists() ) {
			return 'Заявка уже существует';
		}

		// больше 3 заявок за час низя
		$countLastHours = MonetOrder::where( $data )
				->whereDate('created_at', '>', now()->subHours(1))
				->count();

		if ( $countLastHours > 3 ) {
			return 'Много заявок за последнее время, попробуйте позже';
		}

		if (isset($data['group_id'])) {
			$followers = Group::find($data['group_id'])->followers;
		}
		elseif (isset($data['user_id'])) {
			$followers = User::find($data['user_id'])->followers;
		}

		if ($followers < 500) {
			return 'На странице слишком мало подписчиков';
		}

		return true;
	}
}
