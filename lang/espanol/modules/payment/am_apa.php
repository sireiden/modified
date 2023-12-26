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
DEFINE ('BOX_CONFIGURATION_777', 'Opciones de las Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_TEXT_TITLE', 'Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_STATUS_TITLE', '&iquest;Activar el m&oacute;dulo?');
DEFINE('MODULE_PAYMENT_AM_APA_STATUS_DESC', 'Seleccionan loas opciones pertinentes, puedes activar y desactivar el inicio de sesi&oacute;n y el pago a trav&eacute;s de Amazon.');
DEFINE('MODULE_PAYMENT_AM_APA_MERCHANTID_TITLE', 'ID de vendedor de Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MERCHANTID_DESC', 'Tu ID de vendedor de Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MARKETPLACEID_TITLE', 'ID de mercado de Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_MARKETPLACEID_DESC', 'Tu ID de mercado de Amazon');
DEFINE('MODULE_PAYMENT_AM_APA_ACCESKEY_TITLE', 'ID de clave de acceso de MWS');
DEFINE('MODULE_PAYMENT_AM_APA_ACCESKEY_DESC', 'Se usa para la comunicaci&oacute;n y autenticaci&oacute;n con las Amazon Pay');
DEFINE('MODULE_PAYMENT_AM_APA_SECRETKEY_TITLE', 'Clave secreta');
DEFINE('MODULE_PAYMENT_AM_APA_SECRETKEY_DESC', 'Se usa para la comunicaci&oacute;n y autenticaci&oacute;n con las Amazon Pay');
define('MODULE_PAYMENT_AM_APA_ALLOWED_TITLE', 'Zonas permitidas');
define('MODULE_PAYMENT_AM_APA_ALLOWED_DESC', 'Introduce <b>&uacute;nicamente</b> las zonas en las que se permite el uso de este m&oacute;dulo. (por ejemplo, AT, DE; si se deja en blanco, se permiten todas las zonas)');
DEFINE('MODULE_PAYMENT_AM_APA_MODE_TITLE', 'Modo');
DEFINE('MODULE_PAYMENT_AM_APA_MODE_DESC', '');
DEFINE('MODULE_PAYMENT_AM_APA_PROVOCATION_TITLE', 'Provocar errores (solo Sandbox)');
DEFINE('MODULE_PAYMENT_AM_APA_PROVOCATION_DESC', 'Se usa para que el administrador pueda simular rechazos de pagos o clientes.');
DEFINE('MODULE_PAYMENT_AM_APA_ORDER_STATUS_OK_TITLE', 'Estado para pagos autorizados');
DEFINE('MODULE_PAYMENT_AM_APA_ORDER_STATUS_OK_DESC', 'Este estado se establece despu&eacute;s de que Amazon haya autorizado el pago.');
DEFINE('MODULE_PAYMENT_AM_APA_ALLOW_GUESTS_TITLE','&iquest;Permitir pedidos de invitados?');
DEFINE('MODULE_PAYMENT_AM_APA_ALLOW_GUESTS_DESC','Cuando se activa esta opci&oacute;n los clientes pueden pagar tambi&eacute;n mediante las Amazon Pay si has creado una cuenta de cliente en tu tienda.');
define('AMZ_YES', 'Sí');
define('AMZ_NO', 'No');
define('AMZ_SANDBOX', 'Sandbox');
define('AMZ_LIVE', 'En directo');
define('AMZ_AUTHORIZATION_CONFIG_TITLE', '&iquest;Cu&aacute;ndo/c&oacute;mo deben autorizarse los pagos?');
define('AMZ_AUTHORIZATION_CONFIG_DESC', 'La autorizaci&oacute;n inmediata tiene una probabilidad ligeramente mayor de ser rechazada. Por eso se recomienda solo si, por ejemplo, vendes productos para descargar que por tanto requieren una confirmaci&oacute;n de pago inmediata, o si quieres informar al cliente inmediatamente sobre el &eacute;xito de la autorizaci&oacute;n.<br/><br />Despu&eacute;s de la autorizaci&oacute;n tienes hasta 30 días de plazo para gestionar el envío de las mercanc&iacute;as y con ello autorizar el pago. Amazon garantiza el pago en los primeros siete d&iacute;as.');
define('AMZ_CAPTURE_CONFIG_TITLE', '&iquest;Cu&aacute;ndo/c&oacute;mo deben cobrarse los pagos?');
define('AMZ_CAPTURE_CONFIG_DESC', '');
define('AMZ_FAST_AUTH', 'durante el proceso de pago/antes de finalizar el pedido');
define('AMZ_AUTH_AFTER_CHECKOUT', 'directamente despu&eacute;s del pedido');
define('AMZ_CAPTURE_AFTER_AUTH', 'despu&eacute;s de que la autorizaci&oacute;n autom&aacute;tica tenga &eacute;xito');
define('AMZ_AFTER_SHIPPING', 'despu&eacute;s del env&iacute;o');
define('AMZ_MANUALLY', 'manualmente');
define('AMZ_SHIPPED_STATUS_TITLE', 'Estado de los pedidos enviados');
define('AMZ_SHIPPED_STATUS_DESC', 'Importante para el cobro despu&eacute;s del env&iacute;o');
define('AMZ_REVOCATION_ID_TITLE', 'ID de contenido de orden de cancelaci&oacute;n');
define('AMZ_AGB_ID_TITLE', 'ID de contenido de las condiciones');
define('AMZ_BUTTON_SIZE_TITLE', 'Tama&ntilde;o del bot&oacute;n de pago de Amazon');
define('AMZ_BUTTON_COLOR_TITLE', 'Color del bot&oacute;n de pago de Amazon');
define('AMZ_BUTTON_COLOR_ORANGE', 'Amarillo Amazon');
define('AMZ_BUTTON_COLOR_TAN', 'Gris');
define('AMZ_BUTTON_SIZE_MEDIUM', 'normal');
define('AMZ_BUTTON_SIZE_LARGE', 'grande');
define('AMZ_BUTTON_SIZE_XLARGE', 'muy grande');
define('AMZ_TX_TYPE_HEADING', 'Tipo de transacci&oacute;n');
define('AMZ_TX_TIME_HEADING', 'Hora');
define('AMZ_TX_STATUS_HEADING', 'Estado');
define('AMZ_TX_LAST_CHANGE_HEADING', '&Uacute;ltimo cambio');
define('AMZ_TX_ID_HEADING', 'ID de transacciones de Amazon');
define('AMZ_AUTH_TEXT', 'Autorizaci&oacute;n');
define('AMZ_ORDER_TEXT', 'Pedido');
define('AMZ_CAPTURE_TEXT', 'Entrada');
define('AMZ_REFUND_TEXT', 'Reembolso');
define('AMZ_TX_AMOUNT_HEADING', 'Importe');
define('AMZ_IPN_URL_TITLE', 'URL para Amazon IPN');
define('AMZ_TX_EXPIRATION_HEADING', 'V&aacute;lido hasta');
define('AMZ_HISTORY', 'Historial de Amazon Pay');
define('AMZ_ORDER_AUTH_TOTAL', 'Importe autorizado');
define('AMZ_ORDER_CAPTURE_TOTAL', 'Importe cobrado');
define('AMZ_SUMMARY', 'Resumen de Amazon');
define('AMZ_ACTIONS', 'Acciones de Amazon');
define('AMZ_CAPTURE_FROM_AUTH_HEADING', 'Cobrar pagos autorizados');
define('AMZ_TX_ACTION_HEADING', 'Acciones');
define('AMZ_CAPTURE_TOTAL_FROM_AUTH', 'Cobrar todos los pagos pendientes');
define('AMZ_AUTHORIZE', 'Pagos autorizados');
define('AMZ_AUTHORIZATION_SUCCESSFULLY_REQUESTED', 'Solicitud de autorizaci&oacute;n emitida');
define('AMZ_AUTHORIZATION_REQUEST_FAILED', 'Error en la solicitud de autorizaci&oacute;n');
define('AMZ_CAPTURE_SUCCESS', 'Pago cobrado');
define('AMZ_CAPTURE_FAILED', 'Error de cobro');
define('AMZ_AMOUNT_LEFT_TO_OVER_AUTHORIZE', 'Autorizaci&oacute;n adicional');
define('AMZ_AMOUNT_LEFT_TO_AUTHORIZE', 'Autorizaci&oacute;n est&aacute;ndar');
define('AMZ_TYPE', 'Tipo');
define('AMZ_REFUNDS', 'Reembolsos');
define('AMZ_REFUND', 'Reembolsar');
define('AMZ_REFUND_SUCCESS', 'Reembolso correcto');
define('AMZ_REFUND_FAILED', 'Error en el reembolso');
define('AMZ_REFRESH', 'Actualizar');
define('AMZ_CAPTURE_AMOUNT_FROM_AUTH', 'Pago parcial cobrado');
define('AMZ_REFUND_TOTAL', 'Reembolso completo');
define('AMZ_REFUND_AMOUNT', 'Reembolso parcial');
define('AMZ_TX_AMOUNT_REFUNDED_HEADING', 'Reembolso');
define('AMZ_TX_SUM', 'Suma');
define('AMZ_TX_AMOUNT_REFUNDABLE_HEADING', 'A&uacute;n posible');
define('AMZ_TX_AMOUNT_POSSIBLE_HEADING', 'M&aacute;ximo');
define('AMZ_AUTHORIZE_AMOUNT', 'Autorizar pago parcial');
define('AMZ_TX_AMOUNT_NOT_AUTHORIZED_YET_HEADING', 'Importe a&uacute;n no autorizado');
define('AMZ_REFUND_OVER_AMOUNT', 'No reembolsar');
define('AMZ_FINISHED_REFRESHING_ORDER', 'Pedido actualizado');
define('AMZ_OVER_AUTHORIZE_AMOUNT', 'No autorizar');
define('MODULE_PAYMENT_AM_APA_IPN_STATUS_TITLE', 'Recibir actualizaciones de estado por IPN');
define('AMZ_CRON_URL_TITLE', 'URL de Cronjob');
define('MODULE_PAYMENT_AM_APA_CRON_STATUS_TITLE', 'Activar Cronjob para actualizaciones de estado');
define('AMZ_AGB_DISPLAY_MODE_TITLE', 'Visualizaci&oacute;n de condiciones y cancelaci&oacute;n');
define('AMZ_AGB_DISPLAY_MODE_SHORT', 'Solo advertencia');
define('AMZ_AGB_DISPLAY_MODE_LONG', 'Vista completa con casilla de verificaci&oacute;n');
define('AMZ_CRON_PW_TITLE', 'Contrase&ntilde;a de Cronjob');
define('AMZ_SOFT_DECLINE_SUBJECT_TITLE', 'Asunto de mensaje de pago rechazado');
define('AMZ_PAYMENT_NAME_TITLE', 'Nombre de remitente');
define('AMZ_PAYMENT_EMAIL_TITLE', 'Direcci&oacute;n del remitente');
define('AMZ_HARD_DECLINE_SUBJECT_TITLE', 'Asunto de mensaje de pedido rechazado');
define('AMZ_SEND_MAILS_ON_DECLINE_TITLE', 'Se notifica autom&aacute;ticamente al cliente cuando se rechaza un pago');
define('AMZ_FASTAUTH_SOFT_DECLINED', '&iquest;Deseas usar otro m&eacute;todo de pago?');
define('AMZ_FASTAUTH_HARD_DECLINED', 'Amazon Pay ha rechazado tu pago; por favor, usa otro m&eacute;todo de pago.');
define('AMZ_UNKNOWN_ERROR', 'Desafortunadamente no se ha podido completar tu pedido; por favor, int&eacute;ntalo de nuevo con otro m&eacute;todo de pago.');
define('AMZ_CANCEL_ORDER', 'Cancelar proceso de pago de Amazon');
define('AMZ_CLOSE_ORDER', 'Cerrar pedido');
define('AMZ_ORDER_CANCELLED', 'Proceso de pago cancelado');
define('AMZ_ORDER_CLOSED', 'Pedido cerrado');
define('AMZ_SHOW_ON_CHECKOUT_PAYMENT_TITLE', 'Mostrar el bot&oacute;n de Amazon en el proceso de pago');
define('AMZ_DEBUG_MODE_TITLE', 'Modo de soluci&oacute;n de errores');
define('AMZ_DEBUG_MODE_DESC', 'En el modo de soluci&oacute;n de errores, solo los administradores pueden ver pagos mediante las Amazon Pay.');
define('AMZ_EXCLUDED_SHIPPING_TITLE', 'Excluir opciones de env&iacute;o');
define('AMZ_NEW_VERSION_AVAILABLE', 'Hay disponible una nueva versi&oacute;n de este m&oacute;dulo');
define('AMZ_VERSION_IS_GOOD', 'Tu versi&oacute;n del modo est&aacute; actualizada');
define('AMZ_EXCLUDE_PRODUCTS', 'Excluir productos');
define('AMZ_SEARCH', 'B&uacute;squeda');
define('AMZ_EXCLUDED_PRODUCTS', 'Productos excluidos');
define('AMZ_SEARCH_RESULT', 'Resultados de la b&uacute;squeda');
define('AMZ_EXCLUDED_PRODUCTS_TITLE', 'Excluir productos del pago con Amazon');
define('AMZ_EXCLUDED_PRODUCTS_OPEN', ' Abrir');
define('AMZ_ALL_MANUFACTURERS', 'Todos los fabricantes');
define('AMZ_INCLUDE_ALL_PRODUCTS', 'Eliminar todo');
define('AMZ_EXCLUDE_ALL_PRODUCTS', 'Excluir todos');
define('AMZ_TEMPLATE_1', 'Plantilla 1');
define('AMZ_TEMPLATE_2', 'Plantilla 2');
define('AMZ_TEMPLATE_TITLE', 'Selecci&oacute;n de plantilla');
define('AMZ_CANCEL_ORDER_FROM_WALLET', 'Cancelar pago con Amazon Pay');
define('AMZ_WALLET_INTRO', 'Desafortunadamente, el m&eacute;todo de pago seleccionado ya no est&aacute; disponible. Por favor, selecciona otro.');
define('AMZ_DOWNLOAD_ONLY_TITLE', 'En esta tienda solo se pueden vender art&iacute;culos virtuales');
define('AMZ_DOWNLOAD_ONLY_DESC', 'En este caso, no se te pedir&aacute; ninguna direcci&oacute;n de env&iacute;o al seleccionarse la autorizaci&oacute;n durante el proceso de pago.');
define('AMZ_HEADING_AMAZON_PAYMENTS_ACCOUNT', 'Cuenta de Amazon Pay');
define('AMZ_HEADING_GENERAL_SETTINGS', 'Configuraci&oacute;n general');
define('AMZ_HEADING_DESIGN_SETTINGS', 'Configuraci&oacute;n de dise&ntilde;o');
define('AMZ_HEADING_IPN_SETTINGS', 'Configuraci&oacute;n de IPN');
define('AMZ_HEADING_CRONJOB_SETTINGS', 'Configuraci&oacute;n de Cronjob');
define('AMZ_HEADING_MAIL_SETTINGS', 'Opciones de correo electr&oacute;nico');

# Update V2.01
define('AMZ_DB_UPDATE_WARNING', 'Has actualizado el m&oacute;dulo. Tienes que actualizar la base de datos ahora.');
define('AMZ_DB_UPDATE_BUTTON_TEXT', 'Actualizar base de datos');
define('AMZ_IPN_PW_TITLE', 'Contrase&ntilde;a de IPN');
define('AMZ_SET_SELLER_ORDER_ID_TITLE', 'Enviar n&uacute;mero de pedido a Amazon');
define('AMZ_SET_SELLER_ORDER_ID_DESC', 'Nota: Puede haber huecos entre los n&uacute;meros de env&iacute;o debidos a pedidos cancelados.');
define('AMZ_ORDER_REF_HEADING', 'Referencia de pedido de Amazon');
define('AMZ_ORDERS_ID_HEADING', 'Pedido de la tienda');
define('AMZ_TRANSACTION_HISTORY', 'Protocolo de transacciones de Amazon');
define('AMZ_BACK', 'Volver');
define('AMZ_MERCHANT_ID_INVALID', 'No hay acci&oacute;n posible. Tu ID de vendedor de Amazon no coincide');
define('AMZ_STATUS_NONAUTHORIZED_TITLE', 'Estado de pedidos no autorizados');

# Update V2.10
define('MODULE_PAYMENT_AM_APA_LPA_MODE_TITLE', 'Modo Login y Pagar');
define('MODULE_PAYMENT_AM_APA_LPA_MODE_DESC', '');
define('MODULE_PAYMENT_AM_APA_CLIENTID_TITLE', 'ID de cliente para Login y Pagar');
define('MODULE_PAYMENT_AM_APA_CLIENTID_DESC', 'Esto se utiliza para la comunicaci&oacute;n y autenticaci&oacute;n con Login y Pagar de Amazon');
define('AMZ_SET_ADDRESS_TITLE', 'Gracias por iniciar sesi&oacute;n con Amazon Pay');
define('AMZ_SET_ADDRESS_INTRO', 'Para continuar, proporciona una direcci&oacute;n est&aacute;ndar. Siempre puedes elegir tu direcci&oacute;n de entrega durante el proceso de pago.');
define('AMZ_CONNECT_ACCOUNTS_TITLE', 'Gracias por iniciar sesi&oacute;n con Amazon Pay');
define('AMZ_CONNECT_ACCOUNTS_INTRO', 'Hemos encontrado una cuenta con tu direcci&oacute;n de correo electr&oacute;nico en nuestra tienda. Vuelve a introducir la contrase&ntilde;a de la tienda para conectar la cuenta con tu cuenta de Amazon.');
define('AMZ_PASSWORD', 'Tu contrase&ntilde;a');
define('AMZ_CONNECT_ACCOUNTS', 'Conectar cuentas');
define('AMZ_CONNECT_ACCOUNTS_ERROR', 'Error: contrase&ntilde;a incorrecta');
define('MODULE_PAYMENT_AM_APA_POPUP_TITLE', 'Ventana emergente de inicio de sesi&oacute;n si es posible');
define('MODULE_PAYMENT_AM_APA_POPUP_DESC', '&iquest;Prefieres que el inicio de sesi&oacute;n de Amazon aparezca en una ventana emergente? De lo contrario, se redirigir&aacute; al cliente a Amazon para que inicie sesi&oacute;n y volver&aacute; a la p&aacute;gina tras iniciar sesi&oacute;n en tu tienda. El inicio de sesi&oacute;n en ventana emergente s&oacute;lo est&aacute; disponible si la tienda entera est&aacute; protegida por SSL.');
define('AMZ_LOGIN_PROCESSING_TITLE', 'Gracias por iniciar sesi&oacute;n con Amazon Pay');
define('AMZ_LOGIN_PROCESSING_INTRO', 'En un momento se te dirigir&aacute; a otra p&aacute;gina...');
define('AMZ_BUTTON_SIZE_TITLE', 'Tama&ntilde;o del bot&oacute;n de pago de Amazon  (modo de pago)');
define('AMZ_BUTTON_SIZE_TITLE_LPA', 'Tama&ntilde;o del bot&oacute;n de pago de Amazon  (modo Login/Login y Pagar)');
define('AMZ_BUTTON_COLOR_TITLE', 'Color del bot&oacute;n de pago de Amazon  (modo de pago)');
define('AMZ_BUTTON_COLOR_TITLE_LPA', 'Color del bot&oacute;n de pago de Amazon  (modo Login/Login y Pagar)');
define('AMZ_BUTTON_COLOR_TAN_LIGHT', 'Gris claro');
define('AMZ_BUTTON_COLOR_TAN_DARK', 'Gris oscuro');
define('AMZ_BUTTON_SIZE_SMALL', 'Peque&ntilde;o');
define('AMZ_BUTTON_TYPE_LOGIN_TITLE', 'Tipo de bot&oacute;n Login');
define('AMZ_BUTTON_TYPE_PAY_TITLE', 'Tipo de bot&oacute;n Pagar');
define('AMZ_BUTTON_TYPE_LOGIN_LWA', 'Login con Amazon');
define('AMZ_BUTTON_TYPE_LOGIN_LOGIN', 'Iniciar sesi&oacute;n');
define('AMZ_BUTTON_TYPE_LOGIN_A', 'S&oacute;lo una -A-');
define('AMZ_BUTTON_TYPE_PAY_PWA', 'Pagar con Amazon');
define('AMZ_BUTTON_TYPE_PAY_PAY', 'Pagar');
define('AMZ_BUTTON_TYPE_PAY_A', 'S&oacute;lo una -A-');
define('AMZ_SAVE', 'Guardar');
define('AMZ_CONFIGURATION', 'Configuraci&oacute;n');
define('AMZ_PROTOCOL', 'Protocolo de transacciones');
define('AMZ_CLOSE_ORDER_ON_COMPLETE_CAPTURE_TITLE', 'Marcar pedidos como Cerrado tras cobrar el importe completo');
define('AMZ_CLOSE_ORDER_ON_COMPLETE_CAPTURE_DESC', '');
define('AMZ_BUTTON_TYPE_PAY_DESC', 'S&oacute;lo disponible en el modo operativo -Login y Pagar-');
define('AMZ_INVALID_SECRET', 'Tu clave secreta no es v&aacute;lida');
define('AMZ_INVALID_MERCHANT_ID', 'Tu ID de vendedor de Amazon no es v&aacute;lido');
define('AMZ_INVALID_ACCESS_KEY', 'Tu clave de acceso de MWS no es v&aacute;lida');
define('AMZ_CREDENTIALS_SUCCESS', 'Tus credenciales son v&aacute;lidas');
define('AMZ_LOG_STATUS_TITLE', '&iquest;Generar registro?');
define('AMZ_LOG_STATUS_DESC', 'Activar el registro de toda la actividad');
define('AMZ_CHECKOUT_SINGLE_PRODUCTS_TITLE', 'Pago con Amazon desde la p&aacute;gina de detalles del producto');
define('AMZ_CHECKOUT_SINGLE_PRODUCTS_DESC', '');
define('AMZ_SOMETHING_WRONG', 'Tus credenciales no son v&aacute;lidas');

#3.0
define('AMZ_REDIRECT_URL_TITLE', 'Allowed Return URLs');
define('AMZ_STATUS_HARDDECLINE_TITLE', 'Estado del pedido para pagos rechazados');
define('AMZ_CHANGE_PAYMENT_SUCCESS', 'Se ha cambiado con &eacute;xito tu m&eacute;todo de pago');
define('AMZ_NO_ACCOUNT_YET', '&iquest;Todavía no tienes una cuenta de vendedor?');
define('AMZ_MY_CREDENTIALS', 'Mis datos recibidos');
define('AMZ_CREATE_ACCOUNT', 'Registrar cuenta de vendedor');
define('AMZ_USE_MY_CREDENTIALS', 'Utilizar mis datos');
define('AMZ_NO_ACCOUNT_DESCRIPTION', '<b>Para crear tu cuenta de vendedor de Amazon ahora, s&oacute;lo tienes que seguir tres sencillos pasos:</b><br/>
<ol>
        <li>Haz clic en "'.AMZ_CREATE_ACCOUNT.'"</li>
        <li>Sigue las instrucciones</li>
        <li>Introduce los datos recibidos a continuaci&oacute;n en "'.AMZ_MY_CREDENTIALS.'" y haz clic en "'.AMZ_USE_MY_CREDENTIALS.'"</li>
</ol>
');
define('MODULE_PAYMENT_AM_APA_ALLOWED_PAYMENT_ZONES_TITLE', 'Zonas permitidas para la direcci&oacute;n de facturaci&oacute;n');
define('MODULE_PAYMENT_AM_APA_ALLOWED_PAYMENT_ZONES_DESC', 'Introduce <b>únicamente</b> las zonas permitidas para la direcci&oacute;n de facturaci&oacute;n. (por ejemplo, AT, DE; si se deja en blanco, se permiten todas las zonas)');
define('PAYMENT_ZONE_NOT_ALLOWED', 'No se admite la divisa de este pa&iacute;s. Selecciona otro m&eacute;todo de pago');

define('AMZ_STATUS_CAPTUREDECLINE_TITLE', 'Estado de pedido de cobros de pagos incorrectos');
define('AMZ_SEND_MAILS_ON_CAPTUREDECLINE_TITLE', 'Correo electr&oacute;nico informativo al administrador en caso de cobro de pagos incorrecto');
define('AMZ_CAPTURE_DECLINED_EMAIL_SUBJECT', 'Cobro de pagos de Amazon incorrecto para el pedido #%s');

# from /lang/.../amazon.php
define('AMZ_SINGLE_PRICE', 'Precio unitario');
define('AMZ_TOTAL_PRICE', 'Precio total');
define('NO_POSITIONS','No disponible.');
define('CANCEL','Cancelar');
define('AMZ_TOTAL', 'Total');
define('ACCEPT','Acepta nuestras condiciones y política de cancelaci&oacute;n.');
define('NO_SHIPPING','Envío no disponible.');
define('NO_SHIPPING_TO_ADDRESS', 'Env&iacute;o no disponible para esta direcci&oacute;n.');
define('FREE_SHIPPING_AT', 'Entrega sin gastos de env&iacute;o a ');
define('SUCCESS','El n&uacute;mero de referencia de tu pedido de Amazon en nuestra tienda en l&iacute;nea es:');
define('AMZ_WAITING', 'Espera, vamos a redirigirte a otra p&aacute;gina');
define('AMZ_WAITING_IMG', 'https://images-na.ssl-images-amazon.com/images/G/01/cba/images/global/Loading._V192259297_.gif');
define('AMZ_ZOLL', 'Para los env&iacute;os a pa&iacute;ses no pertenecientes a la UE, el cliente, y no el vendedor, podr&iacute;a tener que satisfacer el importe de otros derechos de aduana, impuestos o tasas a los organismos de aduanas y las autoridades fiscales competentes del pa&iacute;s. Se aconseja al cliente que se informe sobre las normativas de aduanas y fiscales antes de realizar el pedido.');
define('AMZ_ADMIN_HINT', '* reducido por el valor de las rebajas aplicadas o vales emitidos para los productos.');
define('AMZ_ADMIN_BTN', 'Procesar');
define('AMZ_VERSANDANTEIL', 'Cuota de env&iacute;o');
define('AMZ_PRODUKT', 'Producto');
define('AMZ_SHOW_HIDE', 'Mostrar/ocultar');
define('AMZ_REFUND_SUCCESS', 'Reembolso iniciado');
define('AMZ_REFUND_ERROR', 'Error de reembolso; comprueba que el importe es v&aacute;lido.');
define('AMZ_DATE', 'Fecha');
define('AMZ_BETRAG', 'Importe');
define('AMZ_ACCEPT_REVOCATION', 'Acepto las condiciones de cancelaci&oacute;n');
define('AGB_SHORT_TEXT', 'He le&iacute;do las <a href="%s" onclick="amzPopupWindow(\'%s\'); return false;" target="_blank">condiciones</a> del proveedor y declaro mi aceptaci&oacute;n de las mismas al realizar el pedido,');
define('REVOCATION_SHORT_TEXT', ' Comprendo la <a href="%s" onclick="amzPopupWindow(\'%s\'); return false;" target="_blank">pol&iacute;tica de cancelaci&oacute;n</a>.');
define('AMAZON_CHECKOUT', 'Amazon Pay'); 
define('AMZ_VIRTUAL_TEXT', 'Solo tienes mercanc&iacute;as virtuales en la cesta.');
define('AMZ_USE_GV', 'Administrar cr&eacute;dito');
define('AMZ_AMOUNT_TOO_LOW_ERROR', 'Los pedidos con importe cero no pueden procesarse en Amazon.');
define('TEXT_CONTINUE_AS_GUEST', 'Continua sin iniciar sesi&oacute;n');
define('AMZ_JS_ORIGIN_TITLE', 'Allowed JavaScript Origins');

# 3.0.2
define('AMZ_SEND_MAILS_ON_AUTH_TITLE', 'Enviar correo electr&oacute;nico de notificaci&oacute;n de autorizaciones concecidas');
define('AMZ_PAYMENT_AUTH_EMAIL_TITLE', 'Enviar correo electr&oacute;nico de notificaci&oacute;n de autorizaciones');
define('TEXT_AMZ_AUTH_EMAIL_SUBJECT', 'Amazon Pay: autorizaci&oacute;n concecida');
define('TEXT_AMZ_PAYMENT_GENERAL_ERROR', 'Se ha producido un error');
define('TEXT_AMZ_PAYMENT_METHOD_NOT_ALLOWED', 'Por favor, selecciona otro instrumento de pago');
define('AMZ_VAT_ID_INFO_TITLE', 'Mostrar aviso de n&uacute;mero de identificación fiscal para clientes de la UE');
define('AMZ_VAT_ID_INFO_DESC', '¿Quieres mostrar un aviso para los clientes de la UE de que es obligatorio introducir el n&uacute;mero de identificación fiscal en la cuenta de cliente?');
define('AMZ_VAT_ID_INFO_TEXT', '¿Eres un cliente de empresa? Entonces, introduce el n&uacute;mero de identificación fiscal en tu <a href="'.xtc_href_link('account_edit.php').'">cuenta de cliente</a>.');

#3.1.0
define('AMZ_ORDER_REFERENCE_IN_COMMENT_TITLE', 'Identificador de referencia de Amazon Pay en la nota del pedido');
define('AMZ_ORDER_REFERENCE_IN_COMMENT_PREFIX', 'Identificador de referencia de Amazon Pay');
define('AMZ_ORDER_STATUS_PARTIALLY_CAPTURED_TITLE', 'Estado del pedido por: pago parcial confirmado');
define('AMZ_ORDER_STATUS_CAPTURED_TITLE', 'Estado del pedido por: pago completo confirmado');
define('AMZ_ORDER_STATUS_SOFT_DECLINE_TITLE', 'Estado del pedido por: pago suspendido - necesaria acci&oacute;n del cliente');
define('AMZ_CHECKOUT_SUCCESS_PENDING_AUTHORIZATION', 'Tu transacci&oacute;n con Amazon Pay se est&aacute; verificando. Te informaremos tan pronto como sea posible.');

#3.1.1
define('AMZ_DELETE', 'Eliminar');
define('AMZ_INCREASE', 'Aumentar cantidad');
define('AMZ_DECREASE', 'Disminuir cantidad');
define('AMZ_PAY_EXISTING_ORDER_INTRO', 'For favor pague aqu&iacute; su pedido realizado');
define('AMZ_PAY_EXISTING_ORDER_SUCCESS', 'Gracias por pagar con Amazon Pay');
define('AMZ_PAY_EXISTING_ORDER_ERROR_WRONG_PAYMENT_METHOD', 'Este pedido no puede ser pagado con Amazon Pay');
define('AMZ_PAY_EXISTING_ORDER_ERROR_ALREADY_PAID', 'Este pedido ya ha sido pagado');
define('AMZ_SEND_PAY_MAIL', '&raquo; Enviar informaci&oacute;n de pago de Amazon Pay por correo electr&oacute;nico');
define('AMZ_SEND_PAY_MAIL_SUCCESS', 'Informaci&oacute;n de pago de Amazon Pay ha sido enviada');
define('AMZ_PAY_EXISTING_ORDER_EMAIL_SUBJECT', 'Informaci&oacute;n de pago de Amazon Pay para su pedido');

#3.2.0
define('AMZ_BUTTON_TOOLTIP', 'Amazon Pay utiliza la informaci&oacute;n de pago y entrega almacenada en tu cuenta de Amazon');

#PSD2
define('AMZ_ERROR_MFA_ABANDONED', 'Se ha cancelado la autenticaci&oacute;n. Por favor, pulse aqu&iacute; para intentarlo otra vez o seleccione otro m&eacute;todo de pago.');
define('AMZ_ERROR_MFA_FAILED', 'Ha fallado la autenticaci&oacute;n. Por favor, int&eacute;ntelo otra vez o seleccione m&eacute;todo de pago distinto.');
