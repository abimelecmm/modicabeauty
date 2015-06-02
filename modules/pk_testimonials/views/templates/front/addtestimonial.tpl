<!-- Block testimonial module -->
{capture name=path}
    {l s='Add Testimonial' mod='pk_testimonials'}
{/capture}
<h1 class="page-heading">
    {l s='Add Testimonial' mod='pk_testimonials'}
</h1>
<span class="subtitle">{l s='We welcome your testimonials - please enter yours using the form below' mod='pk_testimonials'}</span>
<div id="block_testimonials_submit">
  <form class="testimonialForm custom-inputs" id="testimonialForm" name="testimonialForm" method="post" enctype="multipart/form-data" action="" >
    <fieldset>
      <ol>
        <li class="testim-name form-group">
          <label for="name" class="i-name f-label">{l s='Name' mod='pk_testimonials'}<em>*</em></label>
          <input name="testimonial_submitter_name"  value="" id="testimonial_submitter_name" class="required form-control" maxlength="20" type="text" /></li>
        <li class="testim-email">
          <label for="name" class="i-email f-label">{l s='Email' mod='pk_testimonials'}<em>*</em></label>
          <input name="testimonial_submitter_email"  value="" id="testimonial_submitter_email" class="required form-control" maxlength="40" type="email" /></li>
        <li class="testim-summary">
          <label for="testimonial_title" class="i-other f-label">{l s='Summary' mod='pk_testimonials'}<em>*</em></label>
          <input name="testimonial_title" value="" id="testimonial_title" class="required form-control"  maxlength="40" type="text" /></li>
        <li class="testim-body">
          <label for="testimonial_main_message" class="i-message t-label">{l s='Your Testimonial' mod='pk_testimonials'}</label>
          <textarea cols="33" rows="5" name="testimonial_main_message" id="testimonial_main_message" class="required form-control" maxlength="240"></textarea></li>
      </ol>
      <div class='alert'></div>
    </fieldset>
    {if $recaptcha}
      <fieldset>
        <div id="captcha_body"></div>
      </fieldset>      
    {/if}
    <input type="submit" class="button" name="testimonial" value="{l s='Submit Testimonial' mod='pk_testimonials'}"  />
  </form>
</div>
{strip}
{addJsDef addtestimonial=true}
{addJsDef recaptcha=$recaptcha}
{addJsDef captchakey=$captchakey}
{addJsDef base_dir=$base_dir}
{addJsDef http_host=$http_host}
{addJsDefL name=field_error}{l s='Please fill in all the required fields.' mod='pk_productsCarousel_single' js=1}{/addJsDefL}
{addJsDefL name=captcha_error}{l s='Please type captcha words correctly and try again!' mod='pk_productsCarousel_single' js=1}{/addJsDefL}
{addJsDefL name=success}{l s='Your message has been sent and will be published soon.' mod='pk_productsCarousel_single' js=1}{/addJsDefL}
{addJsDefL name=DB_error}{l s='Can\'t add testimonial to DB.' mod='pk_productsCarousel_single' js=1}{/addJsDefL}
{addJsDefL name=other}{l s='Something is wrong. Please try again.' mod='pk_productsCarousel_single' js=1}{/addJsDefL}
{/strip}
<!-- /Block testimonial module -->