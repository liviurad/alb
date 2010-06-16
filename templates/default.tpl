<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><% title %></title>
<script type="text/javascript" src="<% root_folder %>js/jquery.min.js"></script>
<script type="text/javascript" src="<% root_folder %>js/slimbox2.js"></script>
<script type="text/javascript" src="<% root_folder %>js/alb.js"</script>
<link rel="stylesheet" href="<% root_folder %>css/slimbox2.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<% root_folder %>css/<% stylesheet %>.css" type="text/css" media="screen" />
</head>
<body>
<div id="container">
<div id="message">
</div>
<div id="header">
<p class="gallery_title"><% title %></p>
<p class="gallery_desc"><% gallery_desc %></p>
</div>
<div class="breadcrumb_nav"><% breadcrumb_navigation %>
</div>
<div id="content">
<div class="thumbs">
<% thumbnails %></div>
</div>
<div id="page_nav"><% page_navigation %>
</div>
<div id="footer">
<% footer_text %>
</div>
</div>
</body>
</html>
