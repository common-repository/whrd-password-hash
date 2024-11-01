<?php
/**
 * Password Hash
 *
 * @package PasswordHash
 * @author Sérgio 'wherd' Leal <hello@wherd.name>
 * @license GPL-2.0 or later
 *
 * @wordpress-plugin
 * Plugin Name: Password Hash
 * Plugin URI:  https://wordpress.org/plugins/whrd-password-hash/
 * Description: Replaces wp_hash_password and wp_check_password with PHP
 *              password_hash and password_verify if PHP has support for them.
 * Version:     1.0.0
 * Author:      Sérgio 'wherd' Leal
 * Author URI:  https://github.com/wherd/
 * License:     GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: password-hash
 * Domain Path: /languages
 * Tags: bcrypt, password, hash, security, password_hash, password_verify
 *
 * Password Hash is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Password Hash is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Remove Version Arg. If not, see license.txt
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'wp_check_password' ) && function_exists( 'password_verify' ) ) :
	/**
	 * Checks the plaintext password against the encrypted Password.
	 *
	 * Maintains compatibility between old version, the new cookie authentication
	 * protocol using PHPass library and the new bcrypt. The $hash parameter is
	 * the encrypted password and the function compares the plain text password
	 * when encrypted similarly against the already encrypted password to see if
	 * they match.
	 *
	 * @since 1.0.0
	 *
	 * @global PasswordHash $wp_hasher PHPass object used for checking the
	 *         password against the $hash + $password.
	 *
	 * @uses   PasswordHash::CheckPassword
	 *
	 * @param  string     $password Plaintext user's password.
	 * @param  string     $hash     Hash of the user's password to check against.
	 * @param  string|int $user_id  User ID. (Optional).
	 * @return bool False, if the $password does not match the hashed password,
	 */
	function wp_check_password( $password, $hash, $user_id = '' ) {
		global $wp_hasher;

		// If the hash is still md5...
		if ( strlen( $hash ) <= 32 ) {
			$check = hash_equals( $hash, md5( $password ) );

			if ( $check && $user_id ) {
					// Rehash using new hash.
					wp_set_password( $password, $user_id );
					$hash = wp_hash_password( $password );
			}

			return apply_filters( 'check_password', $check, $password, $hash, $user_id );
		}

		// If the hash is still PHPass.
		if ( 0 === strpos( $hash, '$P$' ) ) {
			// If the stored hash is longer than an MD5, presume the new style
			// phpass portable hash.
			if ( empty( $wp_hasher ) ) {
				$wp_hasher = new PasswordHash( 8, true );
			}

			$check = $wp_hasher->CheckPassword( $password, $hash );

			if ( $check && $user_id ) {
				wp_set_password( $password, $user_id );
				$hash = wp_hash_password( $password );
			}

			return apply_filters( 'check_password', $check, $password, $hash, $user_id );
		}

		$check = password_verify( $password, $hash );

		// This filter is documented in wp-includes/pluggable.php.
		return apply_filters( 'check_password', $check, $password, $hash, $user_id );
	}
endif;

if ( ! function_exists( 'wp_hash_password' ) && function_exists( 'password_hash' ) ) :
	/**
	 * Create a hash (encrypt) of a plain text password.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $password Plain text user password to hash.
	 * @return string The hash string of the password.
	 */
	function wp_hash_password( $password ) {
		return password_hash( trim( $password ), PASSWORD_DEFAULT, array() );
	}
endif;
