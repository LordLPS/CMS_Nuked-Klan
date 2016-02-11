<?php
/**
 * table._forums_options.c.i.php
 *
 * `[PREFIX]_forums_options` database table script
 *
 * @version 1.8
 * @link http://www.nuked-klan.org Clan Management System for Gamers
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright 2001-2015 Nuked-Klan (Registred Trademark)
 */

$dbTable->setTable(FORUM_OPTIONS_TABLE);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table configuration
///////////////////////////////////////////////////////////////////////////////////////////////////////////

$forumPollOptionsTableCfg = array(
    'fields' => array(
        'id'          => array('type' => 'int(11)',      'null' => false, 'default' => '\'0\''),
        'poll_id'     => array('type' => 'int(11)',      'null' => false, 'default' => '\'0\''),
        'option_text' => array('type' => 'varchar(255)', 'null' => false, 'default' => '\'\''),
        'option_vote' => array('type' => 'int(11)',      'null' => false, 'default' => '\'0\'')
    ),
    'index' => array(
        'poll_id' => 'poll_id'
    ),
    'engine' => 'MyISAM'
);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Check table integrity
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkIntegrity') {
    // table create in 1.7.x version
    $dbTable->checkIntegrity();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Convert charset and collation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkAndConvertCharsetAndCollation')
    $dbTable->checkAndConvertCharsetAndCollation();

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table drop
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'drop' && $dbTable->tableExist())
    $dbTable->dropTable();

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table creation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'install')
    $dbTable->createTable($forumPollOptionsTableCfg);

?>