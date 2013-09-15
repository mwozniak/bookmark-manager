<?php




function toUrl($string) {

		$normalizeChars = array(
					    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
					    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
					    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
					    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
					    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
					    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
					    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'Ą'=>'A', 'Ć'=>'C', 'Ę'=>'E',
					    'Ł'=>'L', 'Ń'=>'N', 'Ó'=>'O', 'Ś'=>'S', 'Ż'=>'Z', 'Ź'=>'Z', 'ą'=>'a', 'ć'=>'c', 'ę'=>'e', 'ł'=>'l',
					    'ń'=>'n', 'ó'=>'o', 'ś'=>'s', 'ż'=>'z', 'ź'=>'z', '/'=>'-'
					);

		// small fonts
        $sText = strtolower(strtr($string,$normalizeChars));
        // change spaces to -
        $sText = str_replace(' ', '-', $sText);
        // delete all other characters to -
        $sText = preg_replace('|[^0-9a-z\-\/+]|', '', $sText);
        // delete too much - if near
        $sText = preg_replace('/[\-]+/', '-', $sText);
        // trim -
        $sText = trim($sText, '-');

		//limit 150 chars
		if(strlen($sText)>150) $turl = substr($sText, 0, 150);

        return $sText;
}
















