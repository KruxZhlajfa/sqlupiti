<?php 

function xmldb_qtype_sqlupiti_upgrade($oldversion = 0) {
    global $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($oldversion < 2014051901) {

        // Define table question_sqlupiti to be created.
        $table = new xmldb_table('question_sqlupiti');

        // Adding fields to table question_sqlupiti.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('questionid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('sqlanswer', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('server', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('username', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('password', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('dbname', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table question_sqlupiti.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('questionid', XMLDB_KEY_FOREIGN, array('questionid'), 'question', array('id'));

        // Conditionally launch create table for question_sqlupiti.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Sqlupiti savepoint reached.
        upgrade_plugin_savepoint(true, 2014051901, 'qtype', 'sqlupiti');
    }
    
    if ($oldversion < 2014061402) {

        // Define table question_sqlupiti to be renamed to qtype_sqlupiti_options.
        $table = new xmldb_table('question_sqlupiti');

        // Launch rename table for question_sqlupiti.
        $dbman->rename_table($table, 'qtype_sqlupiti_options');

        // Sqlupiti savepoint reached.
        upgrade_plugin_savepoint(true, 2014061402, 'qtype', 'sqlupiti');
    }

    return $result;
}


   
?>