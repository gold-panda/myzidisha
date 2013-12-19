<?php
require_once(dirname(__FILE__).'/captcha.class.php');

$img=new animated_captcha();

// Session name to store key
$img->session_name='SD32FFD133SFWE3RWER1212SEFSDF';

// Magic words, used in session encryption
// - optional
// - recommended to fill magic words
//   with your specific words
// - default is empty
$img->magic_words('ItsnotmagIC');

// Grid (line) color (array)
// Can be used to randomize grid color
$img->grid_color(array('#63A595','#8FD67F'));

// Text color (array)
// Can be used to randomize letter color
$img->text_color(array('#CD1B2D', '#950FC8', '#660033', '#006633', '#0D47B3', '#6600CC', '#000099'));

// Number of image frames
// - optional
// - default is 3
// Using more frames, causes larger file size
$img->frame_number(2);

// Frame delay
// - optional
// - default is 80
// Small value means faster animation, 
// Higher value means slower animation.
$img->frame_delay(80);

$img->use_background(true);// use background ?
$img->use_distortion(true);// distort image ?
$img->distortion_type('normal');// normal | slice | wave | random
$img->use_grid(true);

// Required, to generate our image
$img->generate();
?>