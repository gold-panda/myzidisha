{extends file="content.tpl"}

{block name=title}Contact Us{/block}
{block name=classname}form_page{/block}
{block name=description}Peer-to-peer lending across the international wealth divide.{/block}

{block name=content}
<h3>We would like to hear from you!</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent justo ligula, interdum ut lobortis quis, interdum vitae metus. Proin fringilla metus non nulla cursus, sit amet rutrum est pretium.</p>     

<div id="success" class="alert_box success" style="display:none"><p>Form was submitted.</p></div>

<form action="ajaxform.php" method="post" name="contact" id="contact">
  <fieldset>
    <label class="label">Name <span class="req">*</span></label>
    <small class="note">Your full name please.</small>
    <small class="error">Name required</small>
    <input type="text" id="f_name" name="f_name">
  </fieldset>
  <fieldset>
    <label class="label">Email Address <span class="req">*</span></label>
    <small class="note">This will be the email I contact you with.</small>
    <small class="error">incorrect email address</small>
    <input type="email" id="f_email" name="f_email">
  </fieldset>
  <fieldset>
    <label class="label">Subject <span class="req">*</span></label>
    <small class="note">Be creative if you like.</small>
    <small class="error">Subject required</small>
    <input type="text" name="f_subj" id="f_subj">
  </fieldset>
  <fieldset>
    <label class="label">Message <span class="req">*</span></label>
    <small class="note">Please don’t send me too long of a message ... jks!</small>
    <small class="error">Message required</small>
    <textarea name="f_message" id="f_message"></textarea>
  </fieldset>
  <fieldset class="submit">
    <input type="submit" value="Send Message" class="btn">
  </fieldset>
</form>
{/block}

{block name=sidebar}
<div class="widget widget_text">
  <h3 class="widgettitle">Our Location</h3>
  <div class="textwidget">
    <p>Come check out office out and meet our team in person.</p>
    <p>222 Avenue C South<br>
    Saskatoon, Saskatchewan<br>
    S7K 2N5</p>
  </div>
</div>
<div class="widget widget_text">
  <h3 class="widgettitle">Contact Information</h3>
  <div class="textwidget">
    <p>
      <strong>Email:</strong> info@deliver.ca<br>
      <strong>Primary Phone:</strong> 1 (306) 222 - 3456<br>
      <strong>Alternate Phone:</strong> 1 (306) 222 - 4567<br>
      <strong>Fax:</strong> 1 (306) 222 - 5678
    </p>
  </div>
</div>
<div class="widget widget_text">
  <h3 class="widgettitle">Office Hours</h3>
  <div class="textwidget">
    <p>
      <span>Monday - Friday</span> 8 am - 5 pm<br>
      <span>Saturday - Sunday</span> Closed<br>
      <span>Holidays</span> Closed
    </p>
    <p>* Feel free to email or call us after hours.</p>
  </div>
</div>
{/block}