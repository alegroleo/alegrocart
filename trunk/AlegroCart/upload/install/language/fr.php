<?php
// Heading
$_['heading_title']        = 'AlegroCart Installation';
$_['heading_step1']        = 'Étape 1/3 - Détails BDD';
$_['heading_step2']        = 'Étape 2/3 - Administration';
$_['heading_step3']        = 'Terminé!';

// Error
$_['error_not_found']      = '%s non trouvé! (assurez-vous de l\'avoir uploadé)';
$_['error_not_777']        = '%s n\'est pas inscriptible! (chmod a+w ou chmod 777)';
$_['error_not_666']        = '%s n\'est pas inscriptible! (chmod a+w ou chmod 666)';
$_['error_php']            = 'PHP 5.0 ou supérieur est nécessaire pour AlegroCart';
$_['error_session']        = 'Vous devez désactiver "session.auto_start" dans php.ini pour utiliser AlegroCart';
$_['error_mysql']          = 'L\'extension MySql est requise pour faire fonctionner AlegroCart';
$_['error_gd']             = 'L\'extension GD est requise pour faire fonctionner AlegroCart';
$_['error_upload']         = 'L\'upload de fichiers est requis pour pouvoir faire fonctionner AlegroCart';
$_['error_zlib']           = 'ZLIB doit être chargée dans php.ini pour faire fonctionner AlegroCart';
$_['error']                = 'Les erreurs suivantes sont survenues:';
$_['error_fix']            = 'Veuillez corriger les erreurs ci-dessus, installation arrêtée!';
$_['error_dbhost']         = 'Un hôte de base de données est requis';
$_['error_dbuser']         = 'Un nom d\'utilisateur de base de données est requis';
$_['error_dbname']         = 'Un nom de base de données est requis';
$_['error_dbconnect']      = 'Connexion au serveur de base de données impossible avec le nom d\'utilisateur et le mot de passe fournis.';
$_['error_dbperm']         = 'Impossible d\'accéder à la base de données. Vérifiez que vous avez les permissions, et qu\'elle existe sur le serveur.';
$_['error_sql']            = 'Impossible de trouver le script SQL d\'installation: %s.';
$_['error_new_admin_name'] = 'Un nouveau nom pour le répertoire admin est requis !';
$_['error_admin_uname']    = 'Un nom Administrateur est requis';
$_['error_admin_passw']    = 'Un mot de passe Administrateur est requis';
$_['error_write']          = 'Impossible d\'écrire dans le fichier %s.';
$_['error_open']           = 'Impossible d\'ouvrir le fichier %s en écriture.';
$_['error_rename']         = 'Le répertoire admin ne peut pas être renommé. Renommez-le manuellement, par exemple via une connection FTP puis rechargez cette page.';
$_['error_alphanumeric']   = 'Utilisez uniquement les minuscules de l\'alphabet Anglais, le souligné, le trait d\'union et les chiffres, sans espace.';
$_['error_length']         = 'Le nouveau nom doit être composé de 5 à 15 caractères !';
$_['error_restricted']     = '\'%s\' n\'est pas un nom autorisé.';
$_['error_dir']            = 'Erreur dans la structure du répertoire.';
$_['error_post']           = 'Le nom de répertoire donné ne correspond pas à celui renommé.';

// Text
$_['ac']                   = 'AlegroCart Accueil';
$_['acforum']              = 'AlegroCart Support';
$_['fresh']                = 'UNIQUEMENT POUR LES NOUVELLES INSTALLATIONS! VOTRE BASE DE DONNÉES SERA SUPPRIMÉE!';
$_['database_details']     = 'Veuillez entrer vos détails de connexion à la base de données.';
$_['dbhost']               = 'Hôte de la base de données:';
$_['dbuser']               = 'Nom d\'utilisateur de la base de données:';
$_['dbpassw']              = 'Mot de passe de la base de données:';
$_['dbname']               = 'Nom de la base de données:';
$_['continue']             = 'Continuer';
$_['rename']               = 'Pour empêcher toute activité malicieuse dans votre répertoire d\'administration, veuillez saisir un nouveau nom pour le répertoire principal admin. Ce nom de répertoire doit être différent de \'admin\' ou \'administration\' parce que ceux-ci ne sont pas autorisés dans la nouvelle structure sécurisée. Le répertoire uploadé admin aura son nom changé en le nouveau et ce répertoire contiendra tous vos fichiers admin.';
$_['new_admin']            = 'Nouveau nom pour le répertoire admin:';
$_['admin_details']        = 'Veuillez entrer un nom d\'Administrateur et son mot de passe.';
$_['uname']                = 'Nom d\'utilisateur:';
$_['passw']                = 'Mot de passe:';
$_['success']              = '%s a été modifié avec succès.';
$_['config']               = 'Rendez \'config.php\' non-inscriptible (chmod go-w ou chmod 644).';
$_['htaccess']             = 'Rendez \'.htaccess\' non-inscriptible (chmod go-w 644).';
$_['congrat']              = 'Félicitations! Vous avez installé AlegroCart avec succès.';
$_['congrat_upg']          = 'Félicitations! Vous avez mis à jour AlegroCart avec succès.';
$_['shop']                 = 'Boutique en ligne';
$_['admin']                = 'Administration';
$_['method']               = 'Méthode d\'installation:';
$_['default_install']      = 'Installation par défaut';
$_['clean_install']        = 'Nettoyer l\'installation';
$_['default_expl']         = 'Cela installera des exemples de produits, catégories, fabricants, images, avis, etc... Afin de tester AlegroCart.';
$_['clean_expl']           = 'Cela supprimera tous les produits et les données associées aux produits. Ceci créera une installation propre sans aucun produit.';
$_['rename_expl']          = 'Utilisez uniquement les minuscules de l\'alphabet Anglais, le souligné, le trait d\'union et les chiffres, sans espace. Le nouveau nom doit être composé de 5 à 15 caractères !';
?>
