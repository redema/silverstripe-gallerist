
#Gallerist
{
	display: block;
	<% if GalleristImageWidth %>width: {$GalleristImageWidth}px;<% end_if %>
	<% if GalleristImageHeight %>height: {$GalleristImageHeight}px;<% end_if %>
	overflow: hidden;
}

#Gallerist .GalleristPageItem
{
	display: block;
	<% if GalleristImageWidth %>width: {$GalleristImageWidth}px;<% else %>width: 100%;<% end_if %>
	<% if GalleristImageHeight %>height: {$GalleristImageHeight}px;<% end_if %>
	overflow: hidden;
}

