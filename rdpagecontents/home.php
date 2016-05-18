<body class='index'>

<?php layout_get_header_main($adminloggedin,$rguserloggedin); ?>

<div id="wrapper">
   
	<div id="mask"> 

    <div id='sections_container'>
    	
		<div id="section_introduction" class="sections"> 
       		<a id='section_anchor_top' name='top'></a><div id='top'></div>
			<a id='section_anchor_introduction' name="section_introduction"></a> 
            <div class='section_title' id='section_introduction_title'>Introduction</div>
			<div class="section_content" id='section_introduction_content'>          
            	Retina destruction is an online tool that helps you build a print-friendly, image-based portfolio of your selected work.
                Each user has a section to showcase his work, which is easily customizable.
                Every one of the portfolios is available online.
			</div> 
		</div><!--section_information-->
		
		<div id="section_instructions" class="sections"> 
			<a id='section_anchor_instruction' name="section_instructions"></a> 
            <div class='section_title' id='section_instructions_title'>Instructions</div>
			<div class="section_content" id='section_instructions_content'>
            Ut auctor nulla eu dui sodales egestas ac in dui. Vestibulum lobortis felis quis purus placerat vitae eleifend nunc mattis. Aliquam erat volutpat. Cras interdum nisi ac risus aliquet venenatis. Fusce blandit risus tortor, in volutpat nulla. Etiam congue sapien in tellus pretium interdum. Aliquam dignissim laoreet arcu ut pellentesque. Suspendisse vitae sapien at velit vehicula imperdiet luctus sed justo. Mauris quis felis libero, sit amet semper velit. Nunc pharetra, nunc in imperdiet aliquet, tellus massa fringilla dolor, eget pharetra tortor nisi vitae orci. Suspendisse sit amet massa eget velit ornare iaculis. Donec volutpat ornare orci sit amet ultricies. Donec et lacinia libero. Donec mollis nulla quis quam fermentum iaculis. Quisque consequat, eros nec hendrerit gravida, diam nulla dignissim nunc, consequat commodo dolor mauris eu nisi. Praesent in magna erat. Pellentesque magna lectus, faucibus et ultricies in, gravida at enim. In vitae purus justo. Nullam pellentesque, nulla ut aliquam laoreet, ipsum dui pretium tortor, id elementum purus dui et nisi. Aliquam rhoncus imperdiet tellus quis laoreet.
			</div> 
		</div><!--section_instructions-->
		
		<div id="section_about" class="sections"> 
			<a id='section_anchor_about' name="section_about"></a>
            <div class='section_title' id='section_about_title'>About</div>
			<div class="section_content" id='section_about_content'>
            Morbi faucibus lectus sed nibh eleifend vel tristique velit varius. Nullam nec suscipit enim. Vestibulum bibendum metus in odio ornare vel facilisis metus convallis. Aenean pulvinar lorem id augue vestibulum sodales. Suspendisse eu felis a sapien luctus commodo id non felis. Vestibulum lobortis turpis vitae risus porttitor sit amet fringilla dui molestie. Sed in tortor ante, nec congue ante. In pellentesque egestas risus ac posuere. Quisque eu velit nunc. Maecenas id mauris nibh. Vestibulum porta massa eu nunc malesuada dictum. In sit amet neque justo. Donec nunc ipsum, vulputate id egestas vel, dignissim at augue. Nullam vel urna nunc, nec vulputate diam. Vivamus accumsan libero at ipsum ultricies placerat. Nulla facilisi. Ut mollis, nisl sed tempor vehicula, nibh felis fermentum arcu, eu faucibus tellus velit quis velit.
			</div> 
		</div><!--section_about-->
     	
        
		<div id="section_contact" class="sections"> 
			<a id='section_anchor_contract' name="section_contact"></a>
            <div class='section_title' id='section_contact_title'>Contact</div>
			<div class="section_content" id='section_contact_content'>
Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vivamus posuere, lorem non consectetur tempor, sapien massa aliquam lectus, mattis iaculis nibh nisl et magna. Suspendisse fermentum sapien eget tortor sagittis posuere. Praesent a nisl dui. Sed scelerisque sodales lectus eu mattis.
			</div> 
		</div><!--section_contact-->
		
		<div id="section_artistsindex" class="sections"> 
			<a id='section_anchor_artistsindex' name="section_artistsindex"></a>
            <div class='section_title' id='section_artistsindex_title'>Artists Index</div>
			<div class="section_content" id='section_artistsindex_content'>
Quisque vel metus magna. In vestibulum tempor tellus, sed vulputate tortor varius at. Mauris interdum mattis velit, sit amet fringilla magna ullamcorper id. Donec eleifend adipiscing sapien, vitae volutpat quam dignissim faucibus. Sed consequat accumsan lorem in eleifend. Nam quis justo et lectus imperdiet ultrices. Nulla posuere, magna at volutpat auctor, libero tortor adipiscing ipsum, sit amet suscipit nisl ipsum non massa
            </div> 
		</div><!--section_artists_index-->


		<div id="section_featuredartists" class="sections"> 
			<a id='section_anchor_featuredartists' name="section_featuredartists"></a>
            <div class='section_title' id='section_featuredartists_title'>Featured Artists</div>
			<div class="section_content" id='section_featuredartists_content'>
Etiam pellentesque enim in elit iaculis rutrum. Maecenas cursus aliquet ipsum in ornare. Donec enim magna, vehicula ac euismod eget, placerat et erat. Donec a imperdiet arcu. Phasellus semper, sapien non faucibus congue, nisl lectus fermentum elit, eu iaculis lectus ante eget massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
            </div> 
		</div><!--section_featured_artists-->

		<? if( !($adminloggedin) && !($rguserloggedin) ){?>
            <div id="section_registration" class="sections"> 
                <a id='section_anchor_registration' name="section_registration"></a>
                <div class='section_title' id='section_registration_title'>Registration</div>
                <div class="section_content" id='section_registration_content'>
                <?php layout_get_registerform($adminloggedin,$rguserloggedin); ?>
                </div> 
            </div><!--section_registration-->
    
            <div id="section_signin" class="sections"> 
                <a id='section_anchor_signin' name="section_signin"></a>
                <div class='section_title' id='section_signin_title'>Sign-In</div>
                <div class="section_content" id='section_signin_content'>
                <?php layout_get_loginform($adminloggedin,$rguserloggedin); ?>
                <?php layout_get_forgotpasswordform($adminloggedin,$rguserloggedin); ?>
                </div> 
            </div><!--section_signin-->
		<? } ?>
    	
        <div id="section_terms" class="sections">
			<a id='section_anchor_terms' name="section_terms"></a>
            <div class='section_title' id='section_terms_title'>Terms of agreement</div>
			<div class="section_content" id='section_terms_content'>
				<span class='section_content_page' id='section_content_page_terms'></span>
            </div>       
        </div><!--section_terms-->
        
        
        
    </div><!--sections_container-->

	</div><!--mask-->

</div><!--wrapper-->

<?php layout_get_footer($adminloggedin,$rguserloggedin); ?>

</body>
<div id='eddie' class='displaynone'>main</div>
<div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div><!--clint-->