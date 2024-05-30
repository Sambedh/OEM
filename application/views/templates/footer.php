      </div>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
          <div class="pull-right">
						Online Exam by <a href="https://softwebdevelopers.com/" target="_blank">Softweb Developers</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FastClick -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/fastclick/lib/fastclick.js"></script>
    
    <!-- iCheck -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/iCheck/icheck.min.js"></script>

    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url('assests/admin');?>/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url('assests/admin');?>/build/js/custom.min.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>

    <!-- Datatables -->
    <script src="<?php echo base_url('assests/admin');?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('assests/admin');?>/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url('assests/admin');?>/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url('assests/admin');?>/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo base_url('assests/admin');?>/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
		
		<script src="<?php echo base_url('assests/admin');?>/vendors/dropzone/dist/min/dropzone.min.js"></script>

		<script src="<?php echo base_url('assests/select');?>/vanillaSelectBox.js"></script>
		
		<script>
			function change_dp(input){
				if (input.files && input.files[0]) {
								var reader = new FileReader();

								reader.onload = function (e) {
										$('#profile_dp')
												.attr('src', e.target.result)
												.width(225)
												.height(225);
								};
					document.getElementById('submit_dp').style = 'block';
								reader.readAsDataURL(input.files[0]);
						}
			}
		</script>
   
    <script>
			function timeFunctionLong(input) {
					setTimeout(function() {
							input.type = 'text';
					}, 60000);
			}
		</script>

  </body>
</html>
