<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<footer class="text-center" style="padding-bottom:25px;">
	<div class="footer-above">
		<div class="container">
			<div class="row">
				<div class="footer-col col-lg-12" style="letter-spacing: 1px;">
					</br>
					<ul class="list-inline">
						<li>
							<a href="#" class="btn-social btn-outline hvr-pulse-grow"><i class="fa fa-fw fa-facebook fa-lg"></i></a>
						</li>
						<li>
							<a href="#" class="btn-social btn-outline hvr-pulse-grow"><i class="fa fa-fw fa-google-plus fa-lg"></i></a>
						</li>
						<li>
							<a href="#" class="btn-social btn-outline hvr-pulse-grow"><i class="fa fa-fw fa-twitter fa-lg"></i></a>
						</li>
						<li>
							<a href="#" class="btn-social btn-outline hvr-pulse-grow"><i class="fa fa-fw fa-linkedin fa-lg"></i></a>
						</li>
					</ul>
					
					<i style="color:#098;" class="fa fa-copyright fa-lg " aria-hidden="true"></i>
					<div style="letter-spacing: 1px; display:inline; font-size:11px;">
						Copyright. All Rights Reserved | 
						<i style="color:#788;
							font-size:13px;
							padding:4px;
							font-weight:bold;"> 
							DCSA - GRI-DU 
						</i> <?= date("Y",now()) ?>.
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!--</div>-->
</section> <!--    container section close -->
<script type="text/javascript">
$(document).ready(function () {
    /*Disable cut copy paste
    $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });
   */
    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});

/********** loader  *******
var myVar;
function myFunction() {
    myVar = setTimeout(showPage, 1000);
	
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
 
}
*/

/*** snack bar *****/

 
</script>
</body>
</html>