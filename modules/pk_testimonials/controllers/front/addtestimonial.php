<?php

class pk_testimonialsAddTestimonialModuleFrontController extends ModuleFrontController {

  public function __construct()
  {
    parent::__construct();
    $this->context = Context::getContext();
    require_once(_PS_MODULE_DIR_.'pk_testimonials/pk_testimonials.php');
    require_once(_PS_MODULE_DIR_.'pk_testimonials/recaptchalib.php');
  }

  public function initContent() {
      
    parent::initContent();

    $blockTestimonial = new pk_testimonials();

    $this->context->smarty->assign(array(
      'recaptcha' => intval(Configuration::get('TESTIMONIAL_CAPTCHA')),
      'captchakey' => Configuration::get('TESTIMONIAL_CAPTCHA_PUB'),
      'base_dir' => __PS_BASE_URI__,
      'http_host' => $_SERVER['HTTP_HOST']
    ));        

    $this->setTemplate('addtestimonial.tpl');
  }
}
?>