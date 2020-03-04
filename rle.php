<?php
	/**
     * Compress a string using RLE
     *
     * @param   string  $str
     * @return  string
     */
	function encode_rle(string $str) {
		$output = "";
		$cpt = 1;
		$len = strlen($str);
		for ($i=1; $i<=$len; $i++) {
			if ($str[$i-1]==$str[$i]) {
				$cpt++;
			}
			else {
				while ($cpt>=255) {
					$output .= chr(255).$str[$i-1];
					$cpt -= 255;
				}
				$output .= chr($cpt).$str[$i-1];
				$cpt = 1;
			}

		}
		return $output;
	}

	/**
     * Uncommpress a RLE encoded 
     *
     * @param   string  $str
     * @return  string
     */
	function decode_rle(string $str) {
		$output = "";
		$cpt = 0;
		$len = strlen($str);
		for ($i=0; $i<$len-1; $i+=2) {
			$cpt = ord($str[$i]);
            for ($j=0; $j<$cpt; $j++) {
            	$output .= $str[$i+1];
            }
        }
		return $output;
	}

    /**
     * Compress a bmp file using RLE
     *
     * @param   string  $path_to_encode
     * @param  string  $result_path
     * @return string 
     */
	function encode_advanced_rle(string $path_to_encode, string $result_path) {
		try {
			$filein = fopen($path_to_encode,"r");
	      	$fstats = fstat($filein);
	    	$sizein = $fstats['size'];
	    	$input = fread($filein, $sizein);
			fclose($filein);

			$output = encode_rle($input);

			$fileout = fopen($result_path,"w");
			$rc = fwrite($fileout, $output);
			fclose($fileout);

			if ($rc === FALSE) {
       			return "$$$";
    		}
			return $result_path;
		} catch (Exception $e) {
			echo sprintf("Exception %s.\n",$e->getMessage());	
    		return "$$$";
		}
	}


	/**
     * Unompress a RLE encoded file to a bmp file
     *
     * @param   string  $path_to_decode
     * @param  string  $result_path
     * @return string 
     */
	function decode_advanced_rle(string $path_to_decode, string $result_path) {
		try {
			$filein = fopen($path_to_decode,"r");
	      	$fstats = fstat($filein);
	    	$sizein = $fstats['size'];
	    	$input = fread($filein, $sizein);
			fclose($filein);

			$output = decode_rle($input);

			$fileout = fopen($result_path,"w");
			$rc = fwrite($fileout, $output);
			fclose($fileout);

			if ($rc === FALSE) {
       			return "$$$";
    		}
			return $result_path;
		} catch (Exception $e) {
			echo sprintf("Exception %s.\n",$e->getMessage());	
    		return "$$$";
		}
	}	

	//Encode file
	$path_to_encode = "toto.bmp";
	$result_path = "toto.RLE";
    $result = encode_advanced_rle($path_to_encode, $result_path);
    if ($result === "$$$") {
    	$message =  sprintf("File %s unsuccessfully encoded to %s\n",$path_to_encode,$result_path);	
    }
    else {
    	$message =  sprintf("File %s successfully encoded to %s\n",$path_to_encode, $result);	
    }
    echo $message;

    //Decode file
	$path_to_decode = "toto.RLE";
	$result_path = "toto1.bmp";
    $result = decode_advanced_rle($path_to_decode, $result_path);
    if ($result === "$$$") {
    	$message =  sprintf("File %s unsuccessfully decoded to %s\n",$path_to_decode,$result_path);	
    }
    else {
    	$message =  sprintf("File %s successfully decoded to %s\n",$path_to_decode, $result);	
    }
    echo $message;


?>