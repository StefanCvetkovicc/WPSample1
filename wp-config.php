<?php
/**
 * Основне поставке Вордпреса.
 *
 * Ова датотека се користи од стране скрипте за прављење wp-config.php током
 * инсталирања. Не морате да користите веб место, само умножите ову датотеку
 * у "wp-config.php" и попуните вредности.
 *
 * Ова датотека садржи следеће поставке:
 * * MySQL подешавања
 * * тајне кључеве
 * * префикс табеле
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL подешавања - Можете добити ове податке од свог домаћина ** //
/** Име базе података за Вордпрес */
define( 'DB_NAME', 'WPShop1' );

/** Корисничко име MySQL базе */
define( 'DB_USER', 'root' );

/** Лозинка MySQL базе */
define( 'DB_PASSWORD', '' );

/** MySQL домаћин */
define( 'DB_HOST', '' );

/** Скуп знакова за коришћење у прављењу табела базе. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Не мењајте ово ако сте у сумњи. */
define( 'DB_COLLATE', '' );

/**#@+
 * Јединствени кључеви за аутентификацију.
 *
 * Промените ово у различите јединствене изразе!
 * Можете направити ово користећи {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org услугу тајних кључева}
 * Ово можете променити у сваком тренутку да бисте поништили све постојеће колачиће.
 * Ово ће натерати кориснике да се поново пријаве.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lX7PzT-}R 0FmyK</%UhI#Ay!H5?r5 X<TUC3 c210O/:{Oe 6NaB)0gUjJ*.V``' );
define( 'SECURE_AUTH_KEY',  'z0stQvYGGJ6D:>Hhl^M n-5Ih5Vnn7X|)S_SAN|sUk$fjhqS://zW-M@[z8R@i3g' );
define( 'LOGGED_IN_KEY',    '|S#H&B1&K)~X^;g7m4M7l{7`6L0`pt.Z]+$#??aMi=%5PM%3Yaf_ZVv~CfbU9HSf' );
define( 'NONCE_KEY',        'eH*_mQG=M,~_?Z*bkK/o11u=Km)-X3NnlVL0y$W*_zw)@G 01^cCfFRuXn]r)Kik' );
define( 'AUTH_SALT',        '69!tV^_Z%wUeH3hxB+C}`g/#7[=r X#P(o@<DOgq*k)6asF(D1rzPAI?;};Nuie_' );
define( 'SECURE_AUTH_SALT', '}B:NOQ0b%[sb-y!Pv6Iy<{q4lLQ!khJ)[ve43}R$OU7X>3AQ()>!-H~]iC{9jp<f' );
define( 'LOGGED_IN_SALT',   '<VFE8s: <*zfN<zAt-o.d^fAo[b6dhDO*S`t+#0>bw}=,n_c#B|EbsgmB:c1?%=E' );
define( 'NONCE_SALT',       '8)R/0^c?8}zO!aTOA)7r&(aU_ZTk)KzhB]Ks6rurDJni&SQ0A67C`c~Q5FQa,5O~' );

/**#@-*/

/**
 * Префикс табеле Вордпресове базе података.
 *
 * Можете имати више инсталација Вордпреса у једној бази уколико
 * свакој дате јединствени префикс. Само бројеви, слова и доње цртице!
 */
$table_prefix = 'wp_';

/**
 * За градитеље: исправљање грешака у Вордпресу ("WordPress debugging mode").
 *
 * Промените ово у true да бисте омогућили приказ напомена током градње.
 * Веома се препоручује да градитељи тема и додатака користе WP_DEBUG
 * у својим градитељским окружењима.
 *
 * За више података о осталим константама које могу да се
 * користе током отклањања грешака, посетите Документацију.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* То је све, престаните са уређивањем! Срећно објављивање. */

/** Апсолутна путања ка Вордпресовом директоријуму. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Поставља Вордпресове променљиве и укључене датотеке. */
require_once( ABSPATH . 'wp-settings.php' );
