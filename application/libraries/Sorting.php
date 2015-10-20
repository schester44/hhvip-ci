<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Sorting {

	private $CI;

	  function __construct()
    {
        $this->CI =& get_instance();
    }

    public function score($ups, $downs) {
    	return $ups - $downs;
    }


    public function hot($ups, $downs, $date) {
        return $this->_hot($ups,$downs,$date);
    }

    function _hot($ups,$downs,$date) {
        $score = $this->score($ups, $downs);
        $order = log10(max(abs($score), 1));
        $seconds = $date - 1134028003;

        if ($score > 0) {
            $sign = 1;
        } elseif ($score < 0) {
            $sign = -1;
        } else {
            $sign = 0;
        }

        return round($sign * $order + $seconds / 45000, 7);
    }

    /* get mixtapes and songs list */
    public function get_list($listType, $sortType, $limit="", $start="", $popSort="") {

        $prep = $this->prepareList($listType, $sortType, $popSort);

        if ($listType == 'songs') {
            $model = 'Song_model';
        } elseif ($listType == 'mixtapes') {
            $model = 'Mixtape_model';
        } else {
            $model = 'Song_model';
        }

        $list = $this->CI->cache->model($model, 'get_list', array($prep['where'], $prep['order'], $limit, $start), 300);

        //return $this->scrub_posts($list);
        return $list;
    
    }


    /* filters out songs from the lists if there are more than 5 posts in a row with the same artist from the same user
    * user can stil post however only the latest 5 songs will be shown.
    */
    public function scrub_posts($array) {
            $dup = null;
            $count = 1;
            $list = array();
            if (is_array($array)) {
            foreach ($array as $key => $v) {
                if (!empty($dup) && !$this->CI->ion_auth->is_admin($v->user_id) && !$this->CI->ion_auth->is_moderator($v->user_id)) {
                    if ($dup->user_id == $v->user_id) {
                        if ($dup->song_artist == $dup->song_artist) {
                            $count++;
                            if ($count > 5) { 
                                continue;
                            }
                        }
                    }
                }
                $dup = $v;
                $results[$key] = $v;
            } /** end **/
        return $results;
        }
    }

    public function list_title($type,$sort) {

        if ($sort == 'popular') {
            if ($type == 'mixtapes') {
                return $this->CI->lang->line('mixtape_list_popular');
            } else {
                return $this->CI->lang->line('song_list_popular');
            }
        } elseif ($sort == 'latest') {
            if ($type == 'mixtapes') {
                return $this->CI->lang->line('mixtape_list_latest');
            } else {
                return $this->CI->lang->line('song_list_latest');
            }
        } elseif ($sort == 'trending') {
            if ($type == 'mixtapes') {
                return $this->CI->lang->line('mixtape_list_trending');
            } else {
                return $this->CI->lang->line('song_list_trending');
            }
        } elseif ($sort == 'featured') {

        } else {
           return false;
        }
    }
    /**
     * SQL Query params based on list type
     * @param  string $listType  (mixtape/playlist/songs, .. i think)
     * @param  string $sortType  (latest/popular/trending)
     * @param  string $popSort  if sorting by popular, this will have the age/length (week/month/year)
     * @return array returns the WHERE, ORDER, and PAGE_TITLE for the list
     */
    public function prepareList($listType, $sortType, $popSort='') {

        switch ($sortType) {
            case 'popular':
                $this->data['where']        = array('status' => 'published','upvotes >'=>'3','visibility'=>'public','published_date >'=>date(strtotime("-7 days")));
                $this->data['order']        = 'upvotes desc, published_date desc';
                $this->data['page_title']   = 'Popular ' . ucfirst($listType);

                if (!empty($popSort)) {
                    $this->data['where'] = $this->popularSort($popSort);
                    $this->data['order'] = 'upvotes DESC';
                    $this->data['page_title']   = 'Popular '  . ucfirst($listType) . ' this ' . $popSort;
                }
                break;

            case 'trending':
                $this->data['where']        = array('status' => 'published','visibility'=>'public','upvotes >'=>'5','published_date >'=>date(strtotime("-14 days")));
                $this->data['order']        = 'hotness DESC, published_date desc';
                $this->data['page_title']   = 'Trending ' . ucfirst($listType);
                break;

            case 'promoted':
                $this->data['where']        = array('status' => 'published','visibility'=>'public','promoted'=>'yes','promoted_date >'=>date(strtotime("-7 days")));
                $this->data['order']        = 'published_date DESC';
                $this->data['page_title']   = 'Featured ' . ucfirst($listType);
                break;

            case 'featured':
                $this->data['where']        = array('status' => 'published','visibility'=>'public','featured'=>'yes','published_date >'=>date(strtotime("-3 days")));
                $this->data['order']        = 'published_date DESC';
                $this->data['page_title']   = 'Featured ' . ucfirst($listType);
                break;

            case 'latest':
                default:
                $this->data['where']        = array('status' => 'published','visibility'=>'public','published_date >'=>date(strtotime("-1 year")));
                $this->data['order']        = 'published_date DESC';
                $this->data['page_title']   = 'Latest ' . ucfirst($listType);
                break;
        }

        return $this->data;     
    }


    function popularSort($popSort) {

        switch ($popSort) {
            case 'week':
                $time = '-7 days';
                break;

            case 'month':
                $time = '-30 days';
                break;

            case 'year':
                $time = '-1 year';
                break;

            case 'all':
                $time = '-5 years';
                break;

            case 'today':
                default:
                $time = '-1 day';
                break;
        }

        return array(
            'status'=>'published',
            'visibility'=>'public',
            'published_date >'=>date(strtotime($time)),
            'upvotes >'=>'1');
    }
}
/* End of file Sorting.php */