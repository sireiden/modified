<?php
/* --------------------------------------------------------------
   Amazon Pay  V3.1.0
   am_apa.php 2017-02-10

   alkim media
   http://www.alkim.de

   patworx multimedia GmbH
   http://www.patworx.de/

   Released under the GNU General Public License
   --------------------------------------------------------------
*/
DEFINE ('BOX_CONFIGURATION_777', 'Options relatives aux Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_TEXT_TITLE', 'Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_STATUS_TITLE', 'Activer le module ?');
DEFINE('MODULE_PAYMENT_AM_APA_STATUS_DESC', 'La s&eacute;lection de cette option vous permet d&lsquo;activer et de d&eacute;sactiver la connexion et le paiement avec Amazon.');
DEFINE('MODULE_PAYMENT_AM_APA_MERCHANTID_TITLE', 'ID vendeur Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MERCHANTID_DESC', 'Votre ID vendeur Amazon (Merchant ID)');
DEFINE('MODULE_PAYMENT_AM_APA_MARKETPLACEID_TITLE', 'Identifiant de marketplace Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MARKETPLACEID_DESC', 'Votre identifiant de marketplace Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_ACCESKEY_TITLE', 'Identifiant de la cl&eacute; d&lsquo;acc&egrave;s MWS');
DEFINE('MODULE_PAYMENT_AM_APA_ACCESKEY_DESC', 'Permet de communiquer et de s&lsquo;authentifier avec les Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_SECRETKEY_TITLE', 'Cl&eacute; secr&egrave;te');
DEFINE('MODULE_PAYMENT_AM_APA_SECRETKEY_DESC', 'Permet de communiquer et de s&lsquo;authentifier avec les Amazon Pay');
define('MODULE_PAYMENT_AM_APA_ALLOWED_TITLE', 'Zones autoris&eacute;es');
define('MODULE_PAYMENT_AM_APA_ALLOWED_DESC', 'Pr&eacute;cisez <b>individuellement</b> les zones auxquelles s&lsquo;applique l&lsquo;autorisation de ce module. (par ex : FR, DE. Si vide, l&lsquo;autorisation s&lsquo;applique &acirc; toutes les zones)');
DEFINE('MODULE_PAYMENT_AM_APA_MODE_TITLE', 'Mode de fonctionnement');
DEFINE('MODULE_PAYMENT_AM_APA_MODE_DESC', '');
DEFINE('MODULE_PAYMENT_AM_APA_PROVOCATION_TITLE', 'Provoquer une erreur (Sandbox uniquement)');
DEFINE('MODULE_PAYMENT_AM_APA_PROVOCATION_DESC', 'Permet &agrave; l&lsquo;administrateur de simuler des paiements refus&eacute;s en raison de transactions ou de comptes bloqu&eacute;s.');
DEFINE('MODULE_PAYMENT_AM_APA_ORDER_STATUS_OK_TITLE', 'Statut de commande pour les paiements autoris&eacute;s');
DEFINE('MODULE_PAYMENT_AM_APA_ORDER_STATUS_OK_DESC', 'Ce statut est activ&eacute; apr&egrave;s autorisation du paiement par Amazon.');
DEFINE ('MODULE_PAYMENT_AM_APA_ALLOW_GUESTS_TITLE','Autoriser les commandes en tant qu&lsquo;invit&eacute; ?');
DEFINE ('MODULE_PAYMENT_AM_APA_ALLOW_GUESTS_DESC','En activant cette option, les clients peuvent &eacute;galement payer via les Amazon Pay, s&lsquo;ils ne disposent pas de compte client dans la boutique.');
define('AMZ_YES', 'Oui');
define('AMZ_NO', 'Non');
define('AMZ_SANDBOX', 'Mode test');
define('AMZ_LIVE', 'Mode en ligne');
define('AMZ_AUTHORIZATION_CONFIG_TITLE', 'Quand et comment le paiement doit-il être autoris&eacute; ?');
define('AMZ_AUTHORIZATION_CONFIG_DESC', 'L&lsquo;autorisation imm&eacute;diate comporte un risque de refus l&eacute;g&egrave;rement plus &eacute;lev&eacute;. Il est conseill&eacute; de choisir cette option si, par exemple, vous vendez des produits &agrave; t&eacute;l&eacute;charger et que vous avez besoin d&lsquo;obtenir une confirmation de paiement imm&eacute;diate, ou si vous souhaitez informer directement le client de l&lsquo;&eacute;tat d&lsquo;avancement de l&lsquo;autorisation.<br/><br />Une fois l&lsquo;autorisation re&ccedil;ue, vous disposez de 30 jours pour exp&eacute;dier l&lsquo;article et d&eacute;clencher le paiement. Amazon garantit la r&eacute;alisation du paiement dans les sept premiers jours.');
define('AMZ_CAPTURE_CONFIG_TITLE', 'Quand et comment le paiement doit-il être encaiss&eacute; ?');
define('AMZ_CAPTURE_CONFIG_DESC', '');
define('AMZ_FAST_AUTH', 'pendant le paiement/avant la confirmation de la commande');
define('AMZ_AUTH_AFTER_CHECKOUT', 'directement apr&egrave;s la commande');
define('AMZ_CAPTURE_AFTER_AUTH', 'directement apr&egrave;s confirmation de l&lsquo;autorisation automatique');
define('AMZ_AFTER_SHIPPING', 'apr&egrave;s exp&eacute;dition');
define('AMZ_MANUALLY', 'manuellement');
define('AMZ_SHIPPED_STATUS_TITLE', 'Statut de commande pour les commandes envoy&eacute;es');
define('AMZ_SHIPPED_STATUS_DESC', 'Important pour la r&eacute;ception du paiement apr&egrave;s l&lsquo;exp&eacute;dition');
define('AMZ_REVOCATION_ID_TITLE', 'Identifiant de contenu pour les informations relatives aux conditions de r&eacute;tractation');
define('AMZ_AGB_ID_TITLE', 'Identifiant de contenu pour les conditions g&eacute;n&eacute;rales de vente');
define('AMZ_BUTTON_SIZE_TITLE', 'Taille du bouton de paiement Amazon');
define('AMZ_BUTTON_COLOR_TITLE', 'Couleur du bouton de paiement Amazon');
define('AMZ_BUTTON_COLOR_ORANGE', 'Amazon - jaune');
define('AMZ_BUTTON_COLOR_TAN', 'gris');
define('AMZ_BUTTON_SIZE_MEDIUM', 'normal');
define('AMZ_BUTTON_SIZE_LARGE', 'grand');
define('AMZ_BUTTON_SIZE_XLARGE', 'tr&egrave;s grand');
define('AMZ_TX_TYPE_HEADING', 'Type de transaction');
define('AMZ_TX_TIME_HEADING', 'D&eacute;lai');
define('AMZ_TX_STATUS_HEADING', 'Statut');
define('AMZ_TX_LAST_CHANGE_HEADING', 'Derni&egrave;re modification');
define('AMZ_TX_ID_HEADING', 'Identifiant de transactions Amazon');
define('AMZ_AUTH_TEXT', 'Autorisation');
define('AMZ_ORDER_TEXT', 'Commande');
define('AMZ_CAPTURE_TEXT', 'R&eacute;ception de paiement');
define('AMZ_REFUND_TEXT', 'Remboursement');
define('AMZ_TX_AMOUNT_HEADING', 'Montant');
define('AMZ_IPN_URL_TITLE', 'URL pour la notification de paiement instantan&eacute; Amazon');
define('AMZ_TX_EXPIRATION_HEADING', 'Valide jusqu&lsquo;&agrave;');
define('AMZ_HISTORY', 'Historique Amazon Pay');
define('AMZ_ORDER_AUTH_TOTAL', 'Montant autoris&eacute;');
define('AMZ_ORDER_CAPTURE_TOTAL', 'Montant per&ccedil;u');
define('AMZ_SUMMARY', 'R&eacute;sum&eacute; Amazon');
define('AMZ_ACTIONS', 'Actions Amazon');
define('AMZ_CAPTURE_FROM_AUTH_HEADING', 'Percevoir des paiements autoris&eacute;s');
define('AMZ_TX_ACTION_HEADING', 'Actions');
define('AMZ_CAPTURE_TOTAL_FROM_AUTH', 'Percevoir la totalit&eacute; du montant');
define('AMZ_AUTHORIZE', 'Autoriser le paiement');
define('AMZ_AUTHORIZATION_SUCCESSFULLY_REQUESTED', 'Demande d&lsquo;autorisation envoy&eacute;e');
define('AMZ_AUTHORIZATION_REQUEST_FAILED', 'Echec de la demande d&lsquo;autorisation');
define('AMZ_CAPTURE_SUCCESS', 'Paiement re&ccedil;u');
define('AMZ_CAPTURE_FAILED', 'Echec de la r&eacute;ception du paiement');
define('AMZ_AMOUNT_LEFT_TO_OVER_AUTHORIZE', 'Autorisation suppl&eacute;mentaire');
define('AMZ_AMOUNT_LEFT_TO_AUTHORIZE', 'Autorisation standard');
define('AMZ_TYPE', 'Type');
define('AMZ_REFUNDS', 'Remboursements');
define('AMZ_REFUND', 'Rembourser');
define('AMZ_REFUND_SUCCESS', 'Remboursement effectu&eacute;');
define('AMZ_REFUND_FAILED', 'Echec du remboursement');
define('AMZ_REFRESH', 'Actualiser');
define('AMZ_CAPTURE_AMOUNT_FROM_AUTH', 'Recevoir une partie du montant');
define('AMZ_REFUND_TOTAL', 'Rembourser int&eacute;gralement');
define('AMZ_REFUND_AMOUNT', 'Rembourser partiellement');
define('AMZ_TX_AMOUNT_REFUNDED_HEADING', 'Rembours&eacute;');
define('AMZ_TX_SUM', 'Somme');
define('AMZ_TX_AMOUNT_REFUNDABLE_HEADING', 'Encore possible');
define('AMZ_TX_AMOUNT_POSSIBLE_HEADING', 'Montant maximal possible');
define('AMZ_AUTHORIZE_AMOUNT', 'Autoriser les montants partiels');
define('AMZ_TX_AMOUNT_NOT_AUTHORIZED_YET_HEADING', 'Montant pas encore autoris&eacute;');
define('AMZ_REFUND_OVER_AMOUNT', 'Rembourser davantage');
define('AMZ_FINISHED_REFRESHING_ORDER', 'Commande actualis&eacute;e');
define('AMZ_OVER_AUTHORIZE_AMOUNT', 'Autoriser davantage');
define('MODULE_PAYMENT_AM_APA_IPN_STATUS_TITLE', 'Recevoir les mises &agrave; jour de statut par notification de paiement instantan&eacute;');
define('AMZ_CRON_URL_TITLE', 'URL pour les t&acirc;ches Cron');
define('MODULE_PAYMENT_AM_APA_CRON_STATUS_TITLE', 'Activer les t&acirc;ches Cron pour les mises &agrave; jour de statut');
define('AMZ_AGB_DISPLAY_MODE_TITLE', 'Affichage des conditions g&eacute;n&eacute;rales de vente et des conditions de r&eacute;tractation');
define('AMZ_AGB_DISPLAY_MODE_SHORT', 'Messages d&lsquo;information uniquement');
define('AMZ_AGB_DISPLAY_MODE_LONG', 'Pr&eacute;sentation compl&egrave;te avec une case &agrave; cocher');
define('AMZ_CRON_PW_TITLE', 'Mot de passe pour les t&acirc;ches Cron');
define('AMZ_SOFT_DECLINE_SUBJECT_TITLE', 'Objet de l&lsquo;e-mail : paiement refus&eacute;');
define('AMZ_PAYMENT_NAME_TITLE', 'Nom de l&lsquo;exp&eacute;diteur de l&lsquo;e-mail');
define('AMZ_PAYMENT_EMAIL_TITLE', 'Adresse de l&lsquo;exp&eacute;diteur de l&lsquo;e-mail');
define('AMZ_HARD_DECLINE_SUBJECT_TITLE', 'Objet de l&lsquo;e-mail : commande refus&eacute;e');
define('AMZ_SEND_MAILS_ON_DECLINE_TITLE', 'Pr&eacute;venir automatiquement les clients lorsque des paiements ont &eacute;t&eacute; refus&eacute;s');
define('AMZ_FASTAUTH_SOFT_DECLINED', 'Veuillez choisir un autre moyen de paiement');
define('AMZ_FASTAUTH_HARD_DECLINED', 'Votre paiement a &eacute;t&eacute; refus&eacute; par Amazon Pay : choisissez un autre moyen de paiement');
define('AMZ_UNKNOWN_ERROR', 'Votre commande n&lsquo;a pas pu être pass&eacute;e : essayez de choisir un autre moyen de paiement');
define('AMZ_CANCEL_ORDER', 'Annuler le processus de paiement Amazon');
define('AMZ_CLOSE_ORDER', 'Terminer la commande');
define('AMZ_ORDER_CANCELLED', 'Processus de commande annul&eacute;');
define('AMZ_ORDER_CLOSED', 'Commande termin&eacute;e');
define('AMZ_SHOW_ON_CHECKOUT_PAYMENT_TITLE', 'Afficher le bouton Amazon pour les paiements normaux');
define('AMZ_DEBUG_MODE_TITLE', 'Mode d&eacute;bogage');
define('AMZ_DEBUG_MODE_DESC', 'En mode d&eacute;bogage, le paiement via les Amazon Pay n&lsquo;est visible que par les Administrateurs');
define('AMZ_EXCLUDED_SHIPPING_TITLE', 'Exclure des modes de livraison');
define('AMZ_NEW_VERSION_AVAILABLE', 'Une nouvelle version de ce module est disponible');
define('AMZ_VERSION_IS_GOOD', 'Vous disposez de la derni&egrave;re version du module');
define('AMZ_EXCLUDE_PRODUCTS', 'Exclure des produits');
define('AMZ_SEARCH', 'Recherche');
define('AMZ_EXCLUDED_PRODUCTS', 'Produits exclus');
define('AMZ_SEARCH_RESULT', 'R&eacute;sultat de la recherche');
define('AMZ_EXCLUDED_PRODUCTS_TITLE', 'Exclure des produits du paiement avec Amazon');
define('AMZ_EXCLUDED_PRODUCTS_OPEN', ' ouvrir');
define('AMZ_ALL_MANUFACTURERS', 'Tous les fabricants');
define('AMZ_INCLUDE_ALL_PRODUCTS', 'Tout supprimer');
define('AMZ_EXCLUDE_ALL_PRODUCTS', 'Tout exclure');
define('AMZ_TEMPLATE_1', 'Mod&egrave;le 1');
define('AMZ_TEMPLATE_2', 'Mod&egrave;le 2');
define('AMZ_TEMPLATE_TITLE', 'S&eacute;lection du mod&egrave;le');
define('AMZ_CANCEL_ORDER_FROM_WALLET', 'Annuler le paiement avec Amazon Pay');
define('AMZ_WALLET_INTRO', 'Le moyen de paiement s&eacute;lectionn&eacute; n&lsquo;est pas disponible pour le moment. Veuillez choisir un autre moyen de paiement.');
define('AMZ_DOWNLOAD_ONLY_TITLE', 'Cette boutique propose uniquement des articles virtuels');
define('AMZ_DOWNLOAD_ONLY_DESC', 'Si vous s&eacute;lectionnez Oui, aucune adresse d&lsquo;exp&eacute;dition ne sera demand&eacute;e lors de la s&eacute;lection d&lsquo;autorisation pendant le processus de paiement');
define('AMZ_HEADING_AMAZON_PAYMENTS_ACCOUNT', 'Compte Amazon Pay');
define('AMZ_HEADING_GENERAL_SETTINGS', 'Param&egrave;tres g&eacute;n&eacute;raux');
define('AMZ_HEADING_DESIGN_SETTINGS', 'Param&egrave;tres d&lsquo;apparence');
define('AMZ_HEADING_IPN_SETTINGS', 'Param&egrave;tres de notification instantan&eacute;e de paiement');
define('AMZ_HEADING_CRONJOB_SETTINGS', 'Param&egrave;tres des t&acirc;ches Cron');
define('AMZ_HEADING_MAIL_SETTINGS', 'Options de messagerie');

# Update V2.01
define('AMZ_DB_UPDATE_WARNING', 'Vous avez mis &agrave; jour le module. Vous devez maintenant mettre &agrave; jour la base de donn&eacute;es.');
define('AMZ_DB_UPDATE_BUTTON_TEXT', 'Mettre &agrave; jour la base de donn&eacute;es');
define('AMZ_IPN_PW_TITLE', 'Mot de passe de notification de paiement instantan&eacute;');
define('AMZ_SET_SELLER_ORDER_ID_TITLE', 'Envoyer le num&eacute;ro de commande &agrave; Amazon');
define('AMZ_SET_SELLER_ORDER_ID_DESC', 'Attention : la suite de num&eacute;ros de commande peut ne pas être continue si des commandes ont &eacute;t&eacute; annul&eacute;es.');
define('AMZ_ORDER_REF_HEADING', 'R&eacute;f&eacute;rence de commande Amazon');
define('AMZ_ORDERS_ID_HEADING', 'Commande dans la boutique');
define('AMZ_TRANSACTION_HISTORY', 'Protocole de transaction Amazon');
define('AMZ_BACK', 'Retour');
define('AMZ_MERCHANT_ID_INVALID', 'Aucune action possible. Votre identifiant marchand ne correspond pas');
define('AMZ_STATUS_NONAUTHORIZED_TITLE', 'Etat des commandes qui ne sont pas autoris&eacute;es');

# Update V2.10
define('MODULE_PAYMENT_AM_APA_LPA_MODE_TITLE', 'Mode Connectez-vous/Payez');
define('MODULE_PAYMENT_AM_APA_LPA_MODE_DESC', '');
define('MODULE_PAYMENT_AM_APA_CLIENTID_TITLE', 'Identifiant client pour Connectez-vous et Payez');
define('MODULE_PAYMENT_AM_APA_CLIENTID_DESC', 'Cet identifiant sert &agrave; la communication et l&lsquo;authentification aupr&egrave;s de Connectez-vous et Payez avec Amazon');
define('AMZ_SET_ADDRESS_TITLE', 'Merci de vous être connect&eacute; avec Amazon Pay');
define('AMZ_SET_ADDRESS_INTRO', 'Pour continuer, vous devez utiliser une adresse standard. Vous pourrez choisir une adresse de livraison diff&eacute;rente au cours du paiement.');
define('AMZ_CONNECT_ACCOUNTS_TITLE', 'Merci de vous être connect&eacute; avec Amazon Pay');
define('AMZ_CONNECT_ACCOUNTS_INTRO', 'Nous avons identifi&eacute; un compte utilisant l&lsquo;adresse e-mail indiqu&eacute;e dans notre boutique. Veuillez ressaisir le mot de passe de la boutique afin de vous connecter avec votre compte Amazon.');
define('AMZ_PASSWORD', 'Votre mot de passe');
define('AMZ_CONNECT_ACCOUNTS', 'Connecter les comptes ');
define('AMZ_CONNECT_ACCOUNTS_ERROR', 'Erreur : mot de passe incorrect');
define('MODULE_PAYMENT_AM_APA_POPUP_TITLE', 'Si possible, afficher Connectez-vous avec Amazon dans une fenêtre contextuelle');
define('MODULE_PAYMENT_AM_APA_POPUP_DESC', 'Afficher Connectez-vous avec Amazon pr&eacute;f&eacute;rentiellement dans une fenêtre contextuelle ? Si ce n&lsquo;est pas possible, le client est redirig&eacute; vers Amazon pour qu&lsquo;il s&lsquo;identifie, puis revient sur la page de votre boutique. L&lsquo;affichage dans une fenêtre contextuelle est pris en charge uniquement si l&lsquo;ensemble de la boutique est prot&eacute;g&eacute; par SSL.');
define('AMZ_LOGIN_PROCESSING_TITLE', 'Merci de vous être connect&eacute; avec Amazon Pay');
define('AMZ_LOGIN_PROCESSING_INTRO', 'Vous allez être redirig&eacute; dans quelques secondes...');
define('AMZ_BUTTON_SIZE_TITLE', 'Taille du bouton de paiement Amazon (Mode Payez)');
define('AMZ_BUTTON_SIZE_TITLE_LPA', 'Taille du bouton de paiement Amazon (Mode Connectez-vous/Connectez-vous et Payez)');
define('AMZ_BUTTON_COLOR_TITLE', 'Couleur du bouton de paiement Amazon (Mode Payez)');
define('AMZ_BUTTON_COLOR_TITLE_LPA', 'Couleur du bouton de paiement Amazon (Mode Connectez-vous/Connectez-vous et Payez)');
define('AMZ_BUTTON_COLOR_TAN_LIGHT', 'Gris clair');
define('AMZ_BUTTON_COLOR_TAN_DARK', 'Gris fonc&eacute;');
define('AMZ_BUTTON_SIZE_SMALL', 'Petit');
define('AMZ_BUTTON_TYPE_LOGIN_TITLE', 'Type de bouton Connectez-vous');
define('AMZ_BUTTON_TYPE_PAY_TITLE', 'Type de bouton Payez');
define('AMZ_BUTTON_TYPE_LOGIN_LWA', 'Connectez-vous avec Amazon');
define('AMZ_BUTTON_TYPE_LOGIN_LOGIN', 'Connexion');
define('AMZ_BUTTON_TYPE_LOGIN_A', '-A- seul');
define('AMZ_BUTTON_TYPE_PAY_PWA', 'Payez avec Amazon');
define('AMZ_BUTTON_TYPE_PAY_PAY', 'Payer');
define('AMZ_BUTTON_TYPE_PAY_A', '-A- seul');
define('AMZ_SAVE', 'Enregistrer');
define('AMZ_CONFIGURATION', 'Configuration');
define('AMZ_PROTOCOL', 'Protocole pour la transaction');
define('AMZ_CLOSE_ORDER_ON_COMPLETE_CAPTURE_TITLE', 'Marquer les commandes comme ferm&eacute;es apr&egrave;s paiement de la somme compl&egrave;te');
define('AMZ_CLOSE_ORDER_ON_COMPLETE_CAPTURE_DESC', '');
define('AMZ_BUTTON_TYPE_PAY_DESC', 'Disponible uniquement en mode Connectez-vous et Payez');
define('AMZ_INVALID_SECRET', 'Votre cl&eacute; d&lsquo;acc&egrave;s est non valide');
define('AMZ_INVALID_MERCHANT_ID', 'Votre identifiant marchand Amazon est non valide');
define('AMZ_INVALID_ACCESS_KEY', 'Votre cl&eacute; d&lsquo;acc&egrave;s MWS est non valide');
define('AMZ_CREDENTIALS_SUCCESS', 'Vos identifiants sont valides');
define('AMZ_LOG_STATUS_TITLE', 'Journaliser ?');
define('AMZ_LOG_STATUS_DESC', 'Activer la connexion pour toutes les activit&eacute;s');
define('AMZ_CHECKOUT_SINGLE_PRODUCTS_TITLE', 'Passer la commande avec Amazon depuis la page d&eacute;taill&eacute;e du produit');
define('AMZ_CHECKOUT_SINGLE_PRODUCTS_DESC', '');
define('AMZ_SOMETHING_WRONG', 'Vos identifiants ne sont pas valides');

#3.0
define('AMZ_REDIRECT_URL_TITLE', 'Allowed Return URLs');
define('AMZ_STATUS_HARDDECLINE_TITLE', '&Eacute;tat de la commande pour les paiements refus&eacute;s');
define('AMZ_CHANGE_PAYMENT_SUCCESS', 'Votre mode de paiement a bien &eacute;t&eacute; modifi&eacute;');
define('AMZ_NO_ACCOUNT_YET', 'Vous n\'avez pas encore de compte vendeur ?');
define('AMZ_MY_CREDENTIALS', 'Mes données re&ccedil;ues');
define('AMZ_CREATE_ACCOUNT', 'Cr&eacute;er un compte vendeur');
define('AMZ_USE_MY_CREDENTIALS', 'Utiliser mes donn&eacute;es');
define('AMZ_NO_ACCOUNT_DESCRIPTION', '<b>Cr&eacute;ez votre compte vendeur Amazon dès maintenant en suivant ces 3 &eacute;tapes simples :</b><br/>
<ol>
    <li>Cliquez sur "'.AMZ_CREATE_ACCOUNT.'"</li>
    <li>Suivez les instructions d\'Amazon</li>
    <li>Saisissez les donn&eacute;es re&ccedil;ues ci-dessous dans "'.AMZ_MY_CREDENTIALS.'" et cliquez sur "'.AMZ_USE_MY_CREDENTIALS.'"</li>
</ol>
');
define('MODULE_PAYMENT_AM_APA_ALLOWED_PAYMENT_ZONES_TITLE', 'Zones autoris&eacute;es pour l\'adresse de facturation');
define('MODULE_PAYMENT_AM_APA_ALLOWED_PAYMENT_ZONES_DESC', 'Indiquez <b>chacune</b> des zones devant faire l\'objet d\'une autorisation pour l\'adresse de facturation. (par exemple : AT, DE [laissez le champ vide pour autoriser toutes les zones])');
define('PAYMENT_ZONE_NOT_ALLOWED', 'Le paiement n\'est pas autoris&eacute; depuis ce pays. S&eacute;lectionnez un autre mode de paiement');

define('AMZ_STATUS_CAPTUREDECLINE_TITLE', '&Eacute;tat de la commande pour une saisie de paiement &eacute;chou&eacute;e');
define('AMZ_SEND_MAILS_ON_CAPTUREDECLINE_TITLE', 'E-mail d\'informations à l\'administrateur en cas d\'&eacute;chec de la saisie de paiement');
define('AMZ_CAPTURE_DECLINED_EMAIL_SUBJECT', '&Eacute;chec de la saisie de paiement pour la commande  #%s');

# from /lang/.../amazon.php
define('AMZ_SINGLE_PRICE', 'Prix unitaire');
define('AMZ_TOTAL_PRICE', 'Prix total');
define('NO_POSITIONS','Non disponible.');
define('CANCEL','Annuler');
define('AMZ_TOTAL', 'Total');
define('ACCEPT','Acceptez nos conditions g&eacute;n&eacute;rales de vente et les conditions de r&eacute;tractation.');
define('NO_SHIPPING','Exp&eacute;dition impossible.');
define('NO_SHIPPING_TO_ADDRESS', 'Exp&eacute;dition &agrave; cette adresse impossible.');
define('FREE_SHIPPING_AT', 'Exp&eacute;dition gratuite &agrave; partir de');
define('SUCCESS','Votre num&eacute;ro de commande Amazon pour la commande pass&eacute;e dans notre boutique en ligne est le suivant :');
define('AMZ_WAITING', 'Patientez un instant pendant la redirection');
define('AMZ_WAITING_IMG', 'https://images-na.ssl-images-amazon.com/images/G/01/cba/images/global/Loading._V192259297_.gif');
define('AMZ_ZOLL', 'En cas de livraison hors de l&lsquo;Union Europ&eacute;enne, des droits de douane, des taxes ou des frais suppl&eacute;mentaires peuvent &ecirc;tre factur&eacute;s au client, non pas &agrave; destination du fournisseur mais des autorit&eacute;s financières et fiscales comp&eacute;tentes. Il est conseill&eacute; au client de demander des pr&eacute;cisions aux autorit&eacute;s financières et fiscales avant de passer une commande.');
define('AMZ_ADMIN_HINT', '* r&eacute;duit en raison de la r&eacute;partition des valeurs des r&eacute;ductions et des chèques-cadeaux encaiss&eacute;s pour les produits.');
define('AMZ_ADMIN_BTN', 'Exporter');
define('AMZ_VERSANDANTEIL', 'Frais d&lsquo;exp&eacute;dition');
define('AMZ_PRODUKT', 'Produit');
define('AMZ_SHOW_HIDE', 'afficher/masquer');
define('AMZ_REFUND_SUCCESS', 'Remboursement effectu&eacute;.');
define('AMZ_REFUND_ERROR', 'Remboursement non trait&eacute;. V&eacute;rifiez la validit&eacute; des montants.');
define('AMZ_DATE', 'Date');
define('AMZ_BETRAG', 'Montant');
define('AMZ_ACCEPT_REVOCATION', 'J&lsquo;accepte les conditions de r&eacute;tractation');
define('AGB_SHORT_TEXT', 'J&lsquo;ai pris connaissance des <a href="%s" onclick="amzPopupWindow(\'%s\'); return false;" target="_blank">conditions g&eacute;n&eacute;rales de vente</a> du fournisseur et donne mon accord pour l&lsquo;envoi de la commande.');
define('REVOCATION_SHORT_TEXT', ' J&lsquo;ai pris connaissance des <a href="%s" onclick="amzPopupWindow(\'%s\'); return false;" target="_blank">informations relatives aux conditions de r&eacute;tractation</a>.');
define('AMAZON_CHECKOUT', 'Amazon Pay'); 
define('AMZ_VIRTUAL_TEXT', 'Votre panier comprend exclusivement des articles virtuels.');
define('AMZ_USE_GV', 'Utiliser l&lsquo;avoir');
define('AMZ_AMOUNT_TOO_LOW_ERROR', 'Les commandes sans paiement ne peuvent malheureusement pas &ecirc;tre trait&eacute;es sur Amazon');
define('TEXT_CONTINUE_AS_GUEST', 'Continuer sans s\'identifier');
define('AMZ_JS_ORIGIN_TITLE', 'Allowed JavaScript Origins');

# 3.0.2
define('AMZ_SEND_MAILS_ON_AUTH_TITLE', 'Envoyer une notification par e-mail suite &agrave; l\'obtention d\'une autorisation');
define('AMZ_PAYMENT_AUTH_EMAIL_TITLE', 'Envoyer une notification d\'autorisation par e-mail');
define('TEXT_AMZ_AUTH_EMAIL_SUBJECT', 'Amazon Pay – autorisation obtenue');
define('TEXT_AMZ_PAYMENT_GENERAL_ERROR', 'Une erreur s\'est produite');
define('TEXT_AMZ_PAYMENT_METHOD_NOT_ALLOWED', 'S&eacute;lectionnez un autre mode de paiement');
define('AMZ_VAT_ID_INFO_TITLE', 'Afficher la remarque sur le num&eacute;ro d\'immatriculation &agrave; la TVA pour les clients de l\'UE');
define('AMZ_VAT_ID_INFO_DESC', 'La remarque indiquant qu\'un num&eacute;ro d\'immatriculation potentiellement existant est &agrave; renseigner sur le compte client doit-elle être affich&eacute;e pour les clients de l\'UE ?');
define('AMZ_VAT_ID_INFO_TEXT', '&Ecirc;tes-vous un client professionnel ? Veuillez renseigner votre num&eacute;ro d\'immatriculation &agrave; la TVA dans votre <a href="'.xtc_href_link('account_edit.php').'">compte client</a>.');

#3.1.0
define('AMZ_ORDER_REFERENCE_IN_COMMENT_TITLE', 'R&eacute;f&eacute;rence ID Amazon Pay dans le commentaire de la commande');
define('AMZ_ORDER_REFERENCE_IN_COMMENT_PREFIX', 'R&eacute;f&eacute;rence ID Amazon Pay');
define('AMZ_ORDER_STATUS_PARTIALLY_CAPTURED_TITLE', 'Statut de la commande : paiement partiel confirm&eacute;');
define('AMZ_ORDER_STATUS_CAPTURED_TITLE', 'Statut de la commande : paiement complet confirm&eacute;');
define('AMZ_ORDER_STATUS_SOFT_DECLINE_TITLE', 'Statut de la commande : paiement interrompu - action du client requise');
define('AMZ_CHECKOUT_SUCCESS_PENDING_AUTHORIZATION', 'Votre transaction avec Amazon Pay est en cours de validation. Vous serez inform&eacute; prochainement de son suivi.');

#3.1.1
define('AMZ_DELETE', 'Supprimer');
define('AMZ_INCREASE', 'Augmenter la quantit&eacute;');
define('AMZ_DECREASE', 'Diminuer la quantit&eacute;');
define('AMZ_PAY_EXISTING_ORDER_INTRO', 'Veuillez payer votre commande existante ici');
define('AMZ_PAY_EXISTING_ORDER_SUCCESS', 'Merci d&lsquo;avoir pay&eacute; avec Amazon Pay');
define('AMZ_PAY_EXISTING_ORDER_ERROR_WRONG_PAYMENT_METHOD', 'Cette commande ne peut pas &ecirc;tre pay&eacute;e avec Amazon Pay');
define('AMZ_PAY_EXISTING_ORDER_ERROR_ALREADY_PAID', 'Cette commande a d&eacute;j&agrave; &eacute;t&eacute; pay&eacute;e');
define('AMZ_SEND_PAY_MAIL', '&raquo; Envoyer les informations de paiement Amazon Pay par e-mail');
define('AMZ_SEND_PAY_MAIL_SUCCESS', 'Les informations de paiement Amazon Pay ont &eacute;t&eacute; envoy&eacute;es');
define('AMZ_PAY_EXISTING_ORDER_EMAIL_SUBJECT', 'Informations de paiement Amazon Pay de votre commande');

#3.2.0
define('AMZ_BUTTON_TOOLTIP', 'Le service Amazon Pay utilise les informations de paiement et de livraison enregistr&eacute;es sur votre compte Amazon');

#PSD2
define('AMZ_ERROR_MFA_ABANDONED', 'L\'authentification multi-facteur a &eacute;t&eacute; annul&eacute;e. Merci de faire une nouvelle tentative ou cliquez ici pour retourner sur le panier afin de s&eacute;lectionner une autre m&eacute;thode de paiement.');
define('AMZ_ERROR_MFA_FAILED', 'L\'authentification multi-facteur a &eacute;chou&eacute;. Merci de faire une nouvelle tentative ou de s&eacute;lectionner une autre m&eacute;thode de paiement.');
