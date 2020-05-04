<?php
/**
 * Function to localize our site
 * @param $site \Clue\Site the Site object
 */
return function(Clue\Site $site) {
    // Set the time zone
    date_default_timezone_set('America/Detroit');

    $site->setEmail('hennin67@cse.msu.edu');
    $site->setRoot('/~hennin67/project2');
    $site->dbConfigure('mysql:host=mysql-user.cse.msu.edu;dbname=hennin67',
        'hennin67',       // Database user
        'PREg8NRNCytqa0Od',     // Database password
        'testclue_');            // Table prefix
};