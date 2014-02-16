$(document).ready(function() {
  $('#setLanguage').click(function() {
    $('#languages').slideToggle("slow");
  });

  $('#langPointer').click(function() {
    $('#languages').slideToggle("slow");
  });

  $('a[rel*=facebox]').facebox({
    loadingImage : '<?php echo SITE_URL?>includes/scripts/facebox/loading.gif',
    closeImage   : '<?php echo SITE_URL?>includes/scripts/facebox/closelabel.png'
  });
});

function pwdFocus() {
  $('#textfield').hide();
  $('#pwdfield').show();
  $('#pwdfield').focus();
}

function pwdBlur() {
  if ($('#pwdfield').attr('value') == '') {
    $('#pwdfield').hide();
    $('#textfield').show();
  }
}
function loginfocus(str) {
  if (str == "<?php echo $lang['loginform']['username_login']?>") {
      document.getElementById('username').value="";
      document.getElementById('username').style.fontStyle="normal";
    }
}
function loginblur(str) {
  if (str == "") {
      document.getElementById('username').value="<?php echo $lang['loginform']['username_login']?>";
    }
}