{extends file="content.tpl"}

{block name=title}Borrower Account Creation{/block}
{block name=classname}form_page{/block}
{block name=description}{/block}

{block name=content}
<h3>Welcome to Zidisha.</h3>
<p>Please complete each item on this page. You may save a partially completed form in your member account by clicking <b>"Save and Complete Later."</b> Once your application is complete, click <b>"Submit Final Application"</b> to send it to Zidisha.</p>     

<div id="success" class="alert_box success" style="display:none"><p>Form was submitted.</p></div>

<form action="ajaxform.php" method="post" name="contact" id="contact">
  <fieldset>
    <label class="label">Country <span class="req">*</span></label>
    <select id="lcountry" class="custom_select" name="lcountry" >
          
    </select>
  </fieldset>

  <fieldset>
    <label class="label">Verify Online Identity<span class="req">*</span></label>
    <small class="note">Please ensure that you link your own Facebook account that you have held for a long time and use regularly. A link to your public Facebook page will be displayed to lenders in your Zidisha loan profile page.</small>
    <small class="error">Facebook Connect required.</small>

    <a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('https://www.facebook.com/dialog/oauth?client_id=325782300883543&redirect_uri=https%3A%2F%2Fwww.zidisha.org%2Findex.php%3Fp%3D1%26sel%3D1%26lang%3Den&state=81ac30fcf8d7d6915832e2c21d181576&canvas=1&fbconnect=0&display=popup&scope=email%2Cuser_location%2Cpublish_stream%2Cread_stream');" ><img src="/static/images/facebook-connect.png"/></a>
  </fieldset>

  <fieldset>
    <label class="label">Username <span class="req">*</span></label>
    <small class="note">Your username will be displayed to the public, and cannot be changed.</small>
    <small class="error">Username required.</small>
    <input type="text" id="" name="">
  </fieldset>

  <fieldset>
    <label class="label">Create Password <span class="req">*</span></label>
    <small class="error">Password required.</small>
    <input type="password" id="" name="">
  </fieldset>
  
  <fieldset>
    <label class="label">Email Address <span class="req">*</span></label>
    <small class="error">Incorrect email address format.</small>
    <input type="email" id="" name="">
  </fieldset>


  <fieldset>
    <label class="label">Accept Terms of Use <span class="req">*</span></label>
    <label>
      <INPUT TYPE="checkbox" name="agree" id="agree" value="1" tabindex="3" />I have read and agree to the <a class="terms_of_use_action" href="#">Zidisha Terms of Use</a>.
    </label>
    <small class="error">Please accept the terms of use.</small>
  </fieldset>

  <fieldset class="submit">
    <input type="submit" value="Join Zidisha" class="btn">
  </fieldset>
</form>
{/block}

{block name=sidebar}

{/block}