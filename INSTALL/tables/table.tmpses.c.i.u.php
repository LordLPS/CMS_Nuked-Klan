<?php
/**
 * table.tmpses.c.i.u.php
 *
 * `[PREFIX]_tmpses` database table script
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2016 Nuked-Klan (Registred Trademark)
 */

$dbTable->setTable(PHP_SESSIONS_TABLE);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table configuration
///////////////////////////////////////////////////////////////////////////////////////////////////////////

$tmpSessionsTableCfg = array(
    'fields' => array(
        'session_id'    => array('type' => 'varchar(64)', 'null' => false),
        'session_vars'  => array('type' => 'text',        'null' => false),
        'session_start' => array('type' => 'bigint(20)',  'null' => false)
    ),
    'primaryKey' => array('session_id'),
    'engine' => 'MyISAM'
);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Check table integrity
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkIntegrity') {
    if ($dbTable->tableExist())
        $dbTable->checkIntegrity();
    else
        $dbTable->setJqueryAjaxResponse('NO_TABLE_TO_CHECK_INTEGRITY');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Convert charset and collation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkAndConvertCharsetAndCollation') {
    if ($dbTable->tableExist())
        $dbTable->checkAndConvertCharsetAndCollation();
    else
        $dbTable->setJqueryAjaxResponse('NO_TABLE_TO_CONVERT');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table drop
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'drop' && $dbTable->tableExist())
    $dbTable->dropTable();

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table creation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

// install / update 1.7.9 RC5
if ($process == 'install' || ($process == 'createTable' && ! $dbTable->tableExist()))
    $dbTable->createTable($tmpSessionsTableCfg);

?>