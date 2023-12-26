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
DEFINE('BOX_CONFIGURATION_777', 'Opzioni Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_TEXT_TITLE', 'Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_STATUS_TITLE', 'Attivare modulo?');
DEFINE('MODULE_PAYMENT_AM_APA_STATUS_DESC', 'Selezionando l&lsquo;opzione corrispondente puoi attivare e disattivare Accedi e paga con Amazon.');
DEFINE('MODULE_PAYMENT_AM_APA_MERCHANTID_TITLE', 'ID venditore Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MERCHANTID_DESC', 'Il tuo ID venditore Amazon (Merchant ID)');
DEFINE('MODULE_PAYMENT_AM_APA_MARKETPLACEID_TITLE', 'ID marketplace Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MARKETPLACEID_DESC', 'Il tuo ID marketplace Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_ACCESKEY_TITLE', 'ID chiave d&lsquo;accesso MWS');
DEFINE('MODULE_PAYMENT_AM_APA_ACCESKEY_DESC', 'Serve per la comunicazione e l&lsquo;autenticazione con le Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_SECRETKEY_TITLE', 'Chiave segreta');
DEFINE('MODULE_PAYMENT_AM_APA_SECRETKEY_DESC', 'Serve per la comunicazione e l&lsquo;autenticazione con le Amazon Pay');
define('MODULE_PAYMENT_AM_APA_ALLOWED_TITLE', 'Zone consentite');
define('MODULE_PAYMENT_AM_APA_ALLOWED_DESC', 'Indica <b>una per una</b> le zone che devono essere consentite per questo modulo. (ad es. AT, DE (se vuoto sono consentite tutte le zone))');
DEFINE('MODULE_PAYMENT_AM_APA_MODE_TITLE', 'Modalit&agrave; operativa');
DEFINE('MODULE_PAYMENT_AM_APA_MODE_DESC', '');
DEFINE('MODULE_PAYMENT_AM_APA_PROVOCATION_TITLE', 'Provocare errori (solo Sandbox)');
DEFINE('MODULE_PAYMENT_AM_APA_PROVOCATION_DESC', 'Serve per consentire all&lsquo;amministratore di simulare il rifiuto di pagamenti o clienti');
DEFINE('MODULE_PAYMENT_AM_APA_ORDER_STATUS_OK_TITLE', 'Stato dell&lsquo;ordine per i pagamenti autorizzati');
DEFINE('MODULE_PAYMENT_AM_APA_ORDER_STATUS_OK_DESC', 'Questo stato viene impostato dopo che Amazon ha autorizzato il pagamento.');
DEFINE('MODULE_PAYMENT_AM_APA_ALLOW_GUESTS_TITLE','Consentire gli ordini di ospiti?');
DEFINE('MODULE_PAYMENT_AM_APA_ALLOW_GUESTS_DESC','Se attivata, i clienti possono effettuare pagamenti tramite le Amazon Pay anche se non hanno creato un account nel tuo negozio.');
define('AMZ_YES', 'Sì');
define('AMZ_NO', 'No');
define('AMZ_SANDBOX', 'Funzionamento di prova');
define('AMZ_LIVE', 'Funzionamento live');
define('AMZ_AUTHORIZATION_CONFIG_TITLE', 'Quando/come deve essere autorizzato il pagamento?');
define('AMZ_AUTHORIZATION_CONFIG_DESC', 'L&lsquo;autorizzazione immediata comporta il rischio, seppur ridotto, di essere rifiutata. È quindi consigliata, ad esempio, quando acquisti prodotti da scaricare e hai bisogno di un&lsquo;autorizzazione immediata del pagamento o quando desideri fornire al cliente una conferma immediata del buon esito dell&lsquo;autorizzazione.<br/><br />A seguito dell&lsquo;autorizzazione hai fino a 30 giorni di tempo per inviare i prodotti e riscattare il pagamento. Nei primi sette giorni Amazon garantisce la buona riuscita del versamento.');
define('AMZ_CAPTURE_CONFIG_TITLE', 'Quando/come deve essere incassato il pagamento?');
define('AMZ_CAPTURE_CONFIG_DESC', '');
define('AMZ_FAST_AUTH', 'durante il checkout/prima della chiusura dell&lsquo;ordine');
define('AMZ_AUTH_AFTER_CHECKOUT', 'subito dopo l&lsquo;ordine');
define('AMZ_CAPTURE_AFTER_AUTH', 'subito dopo l&lsquo;autorizzazione automatica con esito positivo');
define('AMZ_AFTER_SHIPPING', 'dopo l&lsquo;invio');
define('AMZ_MANUALLY', 'manuale');
define('AMZ_SHIPPED_STATUS_TITLE', 'Stato dell&lsquo;ordine per gli ordini inviati');
define('AMZ_SHIPPED_STATUS_DESC', 'Importante per l&lsquo;incasso del pagamento dopo l&lsquo;invio');
define('AMZ_REVOCATION_ID_TITLE', 'ID contenuto per le istruzioni di recesso');
define('AMZ_AGB_ID_TITLE', 'ID contenuto per le condizioni generali di contratto');
define('AMZ_BUTTON_SIZE_TITLE', 'Dimensione pulsante checkout Amazon');
define('AMZ_BUTTON_COLOR_TITLE', 'Colore pulsante checkout Amazon');
define('AMZ_BUTTON_COLOR_ORANGE', 'Giallo Amazon');
define('AMZ_BUTTON_COLOR_TAN', 'Grigio');
define('AMZ_BUTTON_SIZE_MEDIUM', 'normale');
define('AMZ_BUTTON_SIZE_LARGE', 'grande');
define('AMZ_BUTTON_SIZE_XLARGE', 'molto grande');
define('AMZ_TX_TYPE_HEADING', 'Tipo di transazione');
define('AMZ_TX_TIME_HEADING', 'Ora');
define('AMZ_TX_STATUS_HEADING', 'Stato');
define('AMZ_TX_LAST_CHANGE_HEADING', 'Ultima modifica');
define('AMZ_TX_ID_HEADING', 'ID transazione Amazon');
define('AMZ_AUTH_TEXT', 'Autorizzazione');
define('AMZ_ORDER_TEXT', 'Ordine');
define('AMZ_CAPTURE_TEXT', 'Incasso');
define('AMZ_REFUND_TEXT', 'Rimborso');
define('AMZ_TX_AMOUNT_HEADING', 'Importo');
define('AMZ_IPN_URL_TITLE', 'URL per l&lsquo;IPN Amazon');
define('AMZ_TX_EXPIRATION_HEADING', 'Validit&agrave; fino al');
define('AMZ_HISTORY', 'Cronologia Amazon Pay');
define('AMZ_ORDER_AUTH_TOTAL', 'Importo autorizzato');
define('AMZ_ORDER_CAPTURE_TOTAL', 'Importo incassato');
define('AMZ_SUMMARY', 'Riepilogo Amazon');
define('AMZ_ACTIONS', 'Azioni Amazon');
define('AMZ_CAPTURE_FROM_AUTH_HEADING', 'Incassa pagamenti autorizzati');
define('AMZ_TX_ACTION_HEADING', 'Azioni');
define('AMZ_CAPTURE_TOTAL_FROM_AUTH', 'Incassa intero importo');
define('AMZ_AUTHORIZE', 'Autorizza pagamento');
define('AMZ_AUTHORIZATION_SUCCESSFULLY_REQUESTED', 'La richiesta di autorizzazione &egrave; stata inviata correttamente');
define('AMZ_AUTHORIZATION_REQUEST_FAILED', 'Richiesta di autorizzazione non riuscita');
define('AMZ_CAPTURE_SUCCESS', 'Incasso riuscito');
define('AMZ_CAPTURE_FAILED', 'Incasso non riuscito');
define('AMZ_AMOUNT_LEFT_TO_OVER_AUTHORIZE', 'Autorizzazione aggiuntiva');
define('AMZ_AMOUNT_LEFT_TO_AUTHORIZE', 'Autorizzazione standard');
define('AMZ_TYPE', 'Tipo');
define('AMZ_REFUNDS', 'Rimborsi');
define('AMZ_REFUND', 'Rimborsa');
define('AMZ_REFUND_SUCCESS', 'Rimborso inoltrato');
define('AMZ_REFUND_FAILED', 'Rimborso non riuscito');
define('AMZ_REFRESH', 'Aggiorna');
define('AMZ_CAPTURE_AMOUNT_FROM_AUTH', 'Incassa importo parziale');
define('AMZ_REFUND_TOTAL', 'Rimborsa per intero');
define('AMZ_REFUND_AMOUNT', 'Rimborsa parzialmente');
define('AMZ_TX_AMOUNT_REFUNDED_HEADING', 'Rimborsato');
define('AMZ_TX_SUM', 'Somma');
define('AMZ_TX_AMOUNT_REFUNDABLE_HEADING', 'Ancora possibile');
define('AMZ_TX_AMOUNT_POSSIBLE_HEADING', 'Massimo possibile');
define('AMZ_AUTHORIZE_AMOUNT', 'Autorizza importo parziale');
define('AMZ_TX_AMOUNT_NOT_AUTHORIZED_YET_HEADING', 'Importo non ancora autorizzato');
define('AMZ_REFUND_OVER_AMOUNT', 'Rimborsa importo superiore');
define('AMZ_FINISHED_REFRESHING_ORDER', 'Ordine aggiornato');
define('AMZ_OVER_AUTHORIZE_AMOUNT', 'Autorizza importo superiore');
define('MODULE_PAYMENT_AM_APA_IPN_STATUS_TITLE', 'Ricevi aggiornamenti dello stato tramite IPN');
define('AMZ_CRON_URL_TITLE', 'URL per Cronjob');
define('MODULE_PAYMENT_AM_APA_CRON_STATUS_TITLE', 'Attiva Cronjob per gli aggiornamenti dello stato');
define('AMZ_AGB_DISPLAY_MODE_TITLE', 'Visualizzazione delle condizioni generali di contratto e di recesso');
define('AMZ_AGB_DISPLAY_MODE_SHORT', 'Solo notifica');
define('AMZ_AGB_DISPLAY_MODE_LONG', 'Rappresentazione completa con casella di selezione');
define('AMZ_CRON_PW_TITLE', 'Password per Cronjob');
define('AMZ_SOFT_DECLINE_SUBJECT_TITLE', 'Oggetto e-mail pagamento rifiutato');
define('AMZ_PAYMENT_NAME_TITLE', 'Nome mittente e-mail');
define('AMZ_PAYMENT_EMAIL_TITLE', 'Indirizzo mittente e-mail');
define('AMZ_HARD_DECLINE_SUBJECT_TITLE', 'Oggetto e-mail ordine rifiutato');
define('AMZ_SEND_MAILS_ON_DECLINE_TITLE', 'Informa automaticamente i clienti quando i pagamenti vengono rifiutati');
define('AMZ_FASTAUTH_SOFT_DECLINED', 'Scegli un&lsquo;altra modalit&agrave; di pagamento');
define('AMZ_FASTAUTH_HARD_DECLINED', 'Il tuo pagamento &egrave; stato rifiutato da Amazon Pay, utilizza un&lsquo;altra modalit&agrave; di pagamento');
define('AMZ_UNKNOWN_ERROR', 'Non &egrave; stato possibile completare l&lsquo;ordine. Riprova con un&lsquo;altra modalit&agrave; di pagamento.a');
define('AMZ_CANCEL_ORDER', 'Annulla la procedura di pagamento Amazon');
define('AMZ_CLOSE_ORDER', 'Chiudi ordine');
define('AMZ_ORDER_CANCELLED', 'Procedura di pagamento annullata');
define('AMZ_ORDER_CLOSED', 'Ordine chiuso');
define('AMZ_SHOW_ON_CHECKOUT_PAYMENT_TITLE', 'Visualizza il pulsante Amazon nel checkout normale');
define('AMZ_DEBUG_MODE_TITLE', 'Modalit&agrave; di debug');
define('AMZ_DEBUG_MODE_DESC', 'Nella modalit&agrave; di debug il pagamento tramite Amazon Pay &egrave; visibile solo agli amministratori');
define('AMZ_EXCLUDED_SHIPPING_TITLE', 'Escludi modalit&agrave; di spedizione');
define('AMZ_NEW_VERSION_AVAILABLE', 'È disponibile una nuova versione di questo modulo');
define('AMZ_VERSION_IS_GOOD', 'La tua versione del modulo &egrave; aggiornata');
define('AMZ_EXCLUDE_PRODUCTS', 'Escludi prodotti');
define('AMZ_SEARCH', 'Cerca');
define('AMZ_EXCLUDED_PRODUCTS', 'Prodotti esclusi');
define('AMZ_SEARCH_RESULT', 'Risultati della ricerca');
define('AMZ_EXCLUDED_PRODUCTS_TITLE', 'Prodotti esclusi dal pagamento tramite Amazon');
define('AMZ_EXCLUDED_PRODUCTS_OPEN', ' Apri');
define('AMZ_ALL_MANUFACTURERS', 'Tutti i produttori');
define('AMZ_INCLUDE_ALL_PRODUCTS', 'Rimuovi tutti');
define('AMZ_EXCLUDE_ALL_PRODUCTS', 'Escludi tutti');
define('AMZ_TEMPLATE_1', 'Modello 1');
define('AMZ_TEMPLATE_2', 'Modello 2');
define('AMZ_TEMPLATE_TITLE', 'Selezione del modello');
define('AMZ_CANCEL_ORDER_FROM_WALLET', 'Interrompi pagamento tramite Amazon Pay');
define('AMZ_WALLET_INTRO', 'La modalit&agrave; di pagamento selezionata non &egrave; al momento disponibile. Scegline un&lsquo;altra.');
define('AMZ_DOWNLOAD_ONLY_TITLE', 'In questo negozio sono venduti solo articoli virtuali');
define('AMZ_DOWNLOAD_ONLY_DESC', 'Se Sì, per la scelta dell&lsquo;autorizzazione al momento del pagamento non viene richiesto alcun indirizzo di spedizione');
define('AMZ_HEADING_AMAZON_PAYMENTS_ACCOUNT', 'Conto Amazon Pay');
define('AMZ_HEADING_GENERAL_SETTINGS', 'Impostazioni generali');
define('AMZ_HEADING_DESIGN_SETTINGS', 'Impostazioni design');
define('AMZ_HEADING_IPN_SETTINGS', 'Impostazioni IPN');
define('AMZ_HEADING_CRONJOB_SETTINGS', 'Impostazioni Cronjob');
define('AMZ_HEADING_MAIL_SETTINGS', 'Opzioni e-mail');

# Update V2.01
define('AMZ_DB_UPDATE_WARNING', 'Hai aggiornato il modulo. Hai aggiornato il database.');
define('AMZ_DB_UPDATE_BUTTON_TEXT', 'Aggiorna database');
define('AMZ_IPN_PW_TITLE', 'Password IPN');
define('AMZ_SET_SELLER_ORDER_ID_TITLE', 'Invia numero ordine ad Amazon');
define('AMZ_SET_SELLER_ORDER_ID_DESC', 'Attenzione: alcuni numeri ordine possono mancare a causa di ordini annullati.');
define('AMZ_ORDER_REF_HEADING', 'Riferimento ordine Amazon');
define('AMZ_ORDERS_ID_HEADING', 'Ordine di acquisto');
define('AMZ_TRANSACTION_HISTORY', 'Protocollo transazione Amazon');
define('AMZ_BACK', 'Indietro');
define('AMZ_MERCHANT_ID_INVALID', 'Nessuna azione possibile. Il tuo ID venditore Amazon non corrisponde');
define('AMZ_STATUS_NONAUTHORIZED_TITLE', 'Stato degli ordini non autorizzati');

# Update V2.10
define('MODULE_PAYMENT_AM_APA_LPA_MODE_TITLE', 'Modalit&agrave; Accedi e paga');
define('MODULE_PAYMENT_AM_APA_LPA_MODE_DESC', '');
define('MODULE_PAYMENT_AM_APA_CLIENTID_TITLE', 'ID cliente per Accedi e paga');
define('MODULE_PAYMENT_AM_APA_CLIENTID_DESC', 'Viene utilizzato per comunicazioni e autenticazioni con Accedi e paga con Amazon');
define('AMZ_SET_ADDRESS_TITLE', 'Grazie per aver effettuato l&lsquo;accesso ad Amazon Pay');
define('AMZ_SET_ADDRESS_INTRO', 'Per continuare, &egrave; necessario un indirizzo standard. Durante il checkout potrai sempre scegliere un indirizzo di spedizione.');
define('AMZ_CONNECT_ACCOUNTS_TITLE', 'Grazie per aver effettuato l&lsquo;accesso ad Amazon Pay');
define('AMZ_CONNECT_ACCOUNTS_INTRO', '&Egrave; stato rilevato un account associato al tuo indirizzo e-mail nel nostro negozio. Inserisci la password per questo negozio per associare il conto al tuo account Amazon.');
define('AMZ_PASSWORD', 'La tua password');
define('AMZ_CONNECT_ACCOUNTS', 'Associa account');
define('AMZ_CONNECT_ACCOUNTS_ERROR', 'Errore: password errata');
define('MODULE_PAYMENT_AM_APA_POPUP_TITLE', 'Se possibile, accedi ad Amazon con popup');
define('MODULE_PAYMENT_AM_APA_POPUP_DESC', 'Mostrare l&lsquo;accesso ad Amazon in una finestra popup? In alternativa, il cliente viene reindirizzato ad Amazon e torna al tuo negozio una volta effettuato l&lsquo;accesso. L&lsquo;accesso tramite popup &egrave; supportato solo se l&lsquo;intero negozio &egrave; protetto con SSL.');
define('AMZ_LOGIN_PROCESSING_TITLE', 'Grazie per aver effettuato l&lsquo;accesso ad Amazon Pay');
define('AMZ_LOGIN_PROCESSING_INTRO', 'Verrai reindirizzato tra pochi secondi...');
define('AMZ_BUTTON_SIZE_TITLE', 'Dimensione pulsante checkout Amazon (modalit&agrave; pagamento)');
define('AMZ_BUTTON_SIZE_TITLE_LPA', 'Dimensione pulsante checkout Amazon (modalit&agrave; accesso/accedi e paga)');
define('AMZ_BUTTON_COLOR_TITLE', 'Colore pulsante checkout Amazon (modalit&agrave; pagamento)');
define('AMZ_BUTTON_COLOR_TITLE_LPA', 'Colore pulsante checkout Amazon (modalit&agrave; accesso/accedi e paga)');
define('AMZ_BUTTON_COLOR_TAN_LIGHT', 'Grigio chiaro');
define('AMZ_BUTTON_COLOR_TAN_DARK', 'Grigio scuro');
define('AMZ_BUTTON_SIZE_SMALL', 'Piccolo');
define('AMZ_BUTTON_TYPE_LOGIN_TITLE', 'Tipo pulsante accesso');
define('AMZ_BUTTON_TYPE_PAY_TITLE', 'Tipo pulsante pagamento');
define('AMZ_BUTTON_TYPE_LOGIN_LWA', 'Accedi con Amazon');
define('AMZ_BUTTON_TYPE_LOGIN_LOGIN', 'Accedi');
define('AMZ_BUTTON_TYPE_LOGIN_A', 'Solo -A-');
define('AMZ_BUTTON_TYPE_PAY_PWA', 'Paga con Amazon');
define('AMZ_BUTTON_TYPE_PAY_PAY', 'Paga');
define('AMZ_BUTTON_TYPE_PAY_A', 'Solo -A-');
define('AMZ_SAVE', 'Salva');
define('AMZ_CONFIGURATION', 'Configurazione');
define('AMZ_PROTOCOL', 'Protocollo transazione');
define('AMZ_CLOSE_ORDER_ON_COMPLETE_CAPTURE_TITLE', 'Contrassegna ordini come Chiusi una volta addebitato l&lsquo;importo complessivo');
define('AMZ_CLOSE_ORDER_ON_COMPLETE_CAPTURE_DESC', '');
define('AMZ_BUTTON_TYPE_PAY_DESC', 'Disponibile solo in modalit&agrave; -Accedi e paga-');
define('AMZ_INVALID_SECRET', 'Chiave segreta non valida');
define('AMZ_INVALID_MERCHANT_ID', 'ID venditore non valido');
define('AMZ_INVALID_ACCESS_KEY', 'Chiave d&lsquo;accesso MWS non valida');
define('AMZ_CREDENTIALS_SUCCESS', 'Credenziali valide');
define('AMZ_LOG_STATUS_TITLE', 'Scrivere registro?');
define('AMZ_LOG_STATUS_DESC', 'Attiva registrazione per tutte le attivit&agrave;');
define('AMZ_CHECKOUT_SINGLE_PRODUCTS_TITLE', 'Checkout con Amazon dalla pagina prodotto');
define('AMZ_CHECKOUT_SINGLE_PRODUCTS_DESC', '');
define('AMZ_SOMETHING_WRONG', 'Credenziali non valide');

#3.0
define('AMZ_REDIRECT_URL_TITLE', 'Allowed Return URLs');
define('AMZ_STATUS_HARDDECLINE_TITLE', 'Stato ordine per pagamenti rifiutati');
define('AMZ_CHANGE_PAYMENT_SUCCESS', 'Il metodo di pagamento &egrave; stato modificato');
define('AMZ_NO_ACCOUNT_YET', 'Non possiedi un Account venditore?');
define('AMZ_MY_CREDENTIALS', 'Dati ricevuti');
define('AMZ_CREATE_ACCOUNT', 'Registra Account venditore');
define('AMZ_USE_MY_CREDENTIALS', 'Usa i miei dati');
define('AMZ_NO_ACCOUNT_DESCRIPTION', '<b>Crea subito un Account venditore in tre semplici passaggi:</b><br/>
<ol>
    <li>Clicca su "'.AMZ_CREATE_ACCOUNT.'"</li>
    <li>Segui le istruzioni di Amazon</li>
    <li>Inserisci i dati ricevuti sotto "'.AMZ_MY_CREDENTIALS.'" e clicca su "'.AMZ_USE_MY_CREDENTIALS.'"</li>
</ol>
');
define('MODULE_PAYMENT_AM_APA_ALLOWED_PAYMENT_ZONES_TITLE', 'Zone consentite per l\'indirizzo di fatturazione');
define('MODULE_PAYMENT_AM_APA_ALLOWED_PAYMENT_ZONES_DESC', 'Indicare <b>una ad una</b> le zone consentite per l\'indirizzo di fatturazione (ad es. AT, DE (se vuoto sono consentite tutte le zone))');
define('PAYMENT_ZONE_NOT_ALLOWED', 'Il pagamento non &egrave; consentito da questo Paese: selezionare un altro mezzo di pagamento');

define('AMZ_STATUS_CAPTUREDECLINE_TITLE', 'Stato dell\'ordine per pagamento non incassato');
define('AMZ_SEND_MAILS_ON_CAPTUREDECLINE_TITLE', 'Mail informativa all\'amministratore in caso di pagamento non incassato');
define('AMZ_CAPTURE_DECLINED_EMAIL_SUBJECT', 'Pagamento Amazon non incassato per l\'ordine n.%s');

# from /lang/.../amazon.php
define('AMZ_SINGLE_PRICE', 'Prezzo unitario');
define('AMZ_TOTAL_PRICE', 'Prezzo totale');
define('NO_POSITIONS','Non disponibile.');
define('CANCEL','Annulla');
define('AMZ_TOTAL', 'Totale');
define('ACCEPT','Ti preghiamo di accettare le nostre condizioni generali di contratto e di recesso.');
define('NO_SHIPPING','Impossibile effettuare la spedizione!');
define('NO_SHIPPING_TO_ADDRESS', 'Impossibile effettuare la spedizione a questo indirizzo.');
define('FREE_SHIPPING_AT', 'Spedizione gratuita da');
define('SUCCESS','Il tuo numero Amazon per questo ordine sul nostro negozio online &egrave; il seguente:');
define('AMZ_WAITING', 'Reindirizzamento in corso, attendi');
define('AMZ_WAITING_IMG', 'https://images-na.ssl-images-amazon.com/images/G/01/cba/images/global/Loading._V192259297_.gif');
define('AMZ_ZOLL', 'Per le spedizioni in paesi al di fuori dell&lsquo;Unione europea, possono essere applicati altri dazi doganali o imposte a carico del cliente, che tuttavia non andranno a favore del venditore ma saranno versati alle autorit&agrave; fiscali o doganali locali.UEConsigliamo ai clienti di chiedere informazioni dettagliate alle autorit&agrave; fiscali o doganali prima di effettuare l&lsquo;ordine.');
define('AMZ_ADMIN_HINT', '* &egrave; stato ridotto, poiché il valore degli sconti o dei buoni utilizzati &egrave; stato ripartito tra i prodotti.');
define('AMZ_ADMIN_BTN', 'Compila');
define('AMZ_VERSANDANTEIL', 'Quota di spedizione');
define('AMZ_PRODUKT', 'Prodotto');
define('AMZ_SHOW_HIDE', 'mostra/nascondi');
define('AMZ_REFUND_SUCCESS', 'Rimborso emesso correttamente.');
define('AMZ_REFUND_ERROR', 'Rimborso non elaborato. Verifica la correttezza degli importi.');
define('AMZ_DATE', 'Data');
define('AMZ_BETRAG', 'Importo');
define('AMZ_ACCEPT_REVOCATION', 'Accetto le condizioni di recesso');
define('AGB_SHORT_TEXT', 'Ho letto le <a href="%s" onclick="amzPopupWindow(\'%s\'); return false;" target="_blank">Condizioni generali di contratto</a> del venditore e con l&lsquo;invio del mio ordine dichiaro il mio consenso.');
define('REVOCATION_SHORT_TEXT', ' Ho preso visione delle <a href="%s" onclick="amzPopupWindow(\'%s\'); return false;" target="_blank">Istruzioni per il recesso</a>.');
define('AMAZON_CHECKOUT', 'Amazon Pay'); 
define('AMZ_VIRTUAL_TEXT', 'Hai solamente articoli virtuali nel carrello.');
define('AMZ_USE_GV', 'Utilizza buono');
define('AMZ_AMOUNT_TOO_LOW_ERROR', 'Gli ordini senza pagamento non possono essere elaborati su Amazon.');
define('TEXT_CONTINUE_AS_GUEST', 'Proseguire senza login');
define('AMZ_JS_ORIGIN_TITLE', 'Allowed JavaScript Origins');

# 3.0.2
define('AMZ_SEND_MAILS_ON_AUTH_TITLE', 'Invia e-mail di notifica per autorizzazioni completate');
define('AMZ_PAYMENT_AUTH_EMAIL_TITLE', 'Invia e-mail di notifica per autorizzazioni');
define('TEXT_AMZ_AUTH_EMAIL_SUBJECT', 'Amazon Pay – Autorizzazione completata');
define('TEXT_AMZ_PAYMENT_GENERAL_ERROR', 'Si &egrave; verificato un errore');
define('TEXT_AMZ_PAYMENT_METHOD_NOT_ALLOWED', 'Seleziona un altro metodo di pagamento');
define('AMZ_VAT_ID_INFO_TITLE', 'Indicazione del numero di partita IVA per i clienti UE');
define('AMZ_VAT_ID_INFO_DESC', 'Per i clienti UE va specificato che occorre indicare un eventuale numero di partita IVA nel loro conto?');
define('AMZ_VAT_ID_INFO_TEXT', 'Sei un cliente commerciale? In caso affermativo, indica il tuo numero di partita IVA nel tuo <a href="'.xtc_href_link('account_edit.php').'">conto</a>.');

#3.1.0
define('AMZ_ORDER_REFERENCE_IN_COMMENT_TITLE', 'ID di riferimento Amazon Pay nel commento all\'ordine');
define('AMZ_ORDER_REFERENCE_IN_COMMENT_PREFIX', 'ID di riferimento Amazon Pay');
define('AMZ_ORDER_STATUS_PARTIALLY_CAPTURED_TITLE', 'Stato dell\'ordine per: pagamento parziale confermato');
define('AMZ_ORDER_STATUS_CAPTURED_TITLE', 'Stato dell\'ordine per: pagamento totale confermato');
define('AMZ_ORDER_STATUS_SOFT_DECLINE_TITLE', 'Stato dell\'ordine per: pagamento interrotto, richiesta azione da parte dell\'utente');
define('AMZ_CHECKOUT_SUCCESS_PENDING_AUTHORIZATION', 'La vostra transazione con Amazon Pay &egrave; in fase di validazione. Vi informeremo del risultato della transazione a breve.');

#3.1.1
define('AMZ_DELETE', 'Cancella');
define('AMZ_INCREASE', 'Aumenta quantit&agrave;');
define('AMZ_DECREASE', 'Diminuisci quantit&agrave;');
define('AMZ_PAY_EXISTING_ORDER_INTRO', 'Per favore paga qui gli ordini esistenti');
define('AMZ_PAY_EXISTING_ORDER_SUCCESS', 'Grazie per aver utilizzato Amazon Pay');
define('AMZ_PAY_EXISTING_ORDER_ERROR_WRONG_PAYMENT_METHOD', 'Quest&lsquo;ordine non pu&ograve; essere pagato con Amazon Pay');
define('AMZ_PAY_EXISTING_ORDER_ERROR_ALREADY_PAID', 'Quest&lsquo;ordine &egrave; gi&agrave; stato pagato');
define('AMZ_SEND_PAY_MAIL', '&raquo; Invia le informazioni di pagamento Amazon Pay via e-mail');
define('AMZ_SEND_PAY_MAIL_SUCCESS', 'Le informazioni di pagamento Amazon Pay sono state inviate');
define('AMZ_PAY_EXISTING_ORDER_EMAIL_SUBJECT', 'Informazioni di pagamento Amazon Pay per il tuo ordine');

#3.2.0
define('AMZ_BUTTON_TOOLTIP', 'Amazon Pay utilizza le informazioni di pagamento e consegna memorizzate nel tuo account Amazon');

#PSD2
define('AMZ_ERROR_MFA_ABANDONED', 'L\'autenticazione multifattoriale &egrave; stata annullata. Prova di nuovo o clicca qui per tornare al carrello e selezionare un altro metodo di pagamento.');
define('AMZ_ERROR_MFA_FAILED', 'L\'autenticazione multifattoriale non &egrave; riuscita. Prova di nuovo o seleziona un altro metodo di pagamento.');
