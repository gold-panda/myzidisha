<div>
<?php if(isset($_SESSION['invalidForm'])) {
      unset($_SESSION['invalidForm']);
      echo "<div style='width:100%; background-color:red;color:white;text-align:center'>CSRF token invalid please try again</div>";
  } ?>
</div>