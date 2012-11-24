<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/Editing_wp-config.php Modifier
 * wp-config.php} (en anglais). C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'dope');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XjJ4N^+v8=#EEZQv;^Oq_`%YHn9C?.ap)^;dQykx4+dq]%)r-q?^ sbVuS+ba+/L');
define('SECURE_AUTH_KEY',  '#d<G-W|_ln(V][)+kC$`[ASYLM!(BiH55DxfYD9w9b{TB?U=~6R|yrt~}^+}vuHJ');
define('LOGGED_IN_KEY',    '2Jl01+I*Fbi=qin,XZY@++<*V|DiQV?yt^i0BP[7KB]?rMElUG|W?xY)K>n[R d)');
define('NONCE_KEY',        '*|5s7J$-DU|}T|n1i/coCiI0%< l(%Yh@;xHnFe?hv&h9ty&Ze+h|@;U}>po$3ZY');
define('AUTH_SALT',        'L?v$Laj@/uR7}qgC8R)Q[_>sIesdJ1jN4C[lolndyumx}b+gv,pz~95e$7d`DQ#{');
define('SECURE_AUTH_SALT', 'S2c@XHCP<C#+<uNB(2{Gd4C-uvGu-WynbM:2S6mHxmG(<oqTJ[gmDhRku1AZZd}G');
define('LOGGED_IN_SALT',   'QqLvF.;R[SI8UwRH!+-uQKfVMx9FfBPV+3:92EUz#S|&Sx+3D8/AUIl26;Tx@Qln');
define('NONCE_SALT',       ']{_sF}cD*}-3~Y.(5}:g-|WlE9Kft+s]uS.Fqfx,|t9[^DC[|{#_+ph)^ihga|Yx');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');