<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Detecting youtube or video link
 *
 * @param $url
 *
 * @return string
 */
function gallery_video_youtube_or_vimeo( $url ) {
	if ( strpos( $url, 'youtube' ) !== false || strpos( $url, 'youtu' ) !== false ) {
		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
			return 'youtube';
		}
	} elseif ( strpos( $url, 'vimeo' ) !== false ) {
		$explode = explode( "/", $url );
		$end     = end( $explode );
		if ( strlen( $end ) == 8 || strlen( $end ) == 9 ) {
			return 'vimeo';
		}
	}elseif ( strpos( $url, 'youku' ) !== false) {
		if ( preg_match( '/sid\/([^\_])/', $url, $match ) ) {
            return 'youku';
        }
	}

	return 'image';
}

/**
 * Returns Youtube or Vimeo URL ID
 *
 * @param $url
 *
 * @return array
 */
function gallery_video_get_video_id_from_url( $url ) {
	if ( strpos( $url, 'youtube' ) !== false || strpos( $url, 'youtu' ) !== false ) {
		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
			return array( $match[1], 'youtube' );
		}
	} else if( strpos( $url, 'youku.com' ) !== false ){
        if(preg_match('/sid\/([^\_]+)/',$url,$matches)){
            $query = explode('/',$matches[1]);
            return array( $query[0], 'youku' );
        }
	} else {
        $vimeoid = explode( "/", $url );
        $vimeoid = end( $vimeoid );

        return array( $vimeoid, 'vimeo' );
    }
}

function get_youku_id_from_url($url){
    if(preg_match('/sid\/([^\_]+)/',$url,$matches)){
        $query = explode('/',$matches[1]);
        return $query[0];
    }
    return false;
}

function send_request_get($url){
    $curlObject = curl_init();
    curl_setopt($curlObject, CURLOPT_URL, $url);
    curl_setopt($curlObject, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curlObject, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlObject, CURLOPT_HEADER, false);
    curl_setopt($curlObject, CURLOPT_POST, false);
    curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlObject, CURLOPT_CONNECTTIMEOUT, 600);
    $response = curl_exec($curlObject);
    curl_close($curlObject);
    return  $response;
}

function get_youku_image_from_url($url){
    if($id = get_youku_id_from_url($url)){
        $id = rtrim($id,'==');
        $link = "https://openapi.youku.com/v2/videos/show.json?video_id={$id}&client_id=b10ab8588528b1b1";
        $response = send_request_get($link);
        if ($response) {
            $response = json_decode($response);
            $data['img'] = $response->bigThumbnail;
            $data['title'] = $response->title;
            $data['url'] = $url;
            return $data['img'];
        } else {
            return false;
        }
    }else{
        return false;
    }
}

function get_youku_duration_from_url($url){
    if($id = get_youku_id_from_url($url)){
        $id = rtrim($id,'==');
        $link = "https://openapi.youku.com/v2/videos/show.json?video_id={$id}&client_id=b10ab8588528b1b1";
        $response = send_request_get($link);
        if ($response) {
            $response = json_decode($response);
            $data['img'] = $response->bigThumbnail; // 视频缩略图
            $data['title'] = $response->title; //标题啦
            $data['url'] = $url;
            $data['duration'] = $response->duration;
            return date('H:i:s',$data['duration']);
        } else {
            return false;
        }
    }else{
        return false;
    }
}
function get_youtube_vimeo_duration_from_url($url)
{
    $videourl = gallery_video_get_video_id_from_url($url);
    if ($videourl[1] == 'youtube') {
        $vid = $videourl[0];
        $link = "https://www.googleapis.com/youtube/v3/videos?id=" . $vid . "&part=contentDetails,statistics&key=AIzaSyDen99uTRRPl9VT_Ra9eAisvZSc4aZDEns";
        $response = send_request_get($link);
        if ($response) {
            $result = json_decode(json_encode($response),true);
            $duration = $result['items'][0]['contentDetails']['duration'];
            $duration = new DateInterval($duration);
            return $duration->format('%H:%i:%s');
        } else {
            return false;
        }
    } elseif ($videourl[1] == 'vimeo') {
        $vimeoId = $videourl[0];
        $link = "https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/{$vimeoId}";
        $response = send_request_get($link);
        if ($response) {
            $response = json_decode($response);
            $duration = $response->duration;
            return date('H:i:s',$duration);
        } else {
            return false;
        }
    }
}