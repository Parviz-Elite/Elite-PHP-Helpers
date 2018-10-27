<?php
	/*
		* Title		: Elite PHP Helpers
		* Dec		: An PHP Class That Contains Many Helpful Methods For General Needs.
		* Author	: Parviz-Turk
		* Email 	: Parviz@HackerMail.com - Parviz@Engineer.com
		* Web		: http://Parviz.id.ir/
		* Version	: 4.1.0
		
		
		Add_Image_WaterMark			=> $imageage_path, $watermark_path, $new_image_path = '', $mright = 0, $mbottom = 10
		Add_Text_Watermark			=> $img_path, $wm_text
		Build_Indexed_File_Path		=> $Path, $FileName
		Build_Token 				=> $String, $build_salt = true
		Check_Len					=> $String, $min, $max
		Clear_Get_Vars 				=> $string, $remove_ls = false
		Correct_Iran_Phone			=> $uNumber
		Decode 						=> $string
		Delete_Cookie				=> $Cookie_Name
		Download_Remote_File 		=> $Remote_File, $Local_Path
		Encode 						=> $string
		Persian_To_English_Num		=> $Number
		Get_Date_Last_Days			=> $days, $format = 'Y-m-d'
		Get_Content 				=> $URL
		Get_Excerpt 				=> $str, $startPos=0, $maxLength=100, $With_etc = true
		Get_Json 					=> $URL
		Get_Remote_Image_Size		=> $URL
		Get_Shamsi_Date 			=> $mod = DIRECTORY_SEPARATOR, $time2 = false, $leading_zero = true
		Get_URL_FileName 			=> $URL
		Input_Check					=> $Title, $Val, $minL = 1, $maxL = 1, $Is_Num = false, $Is_Mail = false, $Is_Latin = false
		Is_Json						=> $string
		Is_Number 					=> $input_string
		Is_Session_Started			=> Null
		Load_CSS 					=> $css_path
		Load_File 					=> $file_path, $show_message = true
		Load_JS 					=> $js_path
		Miladi_To_Shamsi 			=> $gy, $gm, $gd, $mod='', $time2 = false, $leading_zero = false
		English_To_Persian_num		=> $Number
		Post_Redirect				=> $url, $data
		Rand_Num					=> $len
		Rand_Number					=> $min_num_count = 2, $max_num_count = 4, $min_len = 5, $max_len = 8
		Rand_Str					=> $len
		Read_File_To_Array			=> $File_Path
		Redirect					=> $url
		Remove_Char					=> $string, $char, $rem_with = ''
		Remove_All_Special_Chars 	=> $in_string, $protocols_2 = false
		Remove_Special_Chars		=> $in_string, $space_to = false
		Replace_Once				=> $Search, $Replace, $String
		Set_Cookie					=> $Cookie_Name, $Cookie_Value, $Cookie_Days = '30'
		Start_Session				=> Null
		Text_Has_String 			=> $text, $string
	*/
	
	NameSpace ParvizTurk\Elite_Helpers;
	
	Class Helpers {
		
		Public Function Replace_Once( $Search, $Replace, $String ) {
			$Search = '/'. preg_quote($Search, '/') . '/';
			
			Return Preg_Replace($Search, $Replace, $String, 1);
		}
		
		Public Function Add_Image_WaterMark( $imageage_path, $watermark_path, $new_image_path = '', $mright = 0, $mbottom = 10 ) {
			$image = @imagecreatefromjpeg( $imageage_path );
			if ( $image === false ) { $image = @imagecreatefrompng( $imageage_path ); }
			if ( $image === false ) { return false; }
			
			$wmark = @imagecreatefrompng( $watermark_path );
			if ( $wmark === false ) { $wmark = @imagecreatefromjpeg( $imageage_path ); }
			if ( $wmark === false ) { return false; }
			
			$marge_right = $mright;
			$marge_bottom = $mbottom;
			$sx = imagesx($wmark);
			$sy = imagesy($wmark);
			
			imagecopy(
				$image,
				$wmark,
				//imagesx($image) - $sx - $marge_right,
				//imagesy($image) - $sy - $marge_bottom,
				$marge_right,
				$marge_bottom,
				0, 0,
				imagesx($wmark),
				imagesy($wmark)
			);
			
			if ( !empty($new_image_path) ) {
				imagejpeg($image, $new_image_path);
			} else {
				imagejpeg($image, $imageage_path);
			}
			
			imagedestroy($image);
		}
		
		Public Function Add_Text_Watermark( $img_path, $wm_text ) {
			$image = $img_path;
			
			$newImg = @imagecreatefromjpeg( $image );
			if ( $image === false ) { $newImg = @imagecreatefrompng( $image ); }
			if ( $image === false ) { return false; }
			
			$fontSize = 5;
			
			$xPosition = 10;
			$yPosition = 10;
			
			$fontColor = imagecolorallocate($newImg, 255, 255, 255);
			
			imagestring($newImg, $fontSize, $xPosition, $yPosition, $wm_text, $fontColor);
			
			imagejpeg($newImg, $img_path);
			
			imagedestroy($newImg);
		}
		
		Public Function Build_Indexed_File_Path($Path, $FileName) {
			if ( substr($Path, -1) == DIRECTORY_SEPARATOR ) {
				$Path = substr($Path, 0, -1);
			}
			
			$res = $Path . DIRECTORY_SEPARATOR . $FileName;
			
			if ( !file_exists($res) ) return $res;
			
			$fnameNoExt = pathinfo($FileName, PATHINFO_FILENAME);
			$ext = pathinfo($FileName, PATHINFO_EXTENSION);
			
			$i = 1;
			while( file_exists($Path . DIRECTORY_SEPARATOR . $fnameNoExt . "_" . $i . '.' . $ext) ) $i++;
			
			return $Path . DIRECTORY_SEPARATOR . $fnameNoExt . "_" . $i . '.' . $ext;
		}
		
		Public Function Build_Token( $String, $build_salt = true ) {
			if ($build_salt) { $salt = $this->build_token($String, false); } else { $salt = ''; }
			$ac = $String;
			$ac = md5(sha1(base64_encode($ac))) . $salt;
			$ac = crc32($ac);
			$ac = str_replace(0, 'x', $ac);
			return $ac;
		}
		
		Public Function Check_Len( $String, $min, $max ) {
			$len = strlen($String);
			if($len < $min or $len > $max) { return false; } else { return true; }
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
			
			if ( $http_status != 200 ) {
				echo 'HTTP Status[' . $http_status . '] Errno [' . $curl_errno . ']';
				return [0,0];
			}
			
			$image = imagecreatefromstring( $data );
			$dims = [ imagesx( $image ), imagesy( $image ) ];
			imagedestroy($image);
			
			return $dims;
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
		
		Public Function Remove_Char( $string, $char, $rem_with = '' ) {
			$string = str_replace( $char, $rem_with, $string );
			return $string;
		}
		
		Public Function Correct_Iran_Phone($uNumber) {
			$uNumber = Trim($uNumber);
			$ret = $uNumber;
			
			if (substr($uNumber,0, 3) == '%2B')		{ $ret = substr($uNumber, 3); $uNumber = $ret; }
			if (substr($uNumber,0, 3) == '%2b')		{ $ret = substr($uNumber, 3); $uNumber = $ret; }
			if (substr($uNumber,0, 4) == '0098') 	{ $ret = substr($uNumber, 4); $uNumber = $ret; }
			if (substr($uNumber,0, 3) == '098')		{ $ret = substr($uNumber, 3); $uNumber = $ret; }
			if (substr($uNumber,0, 3) == '+98')		{ $ret = substr($uNumber, 3); $uNumber = $ret; }
			if (substr($uNumber,0, 2) == '98') 		{ $ret = substr($uNumber, 2); $uNumber = $ret; }
			if (substr($uNumber,0, 1) == '0') 		{ $ret = substr($uNumber, 1); }
			
			return '+98' . $ret;
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
				
				if ( !in_array($rnd_num, $nums) ) {
					$nums[] = $rnd_num;
				} else {
					$i--;
				}
			}
			
			for ( $j = 0; $j < $len; $j++ ) {
				$rand_arr = array_rand($nums);
				$out = $out . $nums[$rand_arr];
			}
			
			return $out;
		}
		
		Public Function Get_Date_Last_Days($days, $format = 'Y-m-d'){
			$m = date("m"); $de= date("d"); $y= date("Y");
			$dateArray = array();
			for($i = 0; $i <= $days-1; $i++){
				$dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y));
			}
			return array_reverse($dateArray);
		}
		
		Public Function Input_Check($Title, $Val, $minL = 1, $maxL = 1, $Is_Num = false, $Is_Mail = false, $Is_Latin = false) {
			$Err_Msg = '';
			
			if ( mb_strlen($Val) < $minL ) { $Err_Msg = '- حداقل تعداد کاراکتر فیلد <u>' . $Title . '</u> باید ' . $minL . ' عدد باشد.'; }
			
			if ( empty($Val) and $Val != '0' ) { $Err_Msg = '- فیلد <u>' . $Title . '</u> نمیتواند خالی باشد.'; }
			
			if ( mb_strlen($Val) > $maxL ) { $Err_Msg = '- حداکثر تعداد کاراکتر فیلد <u>' . $Title . '</u> باید ' . $maxL . ' عدد باشد.'; }
			
			if ( !empty($Val) and $Is_Num and !is_numeric($Val) ) { $Err_Msg = '- مقدار ورودی فیلد <u>' . $Title . '</u> باید از نوع عددی باشد.'; }
			
			if ( !empty($Val) and $Is_Mail and !filter_var($Val, FILTER_VALIDATE_EMAIL) ) { $Err_Msg = '- مقدار ورودی فیلد <u>' . $Title . '</u> در قالب درست ایمیل نمی باشد.'; }
			
			if ( !empty($Val) and $Is_Latin and preg_match("/^[a-zA-Z0-9_]+$/", $Val) != 1 ) { $Err_Msg = '- مقدار ورودی فیلد <u>' . $Title . '</u> فقط باید شامل اعداد، حروف لاتین و کاراکتر ( _ ) باشد.'; }
			
			if ( !empty($Err_Msg) ) { $Err_Msg .= '<br />'; }
			
			return $Err_Msg;
		}
		
		Public Function Rand_Str( $len = 15 ) {
			$rnd_str = '';
			$temp_int = $rnd_status = 1;
			for ($i = 1; $i <= $len; $i++) {
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
			
			return $rnd_str;
		}
		
		Public Function Rand_Num( $len = 15 ) {
			$rnd_num = '';
			$temp_int = 1;
			for ($i = 1; $i <= $len; $i++) {
				$temp_int = rand(48, 57);
				$rnd_num .= chr($temp_int);
			}
			
			return $rnd_num;
		}
		
		Public Function Redirect( $url ) {
			if( !headers_sent() ) {
				header('Location: ' . $url, true, 302);
				die();
			} else {
				echo '<script type="text/javascript">';
				echo 'window.location.href="'.$url.'";';
				echo '</script>';
				echo '<noscript>';
				echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
				echo '</noscript>';
				die();
			}
		}
		
		Public Function Post_Redirect( $URL, $Data = [] ) {
		?>
			<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<script type="text/javascript">function closethisasap() { document.forms["redirectpost"].submit(); }</script>
				</head>
				<body onload="closethisasap();">
					<form name="redirectpost" method="post" action="<? echo $URL; ?>">
						<?php
							IF ( !Is_Null($Data) ) {
								ForEach ($Data as $K => $V) {
									echo '<input type="hidden" name="' . $K . '" value="' . $V . '" />' . PHP_EOL;
								}
							}
						?>
					</form>
				</body>
			</html>
		<?php
			Exit;
		}
		
		Public Function English_To_Persian_num( $Number ) {
			$farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
			$english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
			return str_replace($english_array, $farsi_array, $Number);
		}
		
		Public Function Persian_To_English_Num( $Number ) {
			$farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫");
			$english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".");
			return str_replace($farsi_array, $english_array, $Number);
		}
		
		Public Function Is_Number( $input_string ) {
			if (!preg_match('/[^0-9]/', $input_string)) {
				return true;
			} else {
				return false;
			}
		}
		
		Public Function Clear_Get_Vars( $string, $remove_ls = false ) {
			$mcode = urldecode($string);
			
			// remove get vars if exist
			$gve = strpos($mcode, '?');
			if ($gve !== false) {
				$mcode = explode('?', $mcode);
				$mcode = $mcode[0];
			}
			
			// remove / from last of url if exist
			if ( substr($mcode, -1) == '/' and $remove_ls == true ) {
				$mcode = substr($mcode, 0, -1);
			}
			
			return $mcode;
		}
		
		Public Function Start_Session() {
			if ( ! $this->Is_Session_Started() ) { @session_start(); }
		}
		
		Public Function Is_Session_Started() {
			if ( php_sapi_name() !== 'cli' ) {
				if ( version_compare(phpversion(), '5.4.0', '>=') ) {
					return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
				} else {
					return session_id() === '' ? FALSE : TRUE;
				}
			}
			
			return FALSE;
		}
		
		Public Function Get_Json( $URL, $URL_Decode = true ) {
			if ( $URL_Decode ) { $URL = urldecode($URL); }
			
			$arrContextOptions= array( "ssl" => array( "verify_peer"=>false, "verify_peer_name"=>false ) );
			
			$jtext = @file_get_contents($URL, false, stream_context_create($arrContextOptions));
			
			if ( empty($jtext) ) { return false; } else { $jobj = @json_decode($jtext); }
			
			return $jobj;
		}
		
		Public Function Get_Content( $URL, $URL_Decode = true ) {
			if ( $URL_Decode ) { $URL = urldecode($URL); }
			
			$arrContextOptions= array( "ssl" => array("verify_peer" => false, "verify_peer_name" => false) );
			
			$content = @file_get_contents($URL, false, stream_context_create($arrContextOptions));
			
			if ( empty($content) ) { return false; } else { return $content; }
		}
		
		Public Function Remove_Special_Chars( $in_string, $space_to = false ) {
			$pattern = array('’', '‘', '!', '@', '#', '$', '%', '^', '*', '&', '(', ')', '+', '=', ',', '<', '>', '{', '}', '[', ']', '?', chr(34), chr(92));
			$res_string = str_replace($pattern, '', $in_string);
			
			if ( $space_to === true ) { $res_string = str_replace(' ', '+', $res_string); }
			
			return $res_string;
		}
		
		Public Function Remove_All_Special_Chars( $in_string, $protocols_2 = false ) {
			$res_string = preg_replace('/[^A-Za-z0-9\-]/', '', $in_string);
			if ( $protocols_2 ) {
				$res_string = str_replace('www', 	'', $res_string);
				$res_string = str_replace('http', 	'', $res_string);
				$res_string = str_replace('https', 	'', $res_string);
				$res_string = str_replace('com', 	'', $res_string);
			}
			return $res_string;
		}
		
		Public Function Text_Has_String( $text, $string ) {
			if (strpos($text, $string) !== false) {
				return true;
			} else {
				return false;
			}
		}
		
		Public Function Get_Excerpt( $str, $startPos=0, $maxLength=100, $With_etc = true ) {
			if(mb_strlen($str) > $maxLength) {
				if ( $With_etc ) { $maxLength = $maxLength - 3; }
				$excerpt   = mb_substr($str, $startPos, $maxLength);
				$lastSpace = mb_strrpos($excerpt, ' ');
				$excerpt   = mb_substr($excerpt, 0, $lastSpace);
				if ( $With_etc ) { $excerpt  .= ' ...'; }
			} else {
				$excerpt = $str;
			}
			
			return $excerpt;
		}
		
		Public Function Encode( $string ) {
			$output = rtrim(strtr(base64_encode(gzdeflate($string, 9)), '+/', '-_'), '=');
			return $output;
		}
		
		Public Function Decode( $string ) {
			$output = gzinflate(base64_decode(strtr($string, '-_', '+/')));
			return $output;
		}
		
		Public Function Load_JS( $js_path ) {
			if (!empty($js_path)) {
				echo chr(9) . chr(9) . '<script type="text/javascript" src="' . $js_path . '" charset="UTF-8" ></script>';
				echo chr(13);
			}
		}
		
		Public Function Load_CSS( $css_path ) {
			if (!empty($css_path)) {
				echo chr(9) . chr(9) . '<link rel="stylesheet" type="text/css" href="' . $css_path . '">';
				echo chr(13);
			}
		}
		
		function Load_File( $file_path, $show_message = true ) {
			if ( file_exists( $file_path ) ) {
				require_once( $file_path );
				return true;
			} else {
				if ( $show_message ) { echo '<h4><font color="red">Missing File : </font><strong>' . $file_path . '</strong></h4>'; }
				return false;
			}
		}
		
		Public Function Set_Cookie( $Cookie_Name, $Cookie_Value, $Cookie_Days = '30' ) {
			Return @setcookie( $Cookie_Name, $Cookie_Value, strtotime('+' . $Cookie_Days . ' day'), "/" );
		}
		
		Public Function Delete_Cookie( $Cookie_Name ) {
			Return @setcookie( $Cookie_Name, null, strtotime('-1 day'), "/" );
		}
		
		Public Function Get_URL_FileName( $URL ) {
			$fname = parse_url($URL, PHP_URL_PATH);
			Return basename($URL);
		}
		
		Public Function Download_Remote_File( $Remote_File, $Local_Path ) {
			set_time_limit(1200);
			ini_set('max_execution_time', '1200');
			
			$fp = fopen($Local_Path, 'w+');
			$ch = curl_init($Remote_File);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
			
			curl_exec($ch);
			if ( curl_errno($ch) ) {
				echo curl_error($ch);
				$res = false;
			} else {
				$resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if ($resultStatus == 200) {
					if ( file_exists($Local_Path) ) {
						$res = true;
					} else {
						$res = false;
					}
				} else {
					$res = false;
				}
			}
			curl_close($ch);
			fclose($fp);
			
			return $res;
		}
		
		Public Function Miladi_To_Shamsi( $gy, $gm, $gd, $mod='', $time2 = false, $leading_zero = false ) {
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
			if($days > 365) $days = ($days - 1) % 365;
			$jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
			$jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
			
			if ( $leading_zero ) {
				$jm = sprintf("%02d", $jm);
				$jd = sprintf("%02d", $jd);
			}
			
			if ($mod != '') {
				$resret = $jy . $mod . $jm . $mod . $jd;
			} else {
				$resret = array( $jy, $jm, $jd );
			}
			
			if ($time2) {
				$resret .= ' ' . date('H:i:s');
			}
			
			return $resret;
		}
		
		Public Function Get_Shamsi_Date( $mod = DIRECTORY_SEPARATOR, $time2 = false, $leading_zero = true ) {
			$sdate = $this->Miladi_To_Shamsi( date('Y'), date('m'), date('d'), $mod, $time2, $leading_zero );
			return $sdate;
		}
		
		Public Function Is_Json( $string ) {
			json_decode($string);
			return (json_last_error() == JSON_ERROR_NONE);
		}
		
		Function __construct() {
		
		}
		
		Function __destruct() {
		
		}
	}
?>
