<?php
/*
 * validation for submit form
 */
?>
<script type="text/javascript">
var front_submission=0;
var recaptcha = 0;
jQuery.noConflict();
jQuery(document).ready(function(){
//<![CDATA[
<?php
global $validation_info;

$js_code = '';
/*$js_code .= '//global vars ';*/
$js_code .= 'var submit_form = jQuery("#submit_form");'; /*form Id*/
$jsfunction = array();
for($i=0;$i<count($validation_info);$i++) {
	$title = $validation_info[$i]['title'];
	$name = $validation_info[$i]['name'];
	$validation_name = $validation_info[$i]['name'];
	$espan = $validation_info[$i]['espan'];
	$type = $validation_info[$i]['type'];
	$search_ctype = $validation_info[$i]['search_ctype'];
	$text = __($validation_info[$i]['text'],'templatic');
	$validation_type = $validation_info[$i]['validation_type'];
	$is_required = $validation_info[$i]['is_require'];
	$is_required_desc = $validation_info[$i]['field_require_desc'];
	if($is_required ==''){
		$is_required = 0;
	}
	$js_code .= '
	dml = document.forms[\'submit_form\'];
	var '.$name.' = jQuery("#'.$name.'"); ';
	$js_code .= '
	var '.$espan.' = jQuery("#'.$espan.'"); 
	';
	if($type=='selectbox' || $type=='checkbox')
	{
		$msg = sprintf(__("%s",'templatic'),$text);
	}else
	{
		$msg = sprintf(__("%s",'templatic'),$text);
	}
	$category_can_select_validation_msg = __("You cannot select more than ",'templatic'); /* message used for while submitting a form with category selected greater than the number of category selection for particular price package. */ 
	$category_can_select_validation_message = __(" categories with this package.",'templatic'); /* message used for while submitting a form with category selected greater than the number of category selection for particular price package.*/ 
	if($type == 'multicheckbox' || $type=='checkbox' || $type=='radio' || $type=='post_categories' || $type=='upload')
	{
		$js_code .= '
		function validate_'.$name.'()
		{
			if("'.$type.'" != "upload")
			  {
				var chklength = jQuery("#submit_form #'.$name.'").length;
			  }
			if("'.$type.'" =="multicheckbox")
			  {
				chklength = document.getElementsByName("'.$name.'[]").length;
			  }
			if("'.$name.'" == "category"){
				chklength = document.getElementsByName("'.$name.'[]").length;
			}
			if("'.$type.'" =="radio")
			  {
				if(!jQuery("input[name='.$name.']:checked").length > 0) {
					flag = 1;
				}
				else
				{
					flag = 0;
				}
			  }
			
			if("'.$type.'" =="upload")
			  {
				  var id_value = jQuery('.$name.').val();
				  var valid_extensions = /(.txt|.pdf|.doc|.xls|.xlsx|.csv|.docx|.rar|.zip|.jpg|.jpeg|.gif|.png)$/i;
				  if(valid_extensions.test(id_value))
				  {
					  
				  }
				  else
				  { ';
				    if($text !='' && $type=='upload'){
					   $umsg = $text;
					}else{
					   $umsg = __("You are uploading invalid file type. Allowed file types are",'templatic')." : txt, pdf, doc, xls, csv, docx, xlsx, zip, rar";
					}
				   $js_code .= 'jQuery("#'.$name.'_error").html("'.$umsg.'");
				   return false;
				  }
			  }
 			var temp	  = "";
			var i = 0;
			if("'.$type.'" =="multicheckbox" || "'.$type.'"=="checkbox")
			  {
			chk_'.$name.' = document.getElementsByName("'.$name.'[]");
			if("'.$name.'" == "category"){
				chk_'.$name.' = document.getElementsByName("'.$name.'[]");
			}			
			if(chklength == 0){
				if ((chk_'.$name.'.checked == false)) {
					flag = 1;	
				} 
			} else {
				var flag      = 0;
				for(i=0;i<chklength;i++) {
					if ((chk_'.$name.'[i].checked == false)) { ';
						$js_code .= '
						flag = 1;
					} else {
						flag = 0;
						break;
					}
				}
			}
			  }
			if(flag == 1)
			{
				if("'.$name.'" == "category"){
					document.getElementById("'.$espan.'").innerHTML = "'.$msg.'";
				}else{
					jQuery("#'.$espan.'").text("'.$msg.'");
				}
				jQuery("#'.$espan.'").addClass("message_error2");
				 return false;
			}
			else{
				if("'.$name.'" == "category"){
					chklength = document.getElementsByName("'.$name.'[]").length;
					cat_count = 0;
					for(i=0;i<chklength;i++) {
						if ((chk_'.$name.'[i].checked == true)) { ';
							$js_code .= '
							cat_count =  cat_count + 1;
						} 
					}
					if(document.getElementById("category_can_select") && document.getElementById("category_can_select").value > 0)
					{
						if(cat_count > document.getElementById("category_can_select").value && chklength > 0)
						{
							
							document.getElementById("category_error").innerHTML = "'.$category_can_select_validation_msg.' "+document.getElementById("category_can_select").value+"'.$category_can_select_validation_message.' ";
							jQuery("#'.$espan.'").addClass("message_error2");
							return false;
						}
					}
				}	
				jQuery("#'.$espan.'").text("");
				jQuery("#'.$espan.'").removeClass("message_error2");
				return true;
			}
		}
	';
	}else {
		$js_code .= '
		function validate_'.$name.'()
		{
			
			';
			if($validation_type == 'email') {
				$js_code .= '
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;				
				if(jQuery("#submit_form #'.$name.'").val() == "" && '.$is_required.') {';
				if($text){
					$emsg = $text;
				}else{
					$emsg = __("Please provide your email address",'templatic');
				}
			
				$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$emsg.'");
					jQuery("#'.$espan.'").addClass("message_error2"); 					
				return false;';
				$js_code .= ' } else if(typeof jQuery("#submit_form #'.$name.'").val()!=="undefined" && '.$is_required.' && !emailReg.test(jQuery("#submit_form #'.$name.'").val().replace(/\s+$/,""))  ) { ';
					if($text){
						$emsg = $text;
					}else{
						$emsg = __("Please provide your email address",'templatic');
					}
					$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$emsg.'");
					jQuery("#'.$espan.'").addClass("message_error2");					
					return false;';
				$js_code .= '
				} else {
					'.$name.'.removeClass("error");
					jQuery("#'.$espan.'").text("");
					jQuery("#'.$espan.'").removeClass("message_error2");
					return true;
				}';
			} if($validation_type == 'phone_no'){
				$js_code .= '
				var phonereg = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12}){1,2}$/;
				if(jQuery("#submit_form #'.$name.'").val() == "" && '.$is_required.') { ';
					$msg = $text;
					$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$msg.'");
					jQuery("#'.$espan.'").addClass("message_error2");					
				return false;';
				$js_code .= ' } else if(!phonereg.test(jQuery("#submit_form #'.$name.'").val()) && jQuery("#submit_form #'.$name.'").val()) { ';
					$msg = __("Enter Valid Phone No.",'templatic');
					$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$msg.'");
					jQuery("#'.$espan.'").addClass("message_error2");					
					return false;';
				$js_code .= '
				} else {
					'.$name.'.removeClass("error");
					jQuery("#'.$espan.'").text("");
					jQuery("#'.$espan.'").removeClass("message_error2");
					return true;
				}';
			}if($validation_type == 'digit'){
				$js_code .= '
				var digitreg = /^[0-9]+$/;
				if(jQuery("#submit_form #'.$name.'").val() == "" && '.$is_required.') { ';
					$msg = trim($text);
				$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$msg.'");
					jQuery("#'.$espan.'").addClass("message_error2");					
				return false;';
				$js_code .= ' } else if(jQuery("#submit_form #'.$name.'").val() && !digitreg.test(jQuery("#submit_form #'.$name.'").val()) && '.$is_required.') { ';
					$msg = __("Values must be all numbers.",'templatic');
					$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$msg.'");
					jQuery("#'.$espan.'").addClass("message_error2");					
					return false;';
				$js_code .= '
				} else {
					'.$name.'.removeClass("error");
					jQuery("#'.$espan.'").text("");
					jQuery("#'.$espan.'").removeClass("message_error2");
					return true;
				}';
			}
			if($type == 'texteditor'){
				$js_code .= 'if(jQuery("#submit_form #'.$name.'").css("display") == "none")
				{
				if(tinyMCE.get("'.$name.'").getContent().replace(/<[^>]+>/g, "") == "") { ';
					$msg = $text;
				$js_code .= $name.'.addClass("error");
					jQuery("#'.$espan.'").text("'.$msg.'");
					jQuery("#'.$espan.'").addClass("message_error2");					
				return false;';
				$js_code .= ' }  else {
					'.$name.'.removeClass("error");
					jQuery("#'.$espan.'").text("");
					jQuery("#'.$espan.'").removeClass("message_error2");					
					return true;
				}
				}else
				{
					if(jQuery("#submit_form #'.$name.'").val() == "")
					{
						jQuery("#'.$espan.'").text("'.$msg.'");
						jQuery("#'.$espan.'").addClass("message_error2");						
						return false;
					}
					else
					{
						jQuery("#'.$espan.'").text("");
						jQuery("#'.$espan.'").removeClass("message_error2");
						return true;
					}
				}';
			}
			if($type == 'image_uploader'){
				$js_code .= 'if(jQuery("#imgarr").val() == "")
				{
					if("'.$msg.'" == "")
					{
						jQuery("#post_images_error").html("'.__("Please upload at least 1 image to the gallery !",'templatic').'");						
						return false;
					}
					else
					{
						jQuery("#post_images_error").html("'.$msg.'");						
						return false;
					}
				}';
			}
			$js_code .= 'if("'.$name.'" == "end_date")';
			{
				$js_code .= '
				{
					 if(jQuery("#submit_form #'.$name.'").val() < jQuery("#submit_form #st_date").val() || jQuery("#submit_form #'.$name.'").val() == "")
					{
						';
						$js_code .= $name.'.addClass("error");
						jQuery("#'.$espan.'").text("'.$msg.'");
						jQuery("#'.$espan.'").addClass("message_error2");						
						return false;
					}
					else
					{
						'.$name.'.removeClass("error");
						jQuery("#'.$espan.'").text("");
						jQuery("#'.$espan.'").removeClass("message_error2");
						return true;
					}
				}';
			}
		$js_code .= 'if((!jQuery("#select_category").val() || jQuery("#select_category").val()=="") && "'.$name.'"=="category")';
		$js_code .= '
			{
				
				jQuery("#'.$espan.'").text("'.$msg.'");
				jQuery("#'.$espan.'").addClass("message_error2");				
				return false;
			}';
		$js_code .= 'if(jQuery("#submit_form #'.$name.'").val() == "" && '.$is_required.')';
		$js_code .= '
			{
				jQuery("#'.$espan.'").text("'.$msg.'");
				jQuery("#'.$espan.'").addClass("message_error2");				
				return false;
			}
			else{
				jQuery("#'.$espan.'").text("");
				jQuery("#'.$espan.'").removeClass("message_error2");				
				return true;
			}
		}
		';
	}
	
	if($type == 'range_type' && $search_ctype=='slider_range' ){
		$js_code .= '
		function validate_'.$name.'_range_type()
		{
			
			var value=jQuery("#submit_form #'.$name.'").val();
			var min_value=jQuery("#submit_form #'.$name.'").attr("min");
			var max_value=jQuery("#submit_form #'.$name.'").attr("max");
			if(parseInt(value) < parseInt(min_value)){	
				jQuery("#'.$espan.'_range_type").remove();
				jQuery("#submit_form #'.$name.'").after("<span id=\"'.$espan.'_range_type\" class=\"message_error2\">'.__('Please select a value that higher than','templatic').' "+min_value+"</span>");				
				return false;
			}else if(parseInt(value) > parseInt(max_value)){				
				jQuery("#'.$espan.'_range_type").remove();
				jQuery("#submit_form #'.$name.'").after("<span id=\"'.$espan.'_range_type\" class=\"message_error2\">'.__('Please select a value that lower than','templatic').' "+max_value+"</span>");				
				return false;
			}else if(isNaN(parseInt(value)) && value!=""){				
				jQuery("#'.$espan.'_range_type").remove();
				jQuery("#submit_form #'.$name.'").after("<span id=\"'.$espan.'_range_type\" class=\"message_error2\">'.__('Please enter a number','templatic').'</span>");				
				return false;
			}else{				
				jQuery("#'.$espan.'_range_type").remove();
				return true;
			}
			
		}';
		/*$js_code .= $name.'.blur(validate_'.$name.'_range_type); ';*/
		/*$js_code .= $name.'.keyup(validate_'.$name.'_range_type); ';*/
		
		$js_code .= $name.'.live("focus blur keyup change", function(event){validate_'.$name.'_range_type()});'."\r\n";
	}
	/*$js_code .= 'On blur ';	*/
	/*$js_code .= $name.'.blur(validate_'.$name.'); ';*/
	/*$js_code .= 'On key press ';*/
	/*$js_code .= $name.'.keyup(validate_'.$name.'); ';*/
	if($name=='category'){
		$js_code .='jQuery("select[name^=category]").bind("blur keyup change click", function(event){validate_'.$name.'()});'."\r\n";	
	}
	$js_code .='jQuery("#submit_form #'.$name.'").bind("blur keyup", function(event){validate_'.$name.'()});'."\r\n";
	 
	
	$jsfunction[] = 'validate_'.$name.'()';
}
if($jsfunction)
{
	$jsfunction_str = implode(' & ', $jsfunction);	
}else{
	$jsfunction_str='';
}

/* custom field validation for video upload option and size validation*/
$js_code .= 'function video_upload_validation(){
                var submit_pid = jQuery("#submit_pid").val();
                var is_video_file_set = jQuery("input[name=_wp_attached_file]").val();
             
                if(jQuery("#submit_form input[name=video_upload]:checked").val() == "upload"){
                    if(!jQuery("#submit_form #upload_video").val() && is_video_file_set ==""){
                        jQuery("#upload_video_error").text("Please upload video");
                        jQuery("#upload_video_error").addClass("message_error2");
                        
                        jQuery("#ptthemes_oembed_error").text("");
                        jQuery("#ptthemes_video_error").text("");
                        return false;
                    }else if(document.getElementById("upload_video").value != ""){
                        var video_uploaded_File = document.getElementById("upload_video");
                        var video_fileSize = video_uploaded_File.files[0].size;
                        if(video_fileSize > video_upload_size){
                            jQuery("#upload_video_error").text("File size is greater than maximum limit.");
                            jQuery("#upload_video_error").addClass("message_error2");
                            return false;
                        }else{
                            jQuery("#upload_video_error").text("");
                            return true;
                        }
                    }else{
                        jQuery("#upload_video_error").text("");
                        return true;
                    }
                }else if(jQuery("#submit_form input[name=video_upload]:checked").val() == "ptthemes_oembed"){
                    if(!jQuery("#submit_form #oembed_video").val() && jQuery("#submit_form #oembed_video").val() == ""){
                        jQuery("#ptthemes_oembed_error").text("Please enter video url");
                        jQuery("#ptthemes_oembed_error").addClass("message_error2");
                        
                        jQuery("#upload_video_error").text("");
                        jQuery("#ptthemes_video_error").text("");
                        return false;
                    }
                    else{
                        jQuery("#ptthemes_oembed_error").text("");
                        return true;
                    }
                }else if(jQuery("#submit_form input[name=video_upload]:checked").val() == "ptthemes_video"){
                    if(!jQuery("#submit_form #video").val() && jQuery("#submit_form #video").val() == ""){
                        jQuery("#ptthemes_video_error").text("Please enter video embet code");
                        jQuery("#ptthemes_video_error").addClass("message_error2");
                        
                        jQuery("#upload_video_error").text("");
                        jQuery("#ptthemes_oembed_error").text("");
                        return false;
                    }
                    else{
                        jQuery("#ptthemes_video_error").text("");
                        return true;
                    }
                }
            }';
            
/* $js_code .= 'On Submitting '; */
/* $js_code .= 'submit_form.submit(function() */
$js_code .= 'jQuery("#continue_submit_from").on("click",function (e)
{
	var id_value = jQuery(".upload_video").val();
	if(jQuery("#submit_form input[name=video_upload]:checked").val()=="upload" && id_value != "" && id_value)
	 { 
		  var valid_extensions = /(.mp4|.webm|.ogg)$/i; 
		  if(!valid_extensions.test(id_value))
		  {
		   jQuery("#upload_video_error").html("You are uploading invalid file type. Allowed file types are : MP4, WebM, and Ogg");
                   jQuery("#upload_video_error").addClass("message_error2"); 
		   return false;
		  }
	 }

	';
	$js_code=apply_filters('submit_form_validation',$js_code);
	if($jsfunction_str !=''){
		$js_code.='if('.$jsfunction_str.' & video_upload_validation())
		{
			jQuery("#common_error").html("");			
			if(recaptcha==1){
				/* Recaptcha validation function to check captcha validation */
				var check_validation=recaptcha_validation();				
				return check_validation;
			}
			can_submit_form = 1;
			return true;
		}
		else
		{
			can_submit_form = 0;
			jQuery("#common_error").html("'.__('Oops! Please make sure you have filled all the mandatory fields.','templatic').'");
			return false;
		}';
	}
	$js_code.='
});
';
$js_code .= '
});';
echo $js_code;
?>
function hide_error(){
	if(jQuery("#term_and_condition").attr("checked"))
	{
		jQuery("#terms_error").html("");
	}
}

if(recaptcha==1){
	function recaptcha_validation(){
		var submit_from = jQuery('form#submit_form').serialize();
		var output;
		jQuery.ajax({
			url:ajaxUrl,
			type:'POST',
			async: false,
			data:submit_from+'&action=submit_form_recaptcha_validation',
		})
		.done(function(results){			
			if(results==1){
				jQuery("#common_error").html('');
				output= true;
			}else{
				jQuery("#common_error").html(results);
				output= false;
			}			
		});	
		return output;
	}
}
//]]>
</script>