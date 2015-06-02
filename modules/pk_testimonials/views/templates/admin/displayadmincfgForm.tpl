<form action="{$requestUri}" method="post" id="testimonialCfg" enctype="multipart/form-data">
  <fieldset>
  <legend><img src="../img/admin/cog.gif" />&nbsp;{l s='Configuration' mod='pk_testimonials'}</legend>
    <div class="margin-form">
      <label>{l s='Use ReCaptcha Anti Spam' mod='pk_testimonials'}</label><br/>
				<input type="radio" name="reCaptcha" id="recaptcha_on" value="1" {if $recaptcha eq 1}checked="yes" {/if}/>
				<label class="t" for="recaptcha_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="reCaptcha" id="recaptcha_off" value="0" {if $recaptcha eq 0}checked="yes" {/if} />
				<label class="t" for="recaptcha_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" />
      </label>
    </div>
    <div class="margin-form">
      <div>
        <strong>{l s='Create a reCAPTCHA key here: ' mod='pk_testimonials'}<a href="https://www.google.com/recaptcha">https://www.google.com/recaptcha</a></strong>
      </div>                              
    </div><br/>
    <div class="margin-form">
      <label>{l s='ReCaptcha Public Key' mod='pk_testimonials'}</label>
      <input type="text" name="recaptchaPub" value="{$recaptchaPub}" />
    </div>
    <div class="margin-form">
      <label>{l s='ReCaptcha Private Key' mod='pk_testimonials'}</label>
      <input type="text" name="recaptchaPriv" value="{$recaptchaPriv}" />
    </div>
    <hr />
    <div class="margin-form">
      <label>{l s='# of testimonials per page' mod='pk_testimonials'}</label>
      <input type="text" name="perPage" value="{$recaptchaPerpage}" />
    </div>
    <div class="margin-form">
      <label>{l s='# of testimonials in column' mod='pk_testimonials'}</label>
      <input type="text" name="perBlock" value="{$recaptchaPerBlock}" />
    </div><br/>
    <div class="margin-form">
      <label>{l s='Show background Image' mod='pk_testimonials'}</label><br/>
      <input type="radio" name="displayImage" id="displayImage_on" value="1" {if $displayImage eq 1}checked="yes" {/if}/>
    <label class="t" for="displayImage_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" name="displayImage" id="displayImage_off" value="0" {if $displayImage eq 0}checked="yes" {/if} />
      <label class="t" for="displayImage_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
    </div><!--
    <div class="margin-form">
      <label>{l s='Maximimum Image size in KiloBytes (KB)' mod='pk_testimonials'}</label>
      <input type="text" name="maximagesize" value="{$maximagesize}" />
    </div>--><br/>
    <input type="file" name="testimonialsbg" id="testimonialsbg"><br/>
    <strong>{l s='Recommended dimension is: 1920x800px' mod='pk_testimonials'}</strong><br/>
    <img src="{$testimonial_bg}" alt="" style="width:300px; height: auto;" />
    <hr/>
    <div class="margin-form">
      <input type="submit" value="{l s='Save' mod='pk_testimonials'}" name="submitConfig" class="button" />	
    </div>
  </fieldset>
</form><br />
<fieldset>
  <legend>{l s='Backup Testimonials' mod='pk_testimonials'}</legend>
  <p>{l s='Use this to create backup of your testimonials in a CSV File.  This will create a file called backup.csv in this /modules/pk_testimonials directory' mod='pk_testimonials'}</p>
  <form id="backupform" action="{$requestUri}" method="post" name="backupform" >
    <input class="button" name="Backup" value="{'Backup'}" type="submit" type="submit" style="width: 200px;"/>
    {if $backupfileExists >0}
      <br><br> <span style="font-weight:bold"><a href="{$base_dir}modules/pk_testimonials/backup.csv" >{l s='Download Backup File' mod='pk_testimonials'}</a></span>
    {/if}
  </form>
</fieldset>
<br/><br/><br/>