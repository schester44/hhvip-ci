<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = 'errors/page_missing';


$route['ajax/events/(:any)']				= "ajax/events/$1";

/**
 * BLOG
 */
$route['news']  							= "blog/index";
$route['news/:num']							= "blog/index/$1";
$route['news/category/(:any)']				= "blog/category_view";
$route['news/category/(:any)/(:num)']		= "blog/category_view/$1";

$route['b/(:any)/(:any)']					= "blog/post/$1/$2";
$route['backend/blog/add']					= "blog/create_post";
$route['backend/blog/add/category']			= "blog/add_category";
$route['backend/blog/add/post']				= "blog/add_post";
$route['backend/blog/update/post']			= "blog/update_post";
$route['backend/blog/delete/post']			= "blog/delete_post";
$route['backend/blog/edit/(:num)']			= "blog/edit_post/$1";

//END BLOG

//RSS Feeds
$route['feed/(:any)']						= "feed/index/$1";
$route['songs/error']						= 'songs/error';

/**** MANAGEMENT ****/
$route['manage/song/remove/(:any)/(:any)'] 	= "songs/delete_song_from_s3/$1/$2";
$route['manage/song/(:any)/edit'] 			= "songs/edit/$1";
$route['manage/mixtape/(:any)/edit'] 		= "mixtapes/edit/$1";
//song/update is the ajax route for edit_song
$route['manage/song/update'] 				= "songs/update";
$route['manage/song/delete'] 				= "songs/delete";
$route['manage/songs/(:any)/(:any)'] 		= "manage/songs/$1/$2";
$route['manage/mixtape/delete'] 			= "mixtapes/delete";
$route['manage/mixtape/update'] 			= "mixtapes/update";

//these two should be under mixtapes
$route['manage/mixtape/sort-order/(:any)']  				= "manage/update_sort_order/$1";
$route['manage/mixtape/update-track/(:any)/(:any)/(:any)']  = "manage/update_track_info/$1/$2/$3";

$route['manage/song/admin/(:any)/delete'] 		= "backend/simple_admin_delete_song/$1";
$route['manage/mixtape/admin/(:any)/delete'] 	= "backend/simple_admin_delete_mixtape/$1";
$route['manage/song/feature/(:any)/(:any)'] 	= "manage/feature/$1/$2";
$route['manage/song/promote/(:any)/(:any)'] 	= "manage/promote/$1/$2";

/**** UPLOADING ****/
$route['manage/process/(:any)'] 			= "upload/init_song_upload/$1";
$route['manage/scup']						= "upload/init_soundcloud";
$route['upload/mixtape/init']				= "upload/init_mixtape_upload";
$route['manage/finish/(:any)'] 				= "upload/publish_song_upload/$1";


$route['playlist']								= "playlists/index";
$route['song']									= "songs/index";


/**** PLAYLISTS ****/
$route['playlist/artist/(:any)']				= "playlists/artist/$1";
$route['playlist/(:any)/(:any)']				= "playlists/player/$1/$2";
$route['song/(:any)/(:any)/playlist']			= "playlists/ajax_add_to_playlist/$1/$2";

$route['manage/playlist/update'] 				= "playlists/update";
$route['manage/playlist/delete'] 				= "playlists/delete";
$route['manage/playlist/delete-track'] 				= "playlists/delete_track";
$route['manage/playlist/sort-order/(:any)']  	= "playlists/update_sort_order/$1";
$route['manage/playlist/(:any)/(:any)'] 		= "playlists/edit/$1/$2";



/**** PROFILE / SOCIAL ****/
$route['song/(:any)/(:any)/favorite']			= "social/favorite/$1/$2";

$route['u/(:any)/stats']				= "user/stats";
$route['u/(:any)/stats/(:any)']				= "user/stats/$1";

//first favorites entry is for pagination, not sure why it doesnt work with just 1
$route['u/(:any)/favorites/:num']				= "social/favorites/$1/$2";
$route['u/(:any)/favorites']					= "social/favorites/$1";
//first following entry is for pagination, not sure why it doesnt work with just 1
$route['u/(:any)/following/:num']					= "social/following/$1/$2";
$route['u/(:any)/following']					= "social/following/$1";
//first followers entry is for pagination, not sure why it doesnt work with just 1
$route['u/(:any)/followers/:num']					= "social/followers/$1/$2";
$route['u/(:any)/followers']					= "social/followers/$1";

$route['u/(:any)/follow']						= "social/follow/$1";
$route['u/(:any)/playlists']					= "social/playlists/$1";
$route['u/(:any)']								= "user/profile_page/$1";

//VIDEO PAGES
$route['videos/(:num)']						= "videos/index/$1";
$route['videos/edit/(:any)'] 				= "videos/edit/$1";
$route['videos/delete/(:any)'] 				= "videos/simple_delete/$1";
$route['videos/(:any)/(:any)']				= "videos/play/$1/$2";
$route['manage/video/delete'] 				= "videos/delete";
$route['manage/video/update'] 				= "videos/update";


//AUDIO PAGES
$route['radio'] 							= "radio/index";
$route['radio/(:any)']						= "radio/user_radio/$1";
$route['song/(:any)/(:any)'] 				= "player/main/$1/$2";
$route['mixtape/(:any)/(:any)'] 			= "mixtapes/player/$1/$2";

/**** EMBED PLAYER ****/
$route['embed/mixtape/(:any)/(:any)/(:any)']	= "mixtapes/embed_player/$1/$2/$3";
$route['embed/playlist/artist/(:num)/(:any)']	= "playlists/artist_embed_player/$1/$2";
$route['embed/playlist/(:any)/(:any)/(:any)']	= "playlists/embed_player/$1/$2/$3";
$route['embed/(:any)/(:any)/(:any)'] 			= "player/embed/$1/$2/$3";

/**** DOWNLOADING ****/
//download $1 = username || $2 = song_url | $3 = timestamp  | $4 = md5 hash (set in player_page)
$route['download/tape-track/(:any)/(:any)']			= "mixtapes/download_single_track/$1/$2/$3/$4/$5";
$route['download/(:any)/(:any)']					= "mixtapes/download_mixtape/$1/$2/$3/$4/$5";

/**** LISTS ****/
$route['songs/(:any)'] 								= "lists/song_list/$1";
$route['songs/popular/(:any)']						= "lists/song_list/$1/$2";
$route['mixtapes/(:any)'] 							= "lists/mixtape_list/$1";
$route['mixtapes/popular/(:any)']					= "lists/mixtape_list/$1/$2";
$route['playlists/(:any)'] 							= "lists/playlist_list/$1";
$route['playlists/popular/(:any)']					= "lists/playlist_list/$1/$2";

/* seo and necessary stuff for url redirects from vip 1.0 */
$route['content/plugins/post_audio/embed.php'] = "player/old_embed_redirect";

// sort of a hack.. we must setup routes for all base controllers since routes without a slash go to songs/old_url_redirect
$explicit_routes = array('auth','backend','blog','errors','feed','lists','manage','mixtapes','oembed','player','playlists','search','site','songs','upload','videos','votes');
foreach ($explicit_routes as $exp) {
	$route[$exp] = $exp;
}
//if route doesnt have a slash in it, route to this controller
$route['([^/]+)/?']								= "songs/old_url_redirect/$1";



/* End of file routes.php */
/* Location: ./application/config/routes.php */