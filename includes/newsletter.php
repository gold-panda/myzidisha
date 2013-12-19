<?php
include_once("library/session.php");
include_once("./editables/newsletter.php");
$path=	getEditablePath('newsletter.php');
include_once("./editables/".$path);
?>
<!-- Begin MailChimp Signup Form -->
<!--[if IE]>
<style type="text/css" media="screen">
	#mc_embed_signup fieldset {position: relative;}
	#mc_embed_signup legend {position: absolute; top: -1em; left: .2em;}
</style>
<![endif]-->
<!--[if IE 7]>
<style type="text/css" media="screen">
	.mc-field-group {overflow:visible;}
</style>
<![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<script type="text/javascript" src="http://downloads.mailchimp.com/js/jquery.validate.js"></script>
<script type="text/javascript" src="http://downloads.mailchimp.com/js/jquery.form.js"></script>

<div class="span12">
	<div id="static">
		<h1><?php echo $lang['newsletter']['newsletter'] ?></h1>
		<label class="custom_label"><?php echo $lang['newsletter']['enter_email'] ?>:</label>
		<div id="mc_embed_signup" style="width: 500px;">
			<form action="http://zidisha.us1.list-manage1.com/subscribe/post?u=c8b5366ff36890ecbc3bf00cc&amp;id=d2aeae704b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" style="font: normal 100% Arial;font-size: 12px;">
				<fieldset style="-moz-border-radius: 4px;border-radius: 4px;-webkit-border-radius: 4px;border: 1px solid #FFFFFF;padding-top: 1.5em;margin: .5em 0;background-color: #FFFFFF;color: #333333;text-align: left;">
					<div class="mc-field-group" style="clear: both;overflow: hidden;float:left">
						<label for="mce-EMAIL" style="display: block;margin: .3em 0;line-height: 1em;font-weight: bold;"><?php echo $lang['newsletter']['email_add'] ?></label>
						<input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="margin-right: 1.5em;padding: .2em .3em;width: 90%;float: left;z-index: 999;">
					</div>
					<div style="float:left;padding-top:7px"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn" style="clear: both;width: auto;display: block;margin: 1em 0 1em 5%;"></div>
					<div style="clear:both"></div>
					<div id="mce-responses" style="float: left;top: -1.4em;padding: 0em .5em 0em .5em;overflow: hidden;width: 90%;margin: 0 5%;clear: both;">
						<div class="response" id="mce-error-response" style="display: none;margin: 1em 0;padding: 1em .5em .5em 0;font-weight: bold;float: left;top: -1.5em;z-index: 1;width: 80%;background: #FFEEEE;color: #FF0000;"></div>
						<div class="response" id="mce-success-response" style="display: none;margin: 1em 0;padding: 1em .5em .5em 0;font-weight: bold;float: left;top: -1.5em;z-index: 1;width: 80%;background: #;color: #529214;"></div>
					</div>					
				</fieldset>
				<a href="#" id="mc_embed_close" class="mc_embed_close" style="display: none;"><?php echo $lang['newsletter']['close'] ?></a>
			</form>
		</div>

<!--- Please do not touch following script untill you really need to change -->
		<script type="text/javascript">
			var fnames = new Array();var ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';var err_style = '';
		try{
			err_style = mc_custom_error_style;
		}
		catch(e){
			err_style = 'margin: 1em 0 0 0; padding: 1em 0.5em 0.5em 0.5em; background: FFEEEE none repeat scroll 0% 0%; font-weight: bold; float: left; z-index: 1; width: 80%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: FF0000;';
		}
		var mce_jQuery = jQuery.noConflict();
		mce_jQuery(document).ready( function($) {
			var options = { errorClass: 'mce_inline_error', errorElement: 'div', errorStyle: err_style, onkeyup: function(){}, onfocusout:function(){}, onblur:function(){}  };
			var mce_validator = mce_jQuery("#mc-embedded-subscribe-form").validate(options);
			options = { url: 'http://zidisha.us1.list-manage1.com/subscribe/post-json?u=c8b5366ff36890ecbc3bf00cc&id=d2aeae704b&c=?', type: 'GET', dataType: 'json', contentType: "application/json; charset=utf-8",
                beforeSubmit: function(){
                    mce_jQuery('#mce_tmp_error_msg').remove();
                    mce_jQuery('.datefield','#mc_embed_signup').each(
                        function(){
                            var txt = 'filled';
                            var fields = new Array();
                            var i = 0;
                            mce_jQuery(':text', this).each(
                                function(){
                                    fields[i] = this;
                                    i++;
                                });
                            mce_jQuery(':hidden', this).each(
                                function(){
                                	if ( fields[0].value=='MM' && fields[1].value=='DD' && fields[2].value=='YYYY' ){
                                		this.value = '';
									} else if ( fields[0].value=='' && fields[1].value=='' && fields[2].value=='' ){
                                		this.value = '';
									} else {
	                                    this.value = fields[0].value+'/'+fields[1].value+'/'+fields[2].value;
	                                }
                                });
                        });
                    return mce_validator.form();
                },
                success: mce_success_cb
            };
			 mce_jQuery('#mc-embedded-subscribe-form').ajaxForm(options);
			});

			function mce_success_cb(resp)
			{
				mce_jQuery('#mce-success-response').hide();
				mce_jQuery('#mce-error-response').hide();
				if (resp.result=="success")
				{
					mce_jQuery('#mce-'+resp.result+'-response').show();
					mce_jQuery('#mce-'+resp.result+'-response').html(resp.msg);
					mce_jQuery('#mc-embedded-subscribe-form').each(function(){this.reset();});
				}
				else
				{
					var index = -1;
					var msg;
					try {
						var parts = resp.msg.split(' - ',2);
						if (parts[1]==undefined){
							msg = resp.msg;
						}
						else {
							i = parseInt(parts[0]);
							if (i.toString() == parts[0]){
								index = parts[0];
								msg = parts[1];
							}
							else {
								index = -1;
								msg = resp.msg;
							}
						}
					}
					catch(e){
						index = -1;
						msg = resp.msg;
					}
					try{
						if (index== -1){
							mce_jQuery('#mce-'+resp.result+'-response').show();
							mce_jQuery('#mce-'+resp.result+'-response').html(msg);
						}
						else {
							err_id = 'mce_tmp_error_msg';
							html = '<div id="'+err_id+'" style="'+err_style+'"> '+msg+'</div>';
							var input_id = '#mc_embed_signup';
							var f = mce_jQuery(input_id);
							if (ftypes[index]=='address'){
								input_id = '#mce-'+fnames[index]+'-addr1';
								f = mce_jQuery(input_id).parent().parent().get(0);
							} else if (ftypes[index]=='date'){
								input_id = '#mce-'+fnames[index]+'-month';
								f = mce_jQuery(input_id).parent().parent().get(0);
							} else {
								input_id = '#mce-'+fnames[index];
								f = mce_jQuery().parent(input_id).get(0);
							}
							if (f){
								mce_jQuery(f).append(html);
								mce_jQuery(input_id).focus();
							} else {
								mce_jQuery('#mce-'+resp.result+'-response').show();
								mce_jQuery('#mce-'+resp.result+'-response').html(msg);
							}
						}
					}
					catch(e){
						mce_jQuery('#mce-'+resp.result+'-response').show();
						mce_jQuery('#mce-'+resp.result+'-response').html(msg);
					}
				}
			}
		</script>
<!--- Please do not touch above script untill you really need to change -->
		
		<label class="custom_label"><?php echo $lang['newsletter']['newsletter_archive'] ?>:</label><br/><br/>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=7617980ed9&e=[UNIQID]" target="_blank">November 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=1599442e4c" target="_blank">October 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=4f45e31f70" target="_blank">September 2013</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=3c02b7832a&e=9fd3b48fc7" target="_blank">August 2013</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=794f47efe6" target="_blank">July 2013</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=4fd26bcc18" target="_blank">June 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=ad76c079cf" target="_blank">May 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=4b4b16888e" target="_blank">April 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=c6bbd96fd8" target="_blank">February 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=6b6b1c9eb1" target="_blank">January 2013</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=2dce0bc6f9" target="_blank">December 2012</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=1bb5cc4cc4" target="_blank">November 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=0ce2cc2e05" target="_blank">October 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=790e679c85" target="_blank">September 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=81a822db23" target="_blank">August 2012</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=7597485b1a" target="_blank">July 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=f3089ed3d2" target="_blank">June 2012</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=f5b7c5b570" target="_blank">May 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=256281d303" target="_blank">April 2012</a></p>

<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=d07a0aa9ef" target="_blank">March 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=918b1013e2" target="_blank">February 2012</a></p>
		
<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=08a4d48f07" target="_blank">January 2012</a></p>

<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=f88790d4c5" target="_blank">December 2011</a></p>

		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=4031e8a428" target="_blank">November 2011</a></p>
		
		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=f7dd4dccbc" target="_blank">October 2011</a></p>
		
		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=709acde036" target="_blank">September 2011</a></p>
		
		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=8af7e50057" target="_blank">August 2011</a></p>
		
		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=a3d2e06154" target="_blank">July 2011</a></p>
		
		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=56159a82d0" target="_blank">June 2011</a></p>
		
		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=3a88d2a08b" target="_blank">May 2011</a></p>
		
		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=c54cb40f2e" target="_blank">April 2011</a></p>

		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=c9e1f2dc3e" target="_blank">March 2011</a></p>

		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=b2d5b34d58" target="_blank">February 2011</a></p>

		<p><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=c03f1f41cf" target="_blank">January 2011</a></p>

		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=55b03ba2ea" target="_blank">December 2010</a></p>

		<p><a href="http://us1.campaign-archive.com/?u=c8b5366ff36890ecbc3bf00cc&id=aa18856cc4" target="_blank">November 2010</a></p>

		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=100643ba08" target="_blank">October 2010</a></p>

		<p><a href="http://us1.campaign-archive.com/?u=c8b5366ff36890ecbc3bf00cc&id=5419d9f9b2" target="_blank">September 2010</a></p>

		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=b33558f2d2&e=" target="_blank">August 2010</a></p>

		<p><a href="http://us1.campaign-archive1.com/?u=c8b5366ff36890ecbc3bf00cc&id=4b775846a4&e=8e993d3d56" target="_blank">July 2010</a></p>

	</div>
</div>