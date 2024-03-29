<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Widget extends Model
{
	use HasFactory;

	protected $casts = [
		'settings' => 'json'
	];

	private static $headers =
	[
		'X-Gismeteo-Token' => '5ea2ce223fdf28.80935958',
		'Accept-Encoding' => 'deflate, gzip'
	];

	public static function getAll()
	{
		$widgets = self::getSettings()
			->where( 'active', 1 )
			->get()
			->keyBy('id');

		if ( isset( $widgets[1] ) )
		{
			if ( isset($widgets[1]['settings']['city']) )
				$city = $widgets[1]['settings']['city'];

			else {

				$city = 'Москва';
				$widgets[1]['settings'] = ['city' => $city];
			}

			$widgets[1]['data'] = self::weather( $city );
		}

		return $widgets;
	}

	public static function getSettings()
	{
		return self::select('widgets.*', 'ws.settings', 'ws.active')
			->leftJoin('widget_settings as ws', 'widgets.id', '=', 'ws.w_id')
			->where( 'u_id', Auth::id() );
	}

	private static function weather( $city )
	{
		$cache = [];
		$update_every = 1200; // 20 min
		$city = $city ?: 'Москва';

		if ( Storage::exists('gismetio.json') )
			$cache = json_decode( Storage::get('gismetio.json'), true);

		if ( isset( $cache[ $city ] ) )
		{
			if ( time() - $cache[ $city ]['last_update'] < $update_every )
				return $cache[ $city ];
		}

		$city_id = isset($cache[ $city ]['city_id']) ? $cache[ $city ]['city_id'] : self::getCityId( $city );

		if ( !$city_id ) return false;

		$res = self::getWeather( $city_id );

		$cache[ $city ] =
		[
			'city_id' => $city_id,
			'last_update' => time(),
			'today' => [
				'temperature' => sprintf( '%+2d', $res['response'][0]['temperature']['air']['max']['C'] ),
				'icon' => $res['response'][0]['icon']
			],
			'tomorrow' => [
				'temperature' => sprintf( '%+2d', $res['response'][1]['temperature']['air']['max']['C'] ),
				'icon' => $res['response'][1]['icon']
			]
		];

		Storage::put('gismetio.json', json_encode( $cache ));

		return $cache[ $city ];
	}

	private static function getCityId( $city )
	{
		$url = 'https://api.gismeteo.net/v2/search/cities/?lang=ru&query='.$city;

		$city_info = Http::withHeaders( self::$headers )->get( $url )->json();

		return $city_info['response']['items'][0]['id'];
	}

	private static function getWeather( $city_id )
	{
		$url = 'https://api.gismeteo.net/v2/weather/forecast/aggregate/'.$city_id.'/?days=3';

		return Http::withHeaders( self::$headers )->get( $url )->json();
	}

}
