<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link https://fr.wordpress.org/support/article/editing-wp-config-php/ Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'wordpress' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'X`<l/Wm9p4F.sVcN7A27)Pgs:~dkM_ThC^>QtQhn9:}`S|#TQ|:ZjJxoUrS3N7rK' );
define( 'SECURE_AUTH_KEY',  '%6FFmV-N%&-> m*yMIy>ah(?-1S1kYHC=f*u>p)T[cWTpj`>}kY~-uFB@lq$]+}`' );
define( 'LOGGED_IN_KEY',    'eu>/@fB.1j/91-FnJFd}{&I7<h@)#yUxi^1]1ydTf8-UU[>J[hA!]Fw{_|hJkF,%' );
define( 'NONCE_KEY',        'GXD_UFRo]`aNm2ME `Z/!{@JVz-V%!m|rF:3p`?%Hbx)%X1CzW6-0c`6!umH$(J;' );
define( 'AUTH_SALT',        '>.,]0kAZvUT,08]q9omt`YEK=Rll$-6kVV zO>1|kz@!~uu}jb}3uEj8*qr#t^TM' );
define( 'SECURE_AUTH_SALT', 'W?C<.pjj?a,dehMB3{;5R#RpvD~1ZubTLqMnO:H`9LBP#NP]O1[,LexjFEfeP5@)' );
define( 'LOGGED_IN_SALT',   '6.E]n?V<1X)1v,MGX.5]QkcT6WWoj*#qoc7/[u:3!Cn,&`a/x=3OD8-r9%nzX`hH' );
define( 'NONCE_SALT',       '0yg<#ktFO1E;:P`GwL-~JJ!7}yz]5mz(V>^Sx8+<IwY/FH)1(q2KjOa{4fLK1FDL' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs et développeuses : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs et développeuses d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur la documentation.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
