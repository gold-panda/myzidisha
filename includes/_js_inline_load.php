<script type="text/javascript">
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

    var siteurl = "<?php echo SITE_URL ?>";
    var rqstUri = "<?php echo $rqstUri ?>";

    if("<?php echo $langfrmIP ?>" != "") {
      setLanguage("<?php echo $langfrmIP?>"); 
    }
    function setLanguage(lan){
      if(lan=='en'){
        url= siteurl+rqstUri;
        window.location=url;
      }else{
        url= siteurl+lan+'/'+rqstUri;
        window.location=url;
      }
    }

    // Google Analytics code 
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-23722503-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

    // SiftScience code
    var _user_id = "<?php echo $session->userid ?>"; // TODO: Set to the user's ID, username, email address, or '' if not yet known
    var _sift = _sift || []; _sift.push(['_setAccount', '946aa02e41']); _sift.push(['_setUserId', _user_id]); _sift.push(['_trackPageview']); (function() { function ls() { var e = document.createElement('script'); e.type = 'text/javascript'; e.async = true; e.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.siftscience.com/s.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(e, s); } if (window.attachEvent) { window.attachEvent('onload', ls); } else { window.addEventListener('load', ls, false); }})();
  </script>