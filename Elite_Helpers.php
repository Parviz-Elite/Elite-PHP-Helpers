<?php
	/*
		* Title		: Elite PHP Helpers
		* Dec		: An PHP Class That Contains Many Helpful Methods For General Needs.
		* Author	: Parviz-Turk
		* Email 	: Parviz@HackerMail.com - Parviz@Engineer.com
		* Web		: http://Parviz.id.ir/
		* Version	: 4.8.0
		
		
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
	*/
	
	NameSpace ParvizTurk\Elite_Helpers;
	
	Class Helpers {
		
		Public Function Replace_Once( $Search, $Replace, $String ) {
			$Search = '/'. PReg_Quote($Search, '/') . '/';
			
			Return Preg_Replace($Search, $Replace, $String, 1);
		}
		
		Public Function Add_Image_WaterMark( $Image_Path, $WaterMark_Path, $New_Image_Path = '', $MRight = 0, $MBottom = 10 ) {
			$Image = @ImageCreateFromJPEG( $Image_Path );
			IF ( $Image === False ) { $Image = @ImageCreateFromPNG( $Image_Path ); }
			IF ( $Image === False ) { Return False; }
			
			$wmark = @ImageCreateFromPNG( $WaterMark_Path );
			IF ( $wmark === False ) { $wmark = @ImageCreateFromJPEG( $Image_Path ); }
			IF ( $wmark === False ) { Return False; }
			
			$Marge_Right = $MRight;
			$Marge_Bottom = $MBottom;
			$sx = ImageSX($wmark);
			$sy = ImageSY($wmark);
			
			Imagecopy(
				$Image,
				$wmark,
				//ImageSX($Image) - $sx - $Marge_Right,
				//ImageSY($Image) - $sy - $Marge_Bottom,
				$Marge_Right,
				$Marge_Bottom,
				0, 0,
				ImageSX($wmark),
				ImageSY($wmark)
			);
			
			IF ( !Empty($New_Image_Path) ) {
				Imagejpeg($Image, $New_Image_Path);
			} Else {
				Imagejpeg($Image, $Image_Path);
			}
			
			Imagedestroy($Image);
		}
		
		Public Function Add_Text_Watermark( $img_Path, $wm_text ) {
			$Image = $img_Path;
			
			$newImg = @Imagecreatefromjpeg( $Image );
			IF ( $Image === False ) { $newImg = @Imagecreatefrompng( $Image ); }
			IF ( $Image === False ) { Return False; }
			
			$fontSize = 5;
			
			$xPosition = 10;
			$yPosition = 10;
			
			$fontColor = Imagecolorallocate($newImg, 255, 255, 255);
			
			Imagestring($newImg, $fontSize, $xPosition, $yPosition, $wm_text, $fontColor);
			
			Imagejpeg($newImg, $img_Path);
			
			Imagedestroy($newImg);
		}
		
		Public Function Build_Indexed_File_Path($Path, $FileName) {
			IF ( SubSTR($Path, -1) == DIRECTORY_SEPARATOR ) {
				$Path = SubSTR($Path, 0, -1);
			}
			
			$res = $Path . DIRECTORY_SEPARATOR . $FileName;
			
			IF ( !file_exists($res) ) Return $res;
			
			$fnameNoExt = Pathinfo($FileName, PATHINFO_FILENAME);
			$ext = Pathinfo($FileName, PATHINFO_EXTENSION);
			
			$i = 1;
			while( file_exists($Path . DIRECTORY_SEPARATOR . $fnameNoExt . "_" . $i . '.' . $ext) ) $i++;
			
			Return $Path . DIRECTORY_SEPARATOR . $fnameNoExt . "_" . $i . '.' . $ext;
		}
		
		Public Function Build_Token( $String, $build_salt = true ) {
			IF ($build_salt) { $salt = $this->build_token($String, False); } Else { $salt = ''; }
			$ac = $String;
			$ac = MD5(SHA1(Base64_EnCode($ac))) . $salt;
			$ac = CRC32($ac);
			$ac = STR_Replace(0, 'x', $ac);
			Return $ac;
		}
		
		Public Function Check_Len( $String, $min, $max ) {
			$len = strlen($String);
			IF ($len < $min or $len > $max) { Return False; } Else { Return true; }
		}
		
		Public Function Get_Remote_Image_Size( $url ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			$data = curl_exec( $ch );
			$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			$curl_errno = curl_errno( $ch );
			curl_close( $ch );
			
			IF ( $http_status != 200 ) {
				Echo 'HTTP Status[' . $http_status . '] Errno [' . $curl_errno . ']';
				Return [0,0];
			}
			
			$Image = Imagecreatefromstring( $data );
			$dims = [ Imagesx( $Image ), Imagesy( $Image ) ];
			Imagedestroy($Image);
			
			Return $dims;
		}
		
		Public Function Read_File_To_Array( $File_Path ) {
			$Res 	= [];
			$Handle = FOpen($File_Path, "r");
			
			IF ( !File_Exists($File_Path) OR !$Handle ) { Return $Res; }
			
			While ( ($Line = FGets($Handle)) !== False ) {
				$Line = $this->Remove_Char( $Line, "\r\n" );
				$Line = $this->Remove_Char( $Line, PHP_EOL );
				Array_Push( $Res, $Line );
			}
			
			FClose($Handle);
			
			Return $Res;
		}
		
		Public Function Remove_Char( $String, $char, $rem_with = '' ) {
			$String = STR_Replace( $char, $rem_with, $String );
			Return $String;
		}
		
		Public Function Correct_Iran_Phone($uNumber) {
			$uNumber = Trim($uNumber);
			$ret = $uNumber;
			
			IF (SubSTR($uNumber,0, 3) == '%2B')		{ $ret = SubSTR($uNumber, 3); $uNumber = $ret; }
			IF (SubSTR($uNumber,0, 3) == '%2b')		{ $ret = SubSTR($uNumber, 3); $uNumber = $ret; }
			IF (SubSTR($uNumber,0, 4) == '0098') 	{ $ret = SubSTR($uNumber, 4); $uNumber = $ret; }
			IF (SubSTR($uNumber,0, 3) == '098')		{ $ret = SubSTR($uNumber, 3); $uNumber = $ret; }
			IF (SubSTR($uNumber,0, 3) == '+98')		{ $ret = SubSTR($uNumber, 3); $uNumber = $ret; }
			IF (SubSTR($uNumber,0, 2) == '98') 		{ $ret = SubSTR($uNumber, 2); $uNumber = $ret; }
			IF (SubSTR($uNumber,0, 1) == '0') 		{ $ret = SubSTR($uNumber, 1); }
			
			Return '+98' . $ret;
		}
		
		Public Function Rand_Number( $min_num_count = 2, $max_num_count = 4, $min_len = 5, $max_len = 8 ) {
			/*
				Very Flexible & Usefull Function For Generating Easy To Remember Random Number
				Algorithm & Code By: Parviz-Turk
				Emails : Parviz@Engineer.com ~ Parviz@HackerMail.com
			*/
			
			$nums = array(); $i = $j = 0; $out = '';
			
			$len = mt_rand( $min_len, $max_len);
			$ncount = mt_rand( $min_num_count, $max_num_count);
			
			for ( $i = 0; $i < $ncount; $i++ ) {
				$rnd_num = mt_rand(1, 9);
				
				IF ( !in_array($rnd_num, $nums) ) {
					$nums[] = $rnd_num;
				} Else {
					$i--;
				}
			}
			
			for ( $j = 0; $j < $len; $j++ ) {
				$rand_arr = array_rand($nums);
				$out = $out . $nums[$rand_arr];
			}
			
			Return $out;
		}
		
		Public Function Get_Date_Last_Days($days, $format = 'Y-m-d'){
			$m = Date("m"); $de= Date("d"); $y= Date("Y");
			$dateArray = Array();
			for($i = 0; $i <= $days-1; $i++){
				$dateArray[] = Date($format, MKTime(0,0,0,$m,($de-$i),$y));
			}
			Return Array_Reverse($dateArray);
		}
		
		Public Function Input_Check($Title, $Val, $minL = 1, $maxL = 1, $Is_Num = False, $Is_Mail = False, $Is_Latin = False) {
			$Err_Msg = '';
			
			IF ( MB_strlen($Val) < $minL ) { $Err_Msg = '- حداقل تعداد کاراکتر فیلد <u>' . $Title . '</u> باید ' . $minL . ' عدد باشد.'; }
			
			IF ( Empty($Val) and $Val != '0' ) { $Err_Msg = '- فیلد <u>' . $Title . '</u> نمیتواند خالی باشد.'; }
			
			IF ( MB_strlen($Val) > $maxL ) { $Err_Msg = '- حداکثر تعداد کاراکتر فیلد <u>' . $Title . '</u> باید ' . $maxL . ' عدد باشد.'; }
			
			IF ( !Empty($Val) and $Is_Num and !is_numeric($Val) ) { $Err_Msg = '- مقدار ورودی فیلد <u>' . $Title . '</u> باید از نوع عددی باشد.'; }
			
			IF ( !Empty($Val) and $Is_Mail and !filter_var($Val, FILTER_VALIDATE_EMAIL) ) { $Err_Msg = '- مقدار ورودی فیلد <u>' . $Title . '</u> در قالب درست ایمیل نمی باشد.'; }
			
			IF ( !Empty($Val) and $Is_Latin and PREG_Match("/^[a-zA-Z0-9_]+$/", $Val) != 1 ) { $Err_Msg = '- مقدار ورودی فیلد <u>' . $Title . '</u> فقط باید شامل اعداد، حروف لاتین و کاراکتر ( _ ) باشد.'; }
			
			IF ( !Empty($Err_Msg) ) { $Err_Msg .= '<br />'; }
			
			Return $Err_Msg;
		}
		
		Public Function Rand_Str( $Len = 15 ) {
			$rnd_str = '';
			$temp_int = $rnd_status = 1;
			for ($i = 1; $i <= $Len; $i++) {
				switch ($rnd_status) {
					case 1:
						$temp_int = rand(97, 122);
						break;
					case 2:
						$temp_int = rand(65, 90);
						break;
					case 3:
						$temp_int = rand(48, 57);
						break;
				}
				$rnd_str .= chr($temp_int);
				$rnd_status = rand(1, 3);
			}
			
			Return $rnd_str;
		}
		
		Public Function Rand_Num( $Len = 15 ) {
			$rnd_num = '';
			$temp_int = 1;
			for ($i = 1; $i <= $Len; $i++) {
				$temp_int = rand(48, 57);
				$rnd_num .= chr($temp_int);
			}
			
			Return $rnd_num;
		}
		
		Public Function Redirect( $URL ) {
			IF( !Headers_sent() ) {
				Header('Location: ' . $URL, true, 302);
				Die();
			} Else {
				Echo '<script type="text/javascript">';
				Echo 'window.location.href="' . $URL . '";';
				Echo '</script>';
				Echo '<noscript>';
				Echo '<meta http-equiv="refresh" content="0;url=' . $URL . '" />';
				Echo '</noscript>';
				Die();
			}
		}
		
		Public Function Post_Redirect( $URL, $Data = [] ) {
		?>
			<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<script type="text/javascript">function closethisasap() { document.forms["redirectpost"].submit(); }</script>
				</head>
				<body onload="closethisasap();">
					<form name="redirectpost" method="post" action="<? Echo $URL; ?>">
						<?php
							IF ( !Is_Null($Data) ) {
								ForEach ($Data as $K => $V) {
									Echo '<input type="hidden" name="' . $K . '" value="' . $V . '" />' . PHP_EOL;
								}
							}
						?>
					</form>
				</body>
			</html>
		<?php
			Exit();
		}
		
		Public Function English_To_Persian_num( $Number ) {
			$farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
			$english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
			Return STR_Replace($english_array, $farsi_array, $Number);
		}
		
		Public Function Persian_To_English_Num( $Number ) {
			$farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
			$english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
			Return STR_Replace($farsi_array, $english_array, $Number);
		}
		
		Public Function Is_Number( $Input_String ) {
			IF (!PREG_Match('/[^0-9]/', $Input_String)) {
				Return true;
			} Else {
				Return False;
			}
		}
		
		Public Function Clear_Get_Vars( $String, $remove_ls = False ) {
			$mcode = urldecode($String);
			
			// remove get vars IF exist
			$gve = strpos($mcode, '?');
			IF ($gve !== False) {
				$mcode = explode('?', $mcode);
				$mcode = $mcode[0];
			}
			
			// remove / from last of url IF exist
			IF ( SubSTR($mcode, -1) == '/' and $remove_ls == true ) {
				$mcode = SubSTR($mcode, 0, -1);
			}
			
			Return $mcode;
		}
		
		Public Function Start_Session() {
			IF ( ! $this->Is_Session_Started() ) { @session_start(); }
		}
		
		Public Function Is_Session_Started() {
			IF ( php_sapi_name() !== 'cli' ) {
				IF ( version_compare(phpversion(), '5.4.0', '>=') ) {
					Return session_status() === PHP_SESSION_ACTIVE ? TRUE : False;
				} Else {
					Return session_id() === '' ? False : TRUE;
				}
			}
			
			Return False;
		}
		
		Public Function Get_JSON( $URL, $Using = 'CURL', $In_Array = False, $URL_Decode = True ) {
			
			$JText = $this->Get_Content( $URL, $Using, $URL_Decode );
			
			IF ( !Empty($JText) And $this->Is_Json($JText) ) {
				Return JSON_Decode($JText, $In_Array);
			}
			
			Return False;
		}
		
		Public Function Get_Content( $URL, $Using = 'CURL', $URL_Decode = True ) {
			// $Using : FGC , CURL
			
			IF ( $URL_Decode ) { $URL = URLDeCode($URL); }
			
			$Content = '';
			
			IF ( $Using == 'FGC' ) {
				
				$ContextOptions= Array(
					"ssl" => Array(
							"verify_peer"		=> False,
							"verify_peer_name"	=> False
					)
				);
				
				$Content = @File_Get_Contents($URL, False, Stream_Context_Create($ContextOptions));
				
			} ElseIF ( $Using == 'CURL' ) {
				
				$User_Agent='Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0';
				
				$COptions = Array(
					CURLOPT_CUSTOMREQUEST	=> "GET",
					CURLOPT_POST			=> False,
					CURLOPT_USERAGENT		=> $User_Agent,
					CURLOPT_COOKIEFILE		=> "Cookie.txt",
					CURLOPT_COOKIEJAR		=> "Cookie.txt",
					CURLOPT_RETURNTRANSFER	=> True,
					CURLOPT_HEADER			=> False,
					CURLOPT_FOLLOWLOCATION	=> True,
					CURLOPT_ENCODING		=> "",
					CURLOPT_AUTOREFERER		=> True,
					CURLOPT_CONNECTTIMEOUT	=> 120,
					CURLOPT_TIMEOUT			=> 1200,
					CURLOPT_MAXREDIRS		=> 10,
				);
				
				$CURL 		= CURL_INIT($URL);
				CURL_SetOPT_Array( $CURL, $COptions );
				$Content 	= CURL_Exec($CURL);
				CURL_Close($CURL);
				
			}
			
			IF ( !Empty($Content) ) { Return $Content; }
			
			Return False;
		}
		
		Public Function Post_Request( $URL, $Data, $Is_JSON = False, $Extra_HTTP_Headers = [] ) {
			$User_Agent='Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0';
			
			$PostVars = '';
			
			IF ( Is_Array($Data) ) { $PostVars = HTTP_Build_Query($Data); } Else { $PostVars = $Data; }
			
			$HTTP_Headers[] = 'Content-Length: ' . MB_STRLen($PostVars);
			
			IF ( $Is_JSON ) {
				$HTTP_Headers[] = 'Content-Type: application/json';
			} Else {
				$HTTP_Headers[] = 'Content-Type: application/x-www-form-urlencoded';
			}
			
			IF ( !Empty($Extra_HTTP_Header) ) {
				Array_Push( $HTTP_Headers, $Extra_HTTP_Headers );
			}
			
			$COptions = Array(
				CURLOPT_POST			=> True,
				CURLOPT_POSTFIELDS		=> $PostVars,
				CURLOPT_USERAGENT		=> $User_Agent,
				CURLOPT_COOKIEFILE		=> "Cookie.txt",
				CURLOPT_COOKIEJAR		=> "Cookie.txt",
				CURLOPT_RETURNTRANSFER	=> True,
				CURLOPT_HEADER			=> False,
				CURLOPT_FOLLOWLOCATION	=> True,
				CURLOPT_ENCODING		=> "",
				CURLOPT_AUTOREFERER		=> True,
				CURLOPT_CONNECTTIMEOUT	=> 120,
				CURLOPT_TIMEOUT			=> 1200,
				CURLOPT_MAXREDIRS		=> 10,
				CURLOPT_HTTPHEADER		=> $HTTP_Headers
			);
			
			$CURL 		= CURL_INIT($URL);
			CURL_SetOPT_Array( $CURL, $COptions );
			$Content 	= CURL_Exec($CURL);
			CURL_Close($CURL);
			
			IF ( !Empty($Content) ) {
				IF ( $this->Is_Json($Content) ) { $Content = JSON_Decode($Content); }
				Return $Content;
			}
			
			Return False;
		}
		
		Public Function Remove_Special_Chars( $in_string, $space_to = False ) {
			$pattern = array('’', '‘', '!', '@', '#', '$', '%', '^', '*', '&', '(', ')', '+', '=', ',', '<', '>', '{', '}', '[', ']', '?', chr(34), chr(92));
			$res_string = STR_Replace($pattern, '', $in_string);
			
			IF ( $space_to === true ) { $res_string = STR_Replace(' ', '+', $res_string); }
			
			Return $res_string;
		}
		
		Public Function Remove_All_Special_Chars( $in_string, $protocols_2 = False ) {
			$res_string = preg_replace('/[^A-Za-z0-9\-]/', '', $in_string);
			IF ( $protocols_2 ) {
				$res_string = STR_Replace('www', 	'', $res_string);
				$res_string = STR_Replace('http', 	'', $res_string);
				$res_string = STR_Replace('https', 	'', $res_string);
				$res_string = STR_Replace('com', 	'', $res_string);
			}
			Return $res_string;
		}
		
		Public Function Text_Has_String( $Text, $String ) {
			IF (strpos($Text, $String) !== False) {
				Return true;
			} Else {
				Return False;
			}
		}
		
		Public Function Get_Excerpt( $Str, $startPos=0, $maxLength=100, $With_etc = true ) {
			IF(MB_strlen($Str) > $maxLength) {
				IF ( $With_etc ) { $maxLength = $maxLength - 3; }
				$excerpt   = MB_SubSTR($Str, $startPos, $maxLength);
				$lastSpace = MB_strrpos($excerpt, ' ');
				$excerpt   = MB_SubSTR($excerpt, 0, $lastSpace);
				IF ( $With_etc ) { $excerpt  .= ' ...'; }
			} Else {
				$excerpt = $Str;
			}
			
			Return $excerpt;
		}
		
		Public Function Encode( $String ) {
			$output = rtrim(strtr(base64_encode(gzdeflate($String, 9)), '+/', '-_'), '=');
			Return $output;
		}
		
		Public Function Decode( $String ) {
			$output = gzinflate(base64_decode(strtr($String, '-_', '+/')));
			Return $output;
		}
		
		Public Function Load_JS( $js_Path ) {
			IF (!Empty($js_Path)) {
				Echo chr(9) . chr(9) . '<script type="text/javascript" src="' . $js_Path . '" charset="UTF-8" ></script>';
				Echo chr(13);
			}
		}
		
		Public Function Load_CSS( $css_Path ) {
			IF (!Empty($css_Path)) {
				Echo chr(9) . chr(9) . '<link rel="stylesheet" type="text/css" href="' . $css_Path . '">';
				Echo chr(13);
			}
		}
		
		function Load_File( $file_Path, $show_message = true ) {
			IF ( file_exists( $file_Path ) ) {
				require_once( $file_Path );
				Return true;
			} Else {
				IF ( $show_message ) { Echo '<h4><font color="red">Missing File : </font><strong>' . $file_Path . '</strong></h4>'; }
				Return False;
			}
		}
		
		Public Function Set_Cookie( $Cookie_Name, $Cookie_Value, $Cookie_Days = '30' ) {
			Return @SetCookie( $Cookie_Name, $Cookie_Value, strtotime('+' . $Cookie_Days . ' day'), "/" );
		}
		
		Public Function Delete_Cookie( $Cookie_Name ) {
			Return @SetCookie( $Cookie_Name, Null, strtotime('-1 day'), "/" );
		}
		
		Public Function Get_URL_FileName( $URL ) {
			$fname = Parse_URL($URL, PHP_URL_PATH);
			Return BaseName($URL);
		}
		
		Public Function Download_Remote_File( $Remote_File, $Local_Path ) {
			Set_Time_Limit(1200);
			INI_Set('max_execution_time', '1200');
			
			$Remote_File = RawURLEncode($Remote_File);
			$Remote_File = STR_Replace('%3A', ':', STR_Replace('%2F', '/', $Remote_File));
			
			$FP = FOpen($Local_Path, 'w+');
			$CH = CURL_INIT($Remote_File);
			CURL_SetOpt($CH, CURLOPT_TIMEOUT, 0);
			CURL_SetOpt($CH, CURLOPT_FILE, $FP);
			CURL_SetOpt($CH, CURLOPT_FOLLOWLOCATION, True);
			CURL_SetOpt($CH, CURLOPT_TIMEOUT, 1200);
			
			CURL_exec($CH);
			IF ( CURL_ErrNo($CH) ) {
				$Res = False;
			} Else {
				$resultStatus = CURL_GetInfo($CH, CURLINFO_HTTP_CODE);
				IF ($resultStatus == 200) {
					IF ( File_Exists($Local_Path) ) {
						$Res = True;
					} Else {
						$Res = False;
					}
				} Else {
					$Res = False;
				}
			}
			CURL_Close($CH);
			FClose($FP);
			
			Return $Res;
		}
		
		Public Function Miladi_To_Shamsi( $gy, $gm, $gd, $mod='', $time2 = False, $leading_zero = False ) {
			$g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
			$jy = ($gy <= 1600) ? 0 : 979;
			$gy -= ($gy <= 1600) ? 621 : 1600;
			$gy2 = ($gm > 2) ? ($gy + 1) : $gy;
			$days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm-1];
			$jy += 33 * ((int)($days / 12053));
			$days %= 12053;
			$jy += 4 * ((int)($days / 1461));
			$days %= 1461;
			$jy += (int)(($days - 1) / 365);
			IF($days > 365) $days = ($days - 1) % 365;
			$jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
			$jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
			
			IF ( $leading_zero ) {
				$jm = sprintf("%02d", $jm);
				$jd = sprintf("%02d", $jd);
			}
			
			IF ($mod != '') {
				$resret = $jy . $mod . $jm . $mod . $jd;
			} Else {
				$resret = array( $jy, $jm, $jd );
			}
			
			IF ($time2) {
				$resret .= ' ' . date('H:i:s');
			}
			
			Return $resret;
		}
		
		Public Function Get_Shamsi_Date( $MOD = DIRECTORY_SEPARATOR, $Time2 = False, $Leading_Zero = True ) {
			$SDate = $this->Miladi_To_Shamsi( Date('Y'), Date('m'), Date('d'), $MOD, $Time2, $Leading_Zero );
			Return $SDate;
		}
		
		Public Function Is_Json( $String ) {
			Json_Decode($String);
			Return (Json_Last_Error() == JSON_ERROR_NONE);
		}
		
		Function __construct() {
		
		}
		
		Function __destruct() {
		
		}
	}
?>
