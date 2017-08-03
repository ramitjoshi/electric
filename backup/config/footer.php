 </div>
    <div class="script">
        
		<!-- wow-->
        <script type="text/javascript" src="assets/js/wow.js"></script>
        <script type="text/javascript" src="assets/js/form.js"></script>
        <script type="text/javascript" src="assets/js/jquery.validate.js"></script>
        <script type="text/javascript" src="assets/js/boot_filestyle.js"></script>
		<script src="assets/js/bootstrap-select.js "></script>
		<script src="assets/js/lightbox.js"></script>
		<script src="assets/js/toastr.js"></script> 
        <script type="text/javascript" src="assets/js/custom.js"></script>
		<script type="text/javascript"> 
            var wow = new WOW({
                boxClass: 'wow',
                animateClass: 'animated',
                offset: 140,
                mobile: true,
                live: true,
                callback: function (box) {},
                scrollContainer: null
            });
            wow.init();
        </script>
		 <!-- IE Smooth Scrolling -->
       <script type="text/javascript">
           if (navigator.userAgent.match(/Trident\/7\./)) { // if IE
               jQuery('body').on("mousewheel", function () {
                   // remove default behavior
                   event.preventDefault();

                   //scroll without smoothing
                   var wheelDelta = event.wheelDelta;
                   var currentScrollPosition = window.pageYOffset;
                   window.scrollTo(0, currentScrollPosition - wheelDelta);
               });
           }
       </script>
	   <script>
            jQuery('.filestyle').filestyle({
				buttonText: ' Upload'
			});
			
			jQuery(document).ready(function()
			{
				jQuery('table').wrap('<div class="responsive-table"></div>');
			});
			
        </script>
		<script>
		
		function check_login_time()
		{
			jQuery.ajax({type: "POST",
			url: "handler.php",
			data: "action=Checklogintime",   
			success:function(result)
			{
				//alert(result);
				setTimeout(check_login_time, 150000);
			}, 
			error:function(e){
				console.log(e);
			}	 
			}); 
		}
		
		
		jQuery(document).ready(function()
		{
			setTimeout(check_login_time, 1000);
		});
		</script>
    </div>
</body>

</html>