{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='My account'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe}
    </span>
    <span class="navigation_page">
        {l s='Your personal information'}
    </span>
{/capture}
<div class="wht_bg ident">
    <div class="wrap_indent">
    <h1 class="page-subheading">
        {l s='Your personal information'}
    </h1>
    
    {include file="$tpl_dir./errors.tpl"}
    
    {if isset($confirmation) && $confirmation}
        <p class="alert alert-success">
            {l s='Your personal information has been successfully updated.'}
            {if isset($pwd_changed)}<br />{l s='Your password has been sent to your email:'} {$email}{/if}
        </p>
    {else}
        <p class="info-title">
            {l s='Please be sure to update your personal information if it has changed.'}
        </p>
        <p class="required">
            <sup>*</sup>{l s='Required field'}
        </p>
        <form action="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" method="post" class="std">
            <div class="clearfix form-group">
                <label>{l s='Title'}</label>
                {foreach from=$genders key=k item=gender}
                    <div class="radio-inline">
                        <label for="id_gender{$gender->id}" class="top">
                        <input type="radio" name="id_gender" id="id_gender{$gender->id}" value="{$gender->id|intval}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id}checked="checked"{/if} />
                        {$gender->name}</label>
                    </div>
                {/foreach}
            </div>
            <div class="required form-group">
                <label for="firstname" class="required">
                    {l s='First name'} 
                </label>
                <input class="is_required validate form-control" data-validate="isName" type="text" id="firstname" name="firstname" value="{$smarty.post.firstname}" />
            </div>
            <div class="required form-group">
                <label for="lastname" class="required">
                    {l s='Last name'} 
                </label>
                <input class="is_required validate form-control" data-validate="isName" type="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" />
            </div>
            <div class="required form-group">
                <label for="email" class="required">
                    {l s='E-mail address'} 
                </label>
                <input class="is_required validate form-control" data-validate="isEmail" type="email" name="email" id="email" value="{$smarty.post.email}" />
            </div>
            <div class="form-group">
                <label>
                    {l s='Date of Birth'}
                </label>
                <div class="row">
                    <div class="col-xs-4">
                        <select name="days" id="days" class="form-control">
                            <option value="">-</option>
                            {foreach from=$days item=v}
                                <option value="{$v}" {if ($sl_day == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
                            {/foreach}
                        </select>

                    </div>
                    <div class="col-xs-4">
                        <select id="months" name="months" class="form-control">
                            <option value="">-</option>
                            {foreach from=$months key=k item=v}
                                <option value="{$k}" {if ($sl_month == $k)}selected="selected"{/if}>{l s=$v}&nbsp;</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <select id="years" name="years" class="form-control">
                            <option value="">-</option>
                            {foreach from=$years item=v}
                                <option value="{$v}" {if ($sl_year == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="required form-group">
                <label for="old_passwd" class="required">
                    {l s='Current Password'} 
                </label>
                <input class="is_required validate form-control" type="password" data-validate="isPasswd" name="old_passwd" id="old_passwd" />
            </div>
            <div class="password form-group">
                <label for="passwd">
                    {l s='New Password'}
                </label>
                <input class="is_required validate form-control" type="password" data-validate="isPasswd" name="passwd" id="passwd" />
            </div>
            <div class="password form-group">
                <label for="confirmation">
                    {l s='Confirmation'}
                </label>
                <input class="is_required validate form-control" type="password" data-validate="isPasswd" name="confirmation" id="confirmation" />
            </div>
            {if $newsletter}
                <div class="checkbox">
                    <label for="newsletter">
                        <input type="checkbox" id="newsletter" name="newsletter" value="1" {if isset($smarty.post.newsletter) && $smarty.post.newsletter == 1} checked="checked"{/if}/>
                        {l s='Sign up for our newsletter!'}
                    </label>
                </div>
                <div class="checkbox">
                    <label for="optin">
                        <input type="checkbox" name="optin" id="optin" value="1" {if isset($smarty.post.optin) && $smarty.post.optin == 1} checked="checked"{/if}/>
                        {l s='Receive special offers from our partners!'}
                    </label>
                </div>
            {/if}
            <div class="form-group">
                <input type="submit" name="submitIdentity" class="btn btn-default button button-medium" value="{l s='Save'}" />
            </div>
            <p id="security_informations" class="text-right">
                <i>{l s='[Insert customer data privacy clause here, if applicable]'}</i>
            </p>
        </form> <!-- .std -->
    {/if}
</div>
</div>