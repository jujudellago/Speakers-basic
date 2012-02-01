<?php
/*
Plugin Name: Basic speakers
Plugin URI: http://yabo-concept.ch
Description: Shows the conference speakers in a shortcode
Version: 1.0
Author: Julien Ramel
Author URI: http://yabo-concept.ch
*/



function my_basic_speakers($atts=array(),$content=null,$code=''){
	$year = isset($atts['year']) ? $atts['year'] : date("Y"); // let the year be specified in the shortcode
	$speaker_page = $atts['speaker_page']; // This should be a URL to a page that includes the shortcode [the_conference_lineup year="my year"] with the addition of ?artist= (or &artist=) as the speaker id will get filled in below
	$Bootstrap = Bootstrap::getBootstrap(); // The Top Quark bootstrap;
	$Bootstrap->usePackage('FestivalApp'); // fire up The Conference Plugin
	$ConferenceContainer = new FestivalContainer();
	$Conference = $FestivalContainer->getFestival($year);
	if (!is_a($Conference,'Festival') or !$Conference->getParameter('FestivalLineupIsPublished')){ 
		return 'Not published yet';
	} 
	$Speakers = $Conference->getLineup();

	// Here's where you make your markup
	$return = '';
	foreach ($Speakers as $SpeakerID => $Speaker){
		$return.= '<div class="speaker">';
		$return.= '	<div class="speaker-name"><a href="'.$speaker_page.$SpeakerID.'">'.$Speaker->getParameter('ArtistFullName').'</a></div>';
		$Speaker->parameterizeAssociatedMedia(); // prepares the images
		$Images = $Speaker->getParameter('ArtistAssociatedImages');
		if (count($Images)){
			$return.= '<div class="speaker-image"><img src="'.$Images[0]['Thumb'].'"/></div>';
		}
		$return.= '<div class="speaker-description">'.$Speaker->getParameter('ArtistDescription').'</div>';
		$return.= '</div> <!-- .speaker -->';
	}
	return $return;
}

add_filter('the_conference_basic_speakers', 'my_basic_speakers');
?>