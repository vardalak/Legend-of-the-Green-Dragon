<?php
declare(strict_types=1);

/**
 * Adds a news item for the current user
 *
 * @param string $text Line of text for the news.
 * @param array $options List of options, including replacements, to modify the acctid, date, or hide from biographies.
 * @todo Change the date format from Y-m-d to Y-m-d H:i:s.
 */
function addnews(string $text = '', array $options = [])
{
    global $translation_namespace, $session;
    $options = modulehook('addnews', $options);
    $news = db_prefix('news');
    $replacements = [];
    foreach ($options as $key => $val) {
        if (is_numeric($key)) {
            array_push($replacements, $val);
        }
    }
    $text = addslashes(sprintf_translate($text, $replacements));
    $date = ($options['date']) ?? date('Y-m-d');
    $acctid = ($options['acctid']) ?? $session['user']['acctid'];
    if (!array_key_exists('hide', $options) || !$options['hide']) {
        $sql = db_query(
            "INSERT INTO $news (newstext, newsdate, accountid, tlschema)
            VALUES ('$text', '$date', '$acctid', '$translation_namespace')"
        );
    }
    else {
        $sql = db_query(
            "INSERT INTO $news (newstext, newsdate, tlschema)
            VALUES ('$text', '$date', '$translation_namespace')"
        );
    }
}
