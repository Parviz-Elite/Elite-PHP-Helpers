* Title		: Elite PHP Helpers
		* Dec		: An PHP Class That Contains Many Helpful Methods For General Needs.
		* Author	: Parviz-Turk
		* Email 	: Parviz@HackerMail.com - Parviz@Engineer.com
		* Web		: http://Parviz.id.ir/
		* Version	: 4.7.0
		
		
		Add_Image_WaterMark			=> $Image_Path, $WaterMark_Path, $New_Image_Path = '', $MRight = 0, $MBottom = 10
		Add_Text_Watermark			=> $img_Path, $wm_text
		Build_Indexed_File_Path		=> $Path, $FileName
		Build_Token 				=> $String, $build_salt = true
		Check_Len					=> $String, $min, $max
		Clear_Get_Vars 				=> $string, $remove_ls = False
		Correct_Iran_Phone			=> $uNumber
		Decode 						=> $String
		Delete_Cookie				=> $Cookie_Name
		Download_Remote_File 		=> $Remote_File, $Local_Path
		Encode 						=> $String
		Persian_To_English_Num		=> $Number
		Get_Date_Last_Days			=> $days, $format = 'Y-m-d'
		Get_Content 				=> $URL, $Using = 'CURL', $URL_Decode = True
		Get_Excerpt 				=> $Str, $startPos=0, $maxLength=100, $With_etc = true
		Get_JSON 					=> $URL, $Using = 'CURL', $In_Array = False, $URL_Decode = True
		Get_Remote_Image_Size		=> $URL
		Get_Shamsi_Date 			=> $MOD = DIRECTORY_SEPARATOR, $Time2 = False, $Leading_zero = True
		Get_URL_FileName 			=> $URL
		Input_Check					=> $Title, $Val, $minL = 1, $maxL = 1, $Is_Num = False, $Is_Mail = False, $Is_Latin = False
		Is_Json						=> $String
		Is_Number 					=> $Input_String
		Is_Session_Started			=> Null
		Load_CSS 					=> $css_Path
		Load_File 					=> $file_Path, $show_message = true
		Load_JS 					=> $js_Path
		Miladi_To_Shamsi 			=> $gy, $gm, $gd, $mod='', $time2 = False, $leading_zero = False
		English_To_Persian_num		=> $Number
		Post_Redirect				=> $URL, $Data = []
		Post_Request				=> $URL, $Data, $Is_JSON = False, $Extra_HTTP_Headers = []
		Rand_Num					=> $Len
		Rand_Number					=> $min_num_count = 2, $max_num_count = 4, $min_len = 5, $max_len = 8
		Rand_Str					=> $Len
		Read_File_To_Array			=> $File_Path
		Redirect					=> $URL
		Remove_Char					=> $String, $char, $rem_with = ''
		Remove_All_Special_Chars 	=> $in_string, $protocols_2 = False
		Remove_Special_Chars		=> $in_string, $space_to = False
		Replace_Once				=> $Search, $Replace, $String
		Set_Cookie					=> $Cookie_Name, $Cookie_Value, $Cookie_Days = '30'
		Start_Session				=> Null
		Text_Has_String 			=> $Text, $String
