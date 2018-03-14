<?php
setlocale (LC_TIME, 'fr_FR.utf8','fra');

function markUp($pattern, $replaceBy, $text, $omit = NULL) {
  $newText = "";
  if(!empty($omit)) {
    $biggerSections = explode($omit, $text);
    foreach ($biggerSections as $pos => $section) {
      if($pos % 2 == 0) {
        $sections = explode($pattern, $section);
        foreach ($sections as $pospos => $valeur) {
          if($pospos % 2 == 1)
            $newText .= $replaceBy[0];
          $newText .= $valeur;
          if($pospos % 2 == 1)
            $newText .= $replaceBy[1];
        }
      }
      else
        $newText .= $omit . $section . $omit;

    }
  }
  else {
    $sections = explode($pattern, $text);
    foreach ($sections as $pospos => $valeur) {
      if($pospos % 2 == 1)
        $newText .= $replaceBy[0];
      $newText .= $valeur;
      if($pospos % 2 == 1)
        $newText .= $replaceBy[1];
    }
  }
  return $newText;
}

function markUpLinks($patternStart, $patternEnd, $text, $omit = NULL) {
  $newText = "";
  if(!empty($omit)) {
    $biggerSections = explode($omit, $text);
    foreach ($biggerSections as $pos => $section) {
      if($pos % 2 == 0) {
        $occ = explode($patternStart[0], $section);
        $newText .= $occ[0];
        array_splice($occ, 0, 1);
        foreach ($occ as $pospos => $valeur) {
          $link = explode($patternEnd[0], $valeur);
          if(count($link) > 1) {
            $newText .= "<a target=\"_blank\" href=\"" . $link[0] . "\">";
            $linkText = explode($patternEnd[1], $link[1]);
            if(count($linkText) > 1 && $linkText[0][0] == $patternStart[1]) {
              $newText .=  substr($linkText[0], 1) . "</a>";
              array_splice($linkText, 0, 1);
              $newText .= implode($linkText);
            }
            else {
              $newText .= $link[0] . "</a>";
              array_splice($link, 0, 1);
              $newText .= implode($link);
            }
          }
          else
            $newText .= $patternStart . $valeur;
        }
      }
      else
        $newText .= $omit . $section . $omit;
    }
  }
  else {
    $sections = explode($pattern, $text);
    foreach ($sections as $pospos => $valeur) {
      if($pospos % 2 == 1)
        $newText .= $replaceBy[0];
      $newText .= $valeur;
      if($pospos % 2 == 1)
        $newText .= $replaceBy[1];
    }
  }
  return $newText;
}

function formatEverything($string) {
  $text = htmlspecialchars($string, ENT_NOQUOTES);
  $text = str_replace("\n", "<br>", $text);
  $text = str_replace(["@@<br>", "<br>@@"], "@@", $text);
  $text = markUp("**", ['<strong>', '</strong>'], $text, "@@");
  $text = markUp("\"\"", ['<em>', '</em>'], $text, "@@");
  $text = markUp("~~", ['<del>', '</del>'], $text, "@@");
  $text = markUp("__", ['<ins>', '</ins>'], $text, "@@");
  $text = markUp("^^", ['<sup>', '</sup>'], $text, "@@");
  $text = markUp("##", ['<mark>', '</mark>'], $text, "@@");
  $text = markUpLinks(["[", "("], ["]", ")"], $text, "@@");
  $text = markUp("@@", ['<code>', '</code>'], $text);
  return $text;
}

 function compareRowsPoints($a, $b) {
   return $b['points'] - $a['points'];
 }

 define( TIMEBEFORE_NOW,         'à l\'instant' );
 define( TIMEBEFORE_MINUTE,      'il y a {num} minute' );
 define( TIMEBEFORE_MINUTES,     'il y a {num} minutes' );
 define( TIMEBEFORE_HOUR,        'il y a {num} heure' );
 define( TIMEBEFORE_HOURS,       'il y a {num} heures' );
 define( TIMEBEFORE_YESTERDAY,   'hier' );
 define( TIMEBEFORE_FORMAT,      '%e %B' );
 define( TIMEBEFORE_FORMAT_YEAR, '%e %B, %Y' );

 function time_ago( $time )
 {
   $out    = ''; // what we will print out
   $now    = time(); // current time
   $diff   = $now - $time; // difference between the current and the provided dates

   if( $diff < 60 ) // it happened now
    return TIMEBEFORE_NOW;

   elseif( $diff < 3600 ) // it happened X minutes ago
    return str_replace( '{num}', ( $out = round( $diff / 60 ) ), $out == 1 ? TIMEBEFORE_MINUTE : TIMEBEFORE_MINUTES );

   elseif( $diff < 3600 * 24 ) // it happened X hours ago
    return str_replace( '{num}', ( $out = round( $diff / 3600 ) ), $out == 1 ? TIMEBEFORE_HOUR : TIMEBEFORE_HOURS );

   elseif( $diff < 3600 * 24 * 2 ) // it happened yesterday
    return TIMEBEFORE_YESTERDAY;

   else // falling back on a usual date format as it happened later than yesterday
    return strftime( date( 'A', $time ) == date( 'A' ) ? TIMEBEFORE_FORMAT : TIMEBEFORE_FORMAT_YEAR, $time );
 }
 ?>
