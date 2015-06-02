<form action="{$requestUri}" method="post" name="form1">
	<fieldset>
		<legend><img src="../img/admin/slip.gif" />&nbsp;{l s='View / Manage Testimonials' mod='pk_testimonials'}</legend>
		<div style="display:none;" id="controls">
			<input  class="button" name="Enable" value="{l s='Enable Selected' mod='pk_testimonials'}" type="submit" type="submit" style="width: 200px;"/>
			<input class="button"  name="Disable" value="{l s='Disable Selected' mod='pk_testimonials'}" type="submit" type="submit" style="width: 200px;"/>
			<input class="button"  name="Delete" onClick="return confirmSubmit('{l s='Okay to Delete this Testimonial(s)?' mod='pk_testimonials'}')" value="{l s='Delete Selected' mod='pk_testimonials'}" type="submit" type="submit" style="width: 200px;"/>
			<input class="button"  name="Update" value="{l s='Update Selected' mod='pk_testimonials'}" type="submit" type="submit" style="width: 200px;"/>
		</div>
		<table id="box-table-a">
			<th>{l s='Select' mod='pk_testimonials'}</th> <!-- Select Column Header-->
			<th>{l s='Status' mod='pk_testimonials'}</th> <!-- Status Column Header-->
			<th>{l s='Name' mod='pk_testimonials'}</th> <!-- Name Column Header-->
			<th>{l s='Email' mod='pk_testimonials'}</th> <!-- Name Column Header-->
			<th>{l s='Date' mod='pk_testimonials'}</th> <!-- Date Column Header-->
			<th style="width:50%">{l s='Testimonial' mod='pk_testimonials'}</th> <!-- Testimonial  Column Header-->
			{if isset($testimonials)}			  
				{foreach from=$testimonials item=nr}				  
				{if $nr}
					<tr>
						<td> <!--Check Box -->
							<INPUT class="testimonialselect" TYPE=checkbox VALUE="{$nr.testimonial_id}" NAME="moderate[]">
						</td>
						<td> <!-- Status Column -->
							<span class="{(($nr.status|lower == "disabled") ? 'disabled' : 'enabled')}">{$nr.status}</span>
						</td>
						<td> <!-- Name Column -->
							{$nr.testimonial_submitter_name}
						</td>
						<td> <!-- Name Column -->
							{$nr.testimonial_submitter_email}
						</td>
						<td> <!-- Date Column -->
							{$nr.date_added|strip_tags|date_format:"%H:%M:%S on %B %e, %Y"}
						</td>
						<td> <!-- Testimonial Column -->
							<textarea style="width:100%; height:100px" name="testimonial_main_message_{$nr.testimonial_id}" > {$nr.testimonial_main_message} </textarea>
						</td>
					</tr>
					{/if}
				{/foreach}
			{else}
				<tr><td colspan="6">{l s='No Testimonials Yet' mod='pk_testimonials'}</td></tr>
			{/if}
		</table>
	</fieldset>
</form>
