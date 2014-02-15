{extends file="content.tpl"}

{block name=title}Lender Account Creation{/block}
{block name=classname}form_page{/block}
{block name=description}{/block}

{block name=content}
<h3>We're so glad you're here.</h3>
<p>When you support microfinance through social investments in a Zidisha entrepreneur, you not only fund a microfinance project, but you also fight poverty in that entrepreneur's family and community.</p>     

<div id="success" class="alert_box success" style="display:none"><p>Form was submitted.</p></div>

<form action="ajaxform.php" method="post" name="contact" id="contact">
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
    <label class="label">Country <span class="req">*</span></label>
    <select id="lcountry" class="custom_select" name="lcountry" >
          <option value='US'>United States</option>
          <option value='AF' >Afghanistan</option>
          <option value='AI' >Anguilla</option>
          <option value='AR' >Argentina</option>
          <option value='AM' >Armenia</option>
          <option value='AW' >Aruba</option>
          <option value='AU' >Australia</option>
          <option value='AT' >Austria</option>
          <option value='AZ' >Azerbaijan</option>
          <option value='BS' >Bahamas</option>
          <option value='BH' >Bahrain</option>
          <option value='BD' >Bangladesh</option>
          <option value='BB' >Barbados</option>
          <option value='BY' >Belarus</option>
          <option value='BE' >Belgium</option>
          <option value='BZ' >Belize</option>
          <option value='BJ' >Benin</option>
          <option value='BM' >Bermuda</option>
          <option value='BT' >Bhutan</option>
          <option value='BO' >Bolivia</option>
          <option value='BV' >Bouvet Islands</option>
          <option value='BR' >Brazil</option>
          <option value='IO' >British Indian Ocean Territory</option>
          <option value='VI' >British Virgin Islands</option>
          <option value='BN' >Brunei</option>
          <option value='BG' >Bulgaria</option>
          <option value='BF' >Burkina Faso</option>
          <option value='BI' >Burundi</option>
          <option value='KH' >Cambodia</option>
          <option value='CM' >Cameroon</option>
          <option value='CA' >Canada</option>
          <option value='CV' >Cape Verde</option>
          <option value='KY' >Cayman Islands</option>
          <option value='CF' >Central African Republic</option>
          <option value='TD' >Chad</option>
          <option value='CL' >Chile</option>
          <option value='CN' >China</option>
          <option value='CO' >Colombia</option>
          <option value='KM' >Comoros</option>
          <option value='CG' >Congo</option>
          <option value='CR' >Costa Rica</option>
          <option value='CI' >Cote D'Ivorie</option>
          <option value='HR' >Croatia</option>
          <option value='CY' >Cyprus</option>
          <option value='CZ' >Czech Republic</option>
          <option value='DK' >Denmark</option>
          <option value='DJ' >Djibouti</option>
          <option value='DM' >Dominica</option>
          <option value='DO' >Dominican Republic</option>
          <option value='EG' >Egypt</option>
          <option value='SV' >El Salvador</option>
          <option value='EC' >Equador</option>
          <option value='GQ' >Equatorial Guinea</option>
          <option value='ER' >Eritrea</option>
          <option value='EE' >Estonia</option>
          <option value='ET' >Ethiopia</option>
          <option value='FK' >Falkland Islands</option>
          <option value='FO' >Faroe Islands</option>
          <option value='FM' >Federated States of Micronesia</option>
          <option value='FJ' >Fiji</option>
          <option value='FI' >Finland</option>
          <option value='FR' >France</option>
          <option value='GF' >French Guiana</option>
          <option value='PF' >French Polynesia</option>
          <option value='GA' >Gabon</option>
          <option value='GM' >Gambia</option>
          <option value='GE' >Georgia</option>
          <option value='DE' >Germany</option>
          <option value='GH' >Ghana</option>
          <option value='GI' >Gibraltar</option>
          <option value='GR' >Greece</option>
          <option value='GL' >Greenland</option>
          <option value='GD' >Grenada</option>
          <option value='GP' >Guadeloupe</option>
          <option value='GU' >Guam</option>
          <option value='GT' >Guatemala</option>
          <option value='GN' >Guinea</option>
          <option value='GW' >Guinea-Bissau</option>
          <option value='GY' >Guyana</option>
          <option value='HT' >Haiti</option>
          <option value='HN' >Honduras</option>
          <option value='HK' >Hong Kong</option>
          <option value='HU' >Hungary</option>
          <option value='IS' >Iceland</option>
          <option value='IN' >India</option>
          <option value='ID' >Indonesia</option>
          <option value='IE' >Ireland</option>
          <option value='IL' >Israel</option>
          <option value='IT' >Italy</option>
          <option value='JM' >Jamaica</option>
          <option value='JP' >Japan</option>
          <option value='JO' >Jordan</option>
          <option value='KZ' >Kazakhstan</option>
          <option value='KE' >Kenya</option>
          <option value='KI' >Kiribati</option>
          <option value='KW' >Kuwait</option>
          <option value='KG' >Kyrgyzstan</option>
          <option value='LA' >Laos</option>
          <option value='LV' >Latvia</option>
          <option value='LB' >Lebanon</option>
          <option value='LS' >Lesotho</option>
          <option value='LR' >Liberia</option>
          <option value='LI' >Liechtenstein</option>
          <option value='LT' >Lithuania</option>
          <option value='LU' >Luxembourg</option>
          <option value='MO' >Macau</option>
          <option value='MG' >Madagascar</option>
          <option value='MW' >Malawi</option>
          <option value='MY' >Malaysia</option>
          <option value='MV' >Maldives</option>
          <option value='ML' >Mali</option>
          <option value='MT' >Malta</option>
          <option value='MH' >Marshall Islands</option>
          <option value='MQ' >Martinique</option>
          <option value='MR' >Mauritania</option>
          <option value='YT' >Mayotte</option>
          <option value='FX' >Metropolitan France</option>
          <option value='MX' >Mexico</option>
          <option value='MD' >Moldova</option>
          <option value='MN' >Mongolia</option>
          <option value='MA' >Morocco</option>
          <option value='MZ' >Mozambique</option>
          <option value='NA' >Namibia</option>
          <option value='NR' >Nauru</option>
          <option value='NP' >Nepal</option>
          <option value='AN' >Neterlands Antilles</option>
          <option value='NL' >Netherlands</option>
          <option value='NC' >New Caledonia</option>
          <option value='NZ' >New Zealand</option>
          <option value='NI' >Nicaragua</option>
          <option value='NE' >Niger</option>
          <option value='NG' >Nigeria</option>
          <option value='MP' >Northern Mariana Islands</option>
          <option value='NO' >Norway</option>
          <option value='OM' >Oman</option>
          <option value='PK' >Pakistan</option>
          <option value='PW' >Palau</option>
          <option value='PA' >Panama</option>
          <option value='PG' >Papua New Guinea</option>
          <option value='PY' >Paraguay</option>
          <option value='PE' >Peru</option>
          <option value='PH' >Philippines</option>
          <option value='PN' >Pitcairn</option>
          <option value='PL' >Poland</option>
          <option value='PT' >Portugal</option>
          <option value='PR' >Puerto Rico</option>
          <option value='QA' >Qatar</option>
          <option value='KR' >Republic of Korea</option>
          <option value='MK' >Republic of Macedonia</option>
          <option value='RE' >Reunion</option>
          <option value='RO' >Romania</option>
          <option value='RU' >Russia</option>
          <option value='RW' >Rwanda</option>
          <option value='ST' >Sao Tome and Principe</option>
          <option value='SA' >Saudi Arabia</option>
          <option value='SN' >Senegal</option>
          <option value='SC' >Seychelles</option>
          <option value='SL' >Sierra Leone</option>
          <option value='SG' >Singapore</option>
          <option value='SK' >Slovakia</option>
          <option value='SI' >Slovenia</option>
          <option value='SB' >Solomon Islands</option>
          <option value='SO' >Somalia</option>
          <option value='ZA' >South Africa</option>
          <option value='ES' >Spain</option>
          <option value='LK' >Sri Lanka</option>
          <option value='SH' >St. Helena</option>
          <option value='KN' >St. Kitts and Nevis</option>
          <option value='LC' >St. Lucia</option>
          <option value='VC' >St. Vincent and the Grenadines</option>
          <option value='SD' >Sudan</option>
          <option value='SR' >Suriname</option>
          <option value='SJ' >Svalbard and Jan Mayen Islands</option>
          <option value='SZ' >Swaziland</option>
          <option value='SE' >Sweden</option>
          <option value='CH' >Switzerland</option>
          <option value='SY' >Syria</option>
          <option value='TW' >Taiwan</option>
          <option value='TJ' >Tajikistan</option>
          <option value='TZ' >Tanzania</option>
          <option value='TH' >Thailand</option>
          <option value='TG' >Togo</option>
          <option value='TO' >Tonga</option>
          <option value='TT' >Trinidad and Tobago</option>
          <option value='TR' >Turkey</option>
          <option value='TM' >Turkmenistan</option>
          <option value='TC' >Turks and Caicos Islands</option>
          <option value='TV' >Tuvalu</option>
          <option value='UG' >Uganda</option>
          <option value='UA' >Ukraine</option>
          <option value='AE' >United Arab Emirates</option>
          <option value='GB' >United Kingdom</option>
          <option value='UY' >Uruguay</option>
          <option value='UZ' >Uzbekistan</option>
          <option value='VU' >Vanuatu</option>
          <option value='VA' >Vatican City</option>
          <option value='VE' >Venezuela</option>
          <option value='VN' >Vietnam</option>
          <option value='EH' >Western Sahara</option>
          <option value='YE' >Yemen</option>
          <option value='YU' >Yugoslavia</option>
          <option value='ZR' >Zaire</option>
          <option value='ZM' >Zambia</option>
          <option value='ZW' >Zimbabwe</option>
    </select>
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