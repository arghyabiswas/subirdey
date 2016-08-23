<?php
/**
 * ThemeREX Framework: html manipulations
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('themerex_html_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_html_theme_setup' );
	function themerex_html_theme_setup() {

		// Set e-mail content type to html for the wp_mail()
		add_filter( 'wp_mail_content_type', 'themerex_set_html_content_type' );

	}
}


/* Wrappers
-------------------------------------------------------------------------------- */

// Open wrapper tags and add it to stack
if (!function_exists('themerex_open_wrapper')) {
	function themerex_open_wrapper($tags, $echo=true) {
		global $THEMEREX_GLOBALS;
		if (!isset($THEMEREX_GLOBALS['wrappers'])) $THEMEREX_GLOBALS['wrappers'] = array();
		if (!is_array($tags) && !empty($tags)) $tags = array($tags);
		$output = '';
		if (count($tags) > 0) {
			$cnt = 0;
			foreach ($tags as $tag) {
				$THEMEREX_GLOBALS['wrappers'][] = $tag;
				$output .= "\n".str_repeat("\t", $cnt++).($tag);
			}
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Close wrapper and delete it from stack
if (!function_exists('themerex_close_wrapper')) {
	function themerex_close_wrapper($cnt=1, $echo=true) {
		global $THEMEREX_GLOBALS;
		$output = '';
		$level = count($THEMEREX_GLOBALS['wrappers']);
		$i = 0;
		while ($i < $cnt) {
			if (count($THEMEREX_GLOBALS['wrappers']) == 0) break;
			$open_tag = array_pop($THEMEREX_GLOBALS['wrappers']);
			$tag = explode(' ', $open_tag, 2);
			$close_tag = str_replace('<', '</', $tag[0]).'>';
			$output .= "\n".str_repeat("\t", $level-$i).($close_tag).' <!-- '.str_replace(array('<', '>'), '', $close_tag.' '.$tag[1]).' -->';
			$i++;
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Open all wrappers
if (!function_exists('themerex_open_all_wrappers')) {
	function themerex_open_all_wrappers($echo=true) {
		global $THEMEREX_GLOBALS;
		$output = '';
		for ($i=0; $i<count($THEMEREX_GLOBALS['wrappers']); $i++) {
			$output .= "\n".str_repeat("\t", $i).($THEMEREX_GLOBALS['wrappers'][$i]);
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Close all wrappers without stack clear
if (!function_exists('themerex_close_all_wrappers')) {
	function themerex_close_all_wrappers($echo=true) {
		global $THEMEREX_GLOBALS;
		$output = '';
		for ($i=count($THEMEREX_GLOBALS['wrappers'])-1; $i>=0; $i--) {
			$tag = explode(' ', $THEMEREX_GLOBALS['wrappers'][$i]);
			$output .= "\n".str_repeat("\t", $i).str_replace('<', '</', $tag[0]).'>';
		}
		if ($echo) echo ($output);
		return $output;
	}
}


/* Tags
-------------------------------------------------------------------------------- */

// Return attrib from tag
if (!function_exists('themerex_get_tag_attrib')) {
	function themerex_get_tag_attrib($text, $tag, $attr) {
		$val = '';
		if (($pos_start = themerex_strpos($text, themerex_substr($tag, 0, themerex_strlen($tag)-1)))!==false) {
			$pos_end = themerex_strpos($text, themerex_substr($tag, -1, 1), $pos_start);
			$pos_attr = themerex_strpos($text, ' '.($attr).'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += themerex_strlen($attr)+3;
				$pos_quote = themerex_strpos($text, themerex_substr($text, $pos_attr-1, 1), $pos_attr);
				$val = themerex_substr($text, $pos_attr, $pos_quote-$pos_attr);
			}
		}
		return $val;
	}
}

// Set (change) attrib from tag
if (!function_exists('themerex_set_tag_attrib')) {
	function themerex_set_tag_attrib($text, $tag, $attr, $val) {
		if (($pos_start = themerex_strpos($text, themerex_substr($tag, 0, themerex_strlen($tag)-1)))!==false) {
			$pos_end = themerex_strpos($text, themerex_substr($tag, -1, 1), $pos_start);
			$pos_attr = themerex_strpos($text, $attr.'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += themerex_strlen($attr)+2;
				$pos_quote = themerex_strpos($text, themerex_substr($text, $pos_attr-1, 1), $pos_attr);
				$text = themerex_substr($text, 0, $pos_attr) . trim($val) . themerex_substr($text, $pos_quote);
			} else {
				$text = themerex_substr($text, 0, $pos_end) . ' ' . esc_attr($attr) . '="' . esc_attr($val) . '"' . themerex_substr($text, $pos_end);
			}
		}
		return $text;
	}
}




/* CSS values
-------------------------------------------------------------------------------- */

// Return string with position rules for the style attr
if (!function_exists('themerex_get_css_position_from_values')) {
	function themerex_get_css_position_from_values($top='',$right='',$bottom='',$left='',$width='',$height='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left','width','height');
		}
		$output = '';
		foreach ($top as $k=>$v) {
			$imp = themerex_substr($v, 0, 1);
			if ($imp == '!') $v = themerex_substr($v, 1);
			if ($v != '') $output .= ($k=='width' ? 'width' : ($k=='height' ? 'height' : 'margin-'.esc_attr($k))) . ':' . esc_attr(themerex_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
		}
		return $output;
	}
}

// Return string with paddings for the style attr
if (!function_exists('themerex_get_css_paddings_from_values')) {
	function themerex_get_css_paddings_from_values($padding_top='',$padding_right='',$padding_bottom='',$padding_left='') {
		if (!is_array($padding_top)) {
			$padding_top = compact('padding_top','padding_right','padding_bottom','padding_left');
		}
		$output = '';
		foreach ($padding_top as $k=>$v) {
			if ($v=='') continue;
			$imp = themerex_substr($v, 0, 1);
			if ($imp == '!') $v = themerex_substr($v, 1);
			$output .= str_replace('_', '-', $k) . ':' . trim(themerex_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
		}
		return $output;
	}
}

// Return value for the style attr
if (!function_exists('themerex_prepare_css_value')) {
	function themerex_prepare_css_value($val) {
		if ($val != '') {
			$ed = themerex_substr($val, -1);
			if ('0'<=$ed && $ed<='9') $val .= 'px';
		}
		return $val;
	}
}

// Return array with classes from css-file
if (!function_exists('themerex_parse_icons_classes')) {
	function themerex_parse_icons_classes($css) {
		$rez = array();
		if (!file_exists($css)) return $rez;
		$file = themerex_fga($css);
		if (count($file) == 0) return $rez;
		foreach ($file as $row) {
			if (themerex_substr($row, 0, 1)!='.') continue;
			$name = '';
			for ($i=1; $i<themerex_strlen($row); $i++) {
				$ch = themerex_substr($row, $i, 1);
				if (in_array($ch, array(':', '{', '.', ' '))) break;
				$name .= $ch;
			}
			if ($name!='') $rez[] = $name;
		}
		return $rez;
	}
}
	
// Return property value for specified selector from css-file
if (!function_exists('themerex_get_css_selector_property')) {
	function themerex_get_css_selector_property($css, $selector, $prop) {
		$rez = '';
		if (!file_exists($css)) return $rez;
		$file = themerex_fga($css);
		foreach ($file as $row) {
			if (($pos = themerex_strpos($row, $selector))===false) continue;
			if (($pos2 = themerex_strpos($row, $prop.':', $pos))!==false && ($pos3 = themerex_strpos($row, ';', $pos2))!==false && $pos2 < $pos3) {
				$rez = trim(chop(themerex_substr($row, $pos2+themerex_strlen($prop)+1, $pos3-$pos2-themerex_strlen($prop)-1)));
				break;
			}
		}
		return $rez;
	}
}

// Put theme custom styles into WP inline styles block
if (!function_exists('themerex_put_custom_styles')) {
	function themerex_put_custom_styles($css, $cond='', $expr='') {
		global $wp_styles;
		if (is_object($wp_styles)) {
			if ($wp_styles->add_data($css, $cond, $expr)) echo 'added';
		}
		return false;
	}
}

// Return minified custom styles to insert it into <head>
if (!function_exists('themerex_prepare_custom_styles')) {
	function themerex_prepare_custom_styles() {
		// Add theme specific custom css
		$css = apply_filters('themerex_filter_add_styles_inline', themerex_get_custom_styles());
		// Minify css string
		//$css = str_replace(array("\n", "\r", "\t"), '', $css);
		$css = themerex_minify_css($css);
		return $css;
	}
}

// Return theme custom styles
if (!function_exists('themerex_get_custom_styles')) {
	function themerex_get_custom_styles() {
		global $THEMEREX_GLOBALS;
		return !empty($THEMEREX_GLOBALS['custom_css']) ? $THEMEREX_GLOBALS['custom_css'] : '';
	}
}

// Add styles to the theme custom styles
if (!function_exists('themerex_add_custom_styles')) {
	function themerex_add_custom_styles($style) {
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['custom_css'] = (!empty($THEMEREX_GLOBALS['custom_css']) ? $THEMEREX_GLOBALS['custom_css'] : '') . "
			{$style}
		";
	}
}

// Minify CSS string
if (!function_exists('themerex_minify_css')) {
	function themerex_minify_css($css) {
		$css = preg_replace("/\r*\n*/", "", $css);
		$css = preg_replace("/\s{2,}/", " ", $css);
        //$css = str_ireplace('@CHARSET "UTF-8";', "", $css);
		$css = preg_replace("/\s*>\s*/", ">", $css);
		$css = preg_replace("/\s*:\s*/", ":", $css);
		$css = preg_replace("/\s*{\s*/", "{", $css);
		$css = preg_replace("/\s*;*\s*}\s*/", "}", $css);
        $css = str_replace(', ', ',', $css);
        $css = preg_replace("/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/", "", $css);
        return $css;
	}
}

// Minify JS string
if (!function_exists('themerex_minify_js')) {
	function themerex_minify_js($js) {
		$js = preg_replace('/([;])\s+/', '$1', $js);
		$js = preg_replace('/([}])\s+(else)/', '$1else', $js);
		$js = preg_replace('/([}])\s+(var)/', '$1;var', $js);
		$js = preg_replace('/([{};])\s+(\$)/', '$1\$', $js);
		return $js;
	}
}



/* Colors manipulations
-------------------------------------------------------------------------------- */

if (!function_exists('themerex_hex2rgb')) {
	function themerex_hex2rgb($hex) {
		$dec = hexdec(themerex_substr($hex, 0, 1)== '#' ? themerex_substr($hex, 1) : $hex);
		return array('r'=> $dec >> 16, 'g'=> ($dec & 0x00FF00) >> 8, 'b'=> $dec & 0x0000FF);
	}
}

if (!function_exists('themerex_hex2hsb')) {
	function themerex_hex2hsb ($hex) {
		return themerex_rgb2hsb(themerex_hex2rgb($hex));
	}
}

if (!function_exists('themerex_rgb2hsb')) {
	function themerex_rgb2hsb ($rgb) {
		$hsb = array();
		$hsb['b'] = max(max($rgb['r'], $rgb['g']), $rgb['b']);
		$hsb['s'] = ($hsb['b'] <= 0) ? 0 : round(100*($hsb['b'] - min(min($rgb['r'], $rgb['g']), $rgb['b'])) / $hsb['b']);
		$hsb['b'] = round(($hsb['b'] /255)*100);
		if (($rgb['r']==$rgb['g']) && ($rgb['g']==$rgb['b'])) $hsb['h'] = 0;
		else if($rgb['r']>=$rgb['g'] && $rgb['g']>=$rgb['b']) $hsb['h'] = 60*($rgb['g']-$rgb['b'])/($rgb['r']-$rgb['b']);
		else if($rgb['g']>=$rgb['r'] && $rgb['r']>=$rgb['b']) $hsb['h'] = 60  + 60*($rgb['g']-$rgb['r'])/($rgb['g']-$rgb['b']);
		else if($rgb['g']>=$rgb['b'] && $rgb['b']>=$rgb['r']) $hsb['h'] = 120 + 60*($rgb['b']-$rgb['r'])/($rgb['g']-$rgb['r']);
		else if($rgb['b']>=$rgb['g'] && $rgb['g']>=$rgb['r']) $hsb['h'] = 180 + 60*($rgb['b']-$rgb['g'])/($rgb['b']-$rgb['r']);
		else if($rgb['b']>=$rgb['r'] && $rgb['r']>=$rgb['g']) $hsb['h'] = 240 + 60*($rgb['r']-$rgb['g'])/($rgb['b']-$rgb['g']);
		else if($rgb['r']>=$rgb['b'] && $rgb['b']>=$rgb['g']) $hsb['h'] = 300 + 60*($rgb['r']-$rgb['b'])/($rgb['r']-$rgb['g']);
		else $hsb['h'] = 0;
		$hsb['h'] = round($hsb['h']);
		return $hsb;
	}
}

if (!function_exists('themerex_hsb2rgb')) {
	function themerex_hsb2rgb($hsb) {
		$rgb = array();
		$h = round($hsb['h']);
		$s = round($hsb['s']*255/100);
		$v = round($hsb['b']*255/100);
		if ($s == 0) {
			$rgb['r'] = $rgb['g'] = $rgb['b'] = $v;
		} else {
			$t1 = $v;
			$t2 = (255-$s)*$v/255;
			$t3 = ($t1-$t2)*($h%60)/60;
			if ($h==360) $h = 0;
			if ($h<60) { 		$rgb['r']=$t1; $rgb['b']=$t2; $rgb['g']=$t2+$t3; }
			else if ($h<120) {	$rgb['g']=$t1; $rgb['b']=$t2; $rgb['r']=$t1-$t3; }
			else if ($h<180) {	$rgb['g']=$t1; $rgb['r']=$t2; $rgb['b']=$t2+$t3; }
			else if ($h<240) {	$rgb['b']=$t1; $rgb['r']=$t2; $rgb['g']=$t1-$t3; }
			else if ($h<300) {	$rgb['b']=$t1; $rgb['g']=$t2; $rgb['r']=$t2+$t3; }
			else if ($h<360) {	$rgb['r']=$t1; $rgb['g']=$t2; $rgb['b']=$t1-$t3; }
			else {				$rgb['r']=0;   $rgb['g']=0;   $rgb['b']=0; }
		}
		return array('r'=>round($rgb['r']), 'g'=>round($rgb['g']), 'b'=>round($rgb['b']));
	}
}

if (!function_exists('themerex_rgb2hex')) {
	function themerex_rgb2hex($rgb) {
		$hex = array(
			dechex($rgb['r']),
			dechex($rgb['g']),
			dechex($rgb['b'])
		);
		return '#'.(themerex_strlen($hex[0])==1 ? '0' : '').($hex[0]).(themerex_strlen($hex[1])==1 ? '0' : '').($hex[1]).(themerex_strlen($hex[2])==1 ? '0' : '').($hex[2]);
	}
}

if (!function_exists('themerex_hsb2hex')) {
	function themerex_hsb2hex($hsb) {
		return themerex_rgb2hex(themerex_hsb2rgb($hsb));
	}
}


/* Other utils
-------------------------------------------------------------------------------- */

// Set e-mail content type
if (!function_exists('themerex_set_html_content_type')) {
	function themerex_set_html_content_type() {
		return 'text/html';
	}
}
?>