

<div id='languages' class="top-language" style="display:none">
  <?php
    $langs= $database->getActiveLanguages();
    echo "<ul>";
    echo "<li><a href='javascript:void(0)' onclick='javascript:setLanguage(\"en\");' style='color:gray'>English</a></li>";
    foreach($langs as $row)
    {
      echo "<li><a href='javascript:void(0)' onclick='javascript:setLanguage(\"".$row['langcode']."\");' style='color:gray'>".$row['lang']."</a></li>";
    }
    echo "</ul>";
  ?>
</div>