<?php

$dbTable->setTable($this->_session['db_prefix'] .'_userbox');

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table function
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
 * Callback function for update row of userbox database table
 */
function updateUserboxRow($updateList, $row, $vars) {
    $setFields = array();

    if (in_array('APPLY_BBCODE', $updateList))
        $setFields['message'] = $vars['bbcode']->apply(stripslashes($row['message']));

    return $setFields;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Check table integrity
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkIntegrity') {
    // table exist in 1.6.x version
    $dbTable->checkIntegrity('mid', 'message');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Convert charset and collation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'checkAndConvertCharsetAndCollation')
    $dbTable->checkAndConvertCharsetAndCollation();

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table creation
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'install') {
    $sql = 'CREATE TABLE `'. $this->_session['db_prefix'] .'_userbox` (
            `mid` int(50) NOT NULL auto_increment,
            `user_from` varchar(30) NOT NULL default \'\',
            `user_for` varchar(30) NOT NULL default \'\',
            `titre` varchar(50) NOT NULL default \'\',
            `message` text NOT NULL,
            `date` varchar(30) NOT NULL default \'\',
            `status` int(1) NOT NULL default \'0\',
            PRIMARY KEY  (`mid`),
            KEY `user_from` (`user_from`),
            KEY `user_for` (`user_for`)
        ) ENGINE=MyISAM DEFAULT CHARSET='. db::CHARSET .' COLLATE='. db::COLLATION .';';

    $dbTable->dropTable()->createTable($sql);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Table update
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($process == 'update') {
    // Update BBcode
    // update 1.7.9 RC1
    if (version_compare($this->_session['version'], '1.7.9', '<=')) {
        $dbTable->setCallbackFunctionVars(array('bbcode' => new bbcode($this->_db, $this->_session, $this->_i18n)))
            ->setUpdateFieldData('APPLY_BBCODE', 'message');
    }

    $dbTable->applyUpdateFieldListToData('mid', 'updateUserboxRow');
}

?>