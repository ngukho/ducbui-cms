<?php
class Digitalus_View_Helper_Cms_N
{

	public function n($string,$symbol = ' $ ',$front = TRUE, $dec = 2, $dec_sign='.', $thousand = ',')
    {
    	if($front)
			$string = trim($symbol.number_format($string, $dec, $dec_sign, $thousand));
		else 
			$string = trim(number_format($string, $dec, $dec_sign, $thousand).$symbol);
//        return number_format($string, $dec, $dec_sign, $thousand);
		return $string;
    }    
}