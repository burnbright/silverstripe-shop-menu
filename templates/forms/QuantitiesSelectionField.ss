<div class="quantities_list">
	<% loop DataList.GroupedBy(Group) %>
		<% if Group %>
			<h3 class="quantities_list_groupheading">$Group.Title</h3>
		<% end_if %>
		<div class="quantities_list_groupedchildren">
			<% loop Children %>
				<div class="quantities_item">
					<label class="quantities_item_label" for="$Top.Name[$ID]">
						<input type="number" name="$Top.Name[$ID]" value="$CartItem.Quantity" size="3" min="5" class="quantities_item_quantity" placeholder="0" <% if not Product.canPurchase %>disabled="disabled"<% end_if %>>
						$Title
					</label>
				</div>
			<% end_loop %>
		</div>
	<% end_loop %>
</div>
