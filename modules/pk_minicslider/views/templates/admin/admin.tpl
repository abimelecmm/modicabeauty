<script type="text/javascript">
    $(document).ready(function() {

        // Sortable
        $("ul.languages").sortable({
            opacity: 0.6,
            cursor: "move",
            update: function(event, ui) {
                var list = $(this);
                var number;
                var response;
                $.getJSON(
                    "{$slider.sortUrl}", 
                    {ldelim}slides: $(this).sortable("serialize"){rdelim}, 
                    function(response){
                        if(response.success == "true"){
                            showResponse($("#fixed_conf"), "{l s='Saved successfull'}");
                            var i = 1;
                            list.children("li").each(function(){
                                number = i;
                                if(i < 10){ 
                                    number = "0"+i; 
                                }
                                $(this).find(".order").text(number);
                                i++;
                            });
                        }else{
                            showResponse($("#fixed_error"), "{l s='Something went wrong, please refresh the page and try again'}"); 
                        }
                  }
                );
            }
        });         
    });
</script>

{if !empty($error)}<div class="error">{$error}</div>{/if}
{if !empty($confirmation)}<div class="conf">{$confirmation}</div>{/if}
<div id="fixed_conf" class="conf response" style="display:none;"><p></p><span class="close">x</span></div>
<div id="fixed_error" class="error response" style="display:none;"><p></p><span class="close">x</span></div>

<div class="slider_admin form-horizontal">
    <div id="slider_header">
        <div id="slider_navigation" class="slider_navigation">
            <a href="#newSlide" id="addNew-button" class="menu">{l s='Add New' mod='pk_minicslider'}</a>
            <a href="#option" id="options-button" class="menu">{l s='Options' mod='pk_minicslider'}</a>
            <div class="clearfix"></div>
        </div>
    </div>
    {include file="{$slider.tpl.options}"}
    {include file="{$slider.tpl.new}"}
    {include file="{$slider.tpl.slides}"}    
    <div id="deleteWindow" class="confirmation_window">
        <div class="conf_header">
            <a href="#" class="close_confirm deny-delete">x</a>
            <h3>{l s='Confirmation' mod='pk_minicslider'}</h3>
        </div>
        <div class="confirmation_content">
            <span class="warning_img"></span>
            <p>{l s='Are you sure? If so than you have to know that the image and all of its data will be deleted! I suggest to use the active state switch, and turn it off.' mod='pk_minicslider'}</p>
        </div>
        <div class="button_box">
            <div class="confirm-b">
                <a href="#" class="confirm-delete">{l s='Yes' mod='pk_minicslider'}</a>
            </div>
            <div class="deny-b">
                <a href="#" class="deny-delete">{l s='No' mod='pk_minicslider'}</a>
            </div>
        </div>
    </div>    
    <div id="sendInfoWindow" class="confirmation_window">
        <div class="conf_header">
            <a href="#" class="close_confirm deny-delete">x</a>
            <h3>{l s='Help Us!' mod='pk_minicslider'}</h3>
        </div>
        <div class="confirmation_content">
            <p>{l s='By clicking to the YES button, you agree to send some basic information to us. This mean we can keep tracking how much active module we have.' mod='pk_minicslider'}</p>
            <p><b>{l s='Don`t worry we`ll be discrete with this information' mod='pk_minicslider'}:</b></p>
            <ul>
                <li>{l s='Domain' mod='pk_minicslider'}: <b>{$slider.info.domain}</b></li>
                <li>{l s='Version' mod='pk_minicslider'}: <b>{$slider.info.version}</b></li>
                <li>{l s='PS Version' mod='pk_minicslider'}: <b>{$slider.info.psVersion}</b></li>
            </ul>
            <form>
                <p>{l s='If you wish to riecive news about our updates, new modules (there will be...) than please fill this out.' mod='pk_minicslider'}</p>
                <div>
                    <label>{l s='Email' mod='pk_minicslider'}:</label>
                    <input type="text" id="sendInfoEmail" name="infoEmail" />
                </div>  
            </form>
            <h3>{l s='Thank you for your help!' mod='pk_minicslider'}</h3>
        </div>
        <div class="button_box">
            <div class="confirm-b">
                <a href="#" id="sendInfo" class="confirm-delete">{l s='Yes' mod='pk_minicslider'}</a>
            </div>
            <div class="deny-b">
                <a href="#" class="deny-delete">{l s='No' mod='pk_minicslider'}</a>
            </div>
        </div>
    </div>  
</div>			