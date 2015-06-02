$(document).ready(function() {
  if (recaptcha) {
    Recaptcha.create(captchakey, "captcha_body", {
      theme: "white",
      callback: Recaptcha.focus_response_field
    });
  }
  $("#testimonialForm").submit(function(event){
    event.preventDefault();
    $.ajax({
      url: "//"+http_host+base_dir+"modules/pk_testimonials/queries.php",
      type: "POST",
      data: $(this).serialize(),
      success: function(msg){
        switch(msg) {
          case "field_error":            
            $(".alert").html(field_error).addClass("alert-danger").slideDown("fast");
            break;
          case "captcha_error":
            $(".alert").html(captcha_error).addClass("alert-danger").slideDown("fast");
            break;
          case "success":
            $(".alert").html(success).addClass("alert-success").slideDown("fast");
            $("#testimonial_submitter_name").val("");
            $("#testimonial_submitter_email").val("");
            $("#testimonial_title").val("");
            $("#testimonial_main_message").val("");
            break;
          case "DB_error":
            $(".alert").html(DB_error).addClass("alert-danger").slideDown("fast");
            break;
          default:
            $(".alert").html(other).addClass("alert-danger").slideDown("fast");
        }
        if (recaptcha) 
          Recaptcha.reload();
      }
    });
  });
});