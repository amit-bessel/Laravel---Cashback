<html>
	<head>
		<script src="jquery-sortable.js" type="text/javascript"></script>
		<style>
			body.dragging, body.dragging * {
			  cursor: move !important;
			}
			
			.dragged {
			  position: absolute;
			  opacity: 0.5;
			  z-index: 2000;
			}
			
			table.example tr.placeholder {
			  position: relative;
			  /** More li styles **/
			}
			table.example tr.placeholder:before {
			  position: absolute;
			  /** Define arrowhead **/
			}
		</style>
	</head>
	<body>
	<table class='example'>
		<tr><td>First</td></tr>
		<tr><td>Second</td></tr>
		<tr><td>Third</td></tr>
	</table>
	</body>
</html>
<script>
	$(document).ready(function(){
	alert('a');
	});
</script>