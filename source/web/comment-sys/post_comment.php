<?php
require('Persistence.php');

$db = new Persistence();
$added = $db->add_comment($_POST);

if($added) {
  header( 'Location: ../articles/article_sample.php' );
}
else {
  header( 'Location: ../articles/article_sample.php?error=Your comment was not posted due to errors in your form submission' );
}
?>
