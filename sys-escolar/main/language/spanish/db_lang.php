<?php

$lang['db_invalid_connection_str'] = 'No se puede determinar la configuración de la base de datos en función de la cadena de conexión que envió';
$lang['db_unable_to_connect'] = 'No se puede conectar a su servidor de base de datos usando la configuración proporcionada.';
$lang['db_unable_to_select'] = 'No se puede seleccionar la base de datos especificada: %s';
$lang['db_unable_to_create'] = 'Imposible crear la base de datos especificada: %s';
$lang['db_invalid_query'] = 'La consulta que ha enviado no es válida.';
$lang['db_must_set_table'] = 'Debe establecer la tabla de base de datos que se utilizará con su consulta.';
$lang['db_must_use_set'] = 'Debe usar el método "set" para actualizar una entrada.';
$lang['db_must_use_index'] = 'Debe especificar un índice para que coincida con las actualizaciones por lotes.';
$lang['db_batch_missing_index'] = 'Faltan el índice especificado en una o más filas enviadas para la actualización por lotes.';
$lang['db_must_use_where'] = 'Las actualizaciones no están permitidas a menos que contengan una cláusula "where".';
$lang['db_del_must_use_where'] = 'Las eliminaciones no están permitidas a menos que contengan una cláusula "where" o "like".';
$lang['db_field_param_missing'] = 'Para obtener campos requiere el nombre de la tabla como parámetro.';
$lang['db_unsupported_function'] = 'Esta función no está disponible para la base de datos que está utilizando.';
$lang['db_transaction_failure'] = 'Error de transacción: Rollback realizado.';
$lang['db_unable_to_drop'] = 'No se puede eliminar la base de datos especificada.';
$lang['db_unsuported_feature'] = 'Característica no admitida de la plataforma de base de datos que está utilizando.';
$lang['db_unsuported_compression'] = 'El servidor no admite el formato de compresión de archivos que elija.';
$lang['db_filepath_error'] = 'Imposible escribir datos en la ruta del archivo que ha enviado.';
$lang['db_invalid_cache_path'] = 'La ruta de acceso del caché que ha enviado no es válida ni puede escribirse.';
$lang['db_table_name_required'] = 'Se requiere un nombre de tabla para esa operación.';
$lang['db_column_name_required'] = 'Se requiere un nombre de columna para esa operación.';
$lang['db_column_definition_required'] = 'Se requiere una definición de columna para esa operación.';
$lang['db_unable_to_set_charset'] = 'Imposible establecer el conjunto de caracteres de la conexión del cliente: %s';
$lang['db_error_heading'] = 'Ocurrio un error en la base de datos.';

/* End of file db_lang.php */
/* Location: ./system/language/english/db_lang.php */