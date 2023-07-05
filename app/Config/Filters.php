<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use App\Filters\AdminFilter;
use App\Filters\KasirFilter;

class Filters extends BaseConfig
{
	/**
	 * Configures aliases for Filter classes to
	 * make reading things nicer and simpler.
	 *
	 * @var array
	 */
	public $aliases = [
		'csrf'     => CSRF::class,
		'toolbar'  => DebugToolbar::class,
		'honeypot' => Honeypot::class,
		'AdminFilter' => AdminFilter::class,
		'KasirFilter' => KasirFilter::class,
	];

	/**
	 * List of filter aliases that are always
	 * applied before and after every request.
	 *
	 * @var array
	 */
	public $globals = [
		'before' => [
			// 'honeypot',
			// 'csrf',
			'AdminFilter' => ['except' => [
				'Auth', 'Auth/*',
			]],
			'KasirFilter' => ['except' => [
				'Auth', 'Auth/*',
			]],
		],
		'after'  => [
			'toolbar',
			'AdminFilter' => ['except' => [
				'Admin', 'Admin/*',
				'Barang', 'Barang/*',
				'Laporan', 'Laporan/*',
				'Penjualan', 'Penjualan/*'
			]],
			'KasirFilter' => ['except' => [
				'Admin', 'Admin/*',
				'Penjualan', 'Penjualan/*'
			]],
			// 'honeypot',
		],
	];

	/**
	 * List of filter aliases that works on a
	 * particular HTTP method (GET, POST, etc.).
	 *
	 * Example:
	 * 'post' => ['csrf', 'throttle']
	 *
	 * @var array
	 */
	public $methods = [];

	/**
	 * List of filter aliases that should run on any
	 * before or after URI patterns.
	 *
	 * Example:
	 * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
	 *
	 * @var array
	 */
	public $filters = [];
}
