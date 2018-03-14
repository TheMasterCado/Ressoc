<?php
function markUp($pattern, $replaceBy, $text, $omit = NULL) {
  $newText = "";
  if(!empty($omit)) {
    foreach ($omit as $i => $o) {
      $biggerSections = explode($o, $text);
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
        else {
          $newText .= $o . $section . $o;
        }
      }
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
    foreach ($omit as $i => $o) {
      $biggerSections = explode($o, $text);
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
        else {
          $newText .= $o . $section . $o;
        }
      }
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
  $text = markUp("**", ['<strong>', '</strong>'], $text, ["@@", "=;;"]);
  $text = markUp("\"\"", ['<em>', '</em>'], $text, ["@@", "=;;"]);
  $text = markUp("~~", ['<del>', '</del>'], $text, ["@@", "=;;"]);
  $text = markUp("__", ['<ins>', '</ins>'], $text, ["@@", "=;;"]);
  $text = markUp("^^", ['<sup>', '</sup>'], $text, ["@@", "=;;"]);
  $text = markUp("##", ['<mark>', '</mark>'], $text, ["@@", "=;;"]);
  $text = markUpLinks(["[", "("], ["]", ")"], $text, ["@@", "=;;"]);
  $text = markUp("@@", ['<code>', '</code>'], $text, ["=;;"]);
  return $text;
}
?>
