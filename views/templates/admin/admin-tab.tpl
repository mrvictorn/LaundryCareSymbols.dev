<div class="panel product-tab">
	<h3>{l s='Laundry Care Symbols' mod='laundrycaresymbols'}:</h3>
	<div class="form-group">
		<div class="col-lg-9 col-lg-offset-3">
			{foreach name=outer key=cGroup item=arrSymb from=$allLaundryCareSymbols}
				<div class="row">
					<h4>{l s=$cGroup mod='laundrycaresymbols'}</h4>
					<div class="col-lg-12">
						<select multiple id="{$cGroup}">
							{foreach item=symbol from=$arrSymb}
								<option value="{$symbol}"><span class="icon {$symbol}"></span></option>
							{/foreach}
						</select>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
	</div>
</div>
<div class="clear">&nbsp;</div>

<script type="text/javascript">
{literal}

{/literal}
$(function() {
    

});
</script>