
<% if GalleristPageItems %>
<div id="Gallerist">
	<% control GalleristPageItems %>
	<% if Image.exists %>
		<a href="$Link#Gallerist" class="GalleristPageItem <% if Link %>Linked<% else %>NotLinked<% end_if %>" style="background: transparent url($Image.getURL) no-repeat;">
			<span class="TextHolder">
				<span class="Title">$Title</span>
				<span class="Description">$Description</span>
			</span>
		</a>
	<% end_if %>
	<% end_control %>
</div>
<% end_if %>

