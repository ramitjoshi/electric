<?php 
if($_SESSION['user_id']!='')
{
get_header(); ?>
		<?php get_sidebar(); ?>
		<div class="rt_sidebar pull-left cstmr_rt">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="" class="custom">Dashboard</a>
                    <a href="" class="custom">New Customer</a>
                </div>
                <div class="btns_rt pull-right">
                    <input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="close_mn display_block">
                <h4 class="close_title">ADD STAFF</h4>
                <div class="close_container custmr_edt_cnt">
                    <form name="add_staff" id="add_staff" action="" method="post">
						<div class="cstm-wid top_inpt">
							<ul> 
								<li>
									<input type="text" name="fname" placeholder="First Name">
								</li>
								<li>
									<input type="text" name="lname" placeholder="Last Name">
								</li>
								<li>
									<input type="text" name="email" placeholder="Email addres">
								</li>
								<li>
									<input type="text" name="phone" placeholder="Phone">
								</li>
								<li>
									<input type="text" name="username" placeholder="Username">
								</li>
								<li>
									<input type="password" name="user_pass" placeholder="Password">
								</li>
							</ul>
						</div>
						<div class="btn_deit">
							<button type="submit" class="custom edit_cstm">Save</button>
							<img src="assets/images/loader.gif" id="addSaff_loader" style="display:none;" />
						</div> 
						<input type="hidden" name="action" value="AddStaff">
					</form>
					<div class="result"></div>
                </div>

                <div class="edit_table">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th style="width: 35px;"></th>
                                <th style="width: 196px;">First Name</th>
                                <th style="width: 253px;">Last Name</th>
                                <th style="width: 376px;">Email Address</th>
                                <th style="width: 245px;">Telephone</th>
                                <th style="text-align: center; width: 101px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>Terry</td>
                                <td>Gaughan</td>
                                <td>algonquinelectrical@gmail.com</td>
                                <td>(705) 380-0752</td>
                                <td style="text-align: right;padding-right: 2.2%;"><a href="" class="custom edit_cstm">View</a></td>
                            </tr>

                        </tbody>
                    </table>

                </div>


            </div>


        </div>
		
<?php get_footer(); 
}
else
{	
?>
<script>window.location.href="<?php echo SITE_URL(); ?>?section=login";</script>
<?php 
}
?>		
