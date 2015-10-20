<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('Blog_model');
	}

	public function index()
	{

		if ($this->uri->segment('1') === 'blog') {
			redirect('news', '301');
		}

		$this->load->library('pagination'); 

		$config['per_page'] 	= 25;
		$config['uri_segment'] 	= 2;
		$config['base_url'] 	= base_url('news');

		$page 					= ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
		$where 					= array('status'=>'published','access'=>'public');
		$config['total_rows'] 	= $this->cache->model('Blog_model', 'count', array($where), 300);
		$posts 					= $this->cache->model('Blog_model', 'get_posts', array($where, 'id DESC', $config['per_page'], $page), 300);

		$this->pagination->initialize($config); 		
		$this->data['pagination'] = $this->pagination->create_links();


		$this->data['categories'] = $this->cache->model('Blog_model', 'get_categories', array(array('id >'=>'0','type'=>'public')), 900);


		$this->data['title'] = 'News on ' . $this->lang->line('meta_title');
		$this->data['posts'] = $posts;
		$this->_render('blog/index', $this->data);
	}


	public function category_view() {

		if (!$this->uri->segment('3')) {
			redirect('errors/page_missing', 'refresh');
		}


		$this->load->library('pagination'); 
		$config['per_page'] 	= 15;
		$config['uri_segment'] 	= 2;
		$config['base_url'] 	= base_url('news/category/' . $this->uri->segment('3'));

		$page 					= ($this->uri->segment(4) ? $this->uri->segment(4) : 0);
		$where 					= array('blog_posts.status'=>'published','blog_categories.title'=>$this->uri->segment('3'));
		
		$config['total_rows'] 	= $this->cache->model('Blog_model', 'count', array($where), 300);		
		$posts 					= $this->cache->model('Blog_model', 'get_posts', array($where, 'id DESC', $config['per_page'], $page), 300);
		
		if (!$posts) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->data['categories'] = $this->cache->model('Blog_model', 'get_categories', array(array('id >'=>'0', 'type'=>'public')), 900);
		$this->data['posts'] = $posts;

		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();

		$this->data['title'] = ucfirst($this->uri->segment('3')) . ' Category - ' . $this->lang->line('meta_title');

		$this->_render('blog/category_view', $this->data);
	}

	/**
	 * Single Post View. Displays a single post at base_url(blog/$category/$url)
	 * @param  string $category title of blog post category
	 * @param  string $url      blog post url
	 * @return View - displays single post view
	 */
	public function post($category, $url) {

		if (!$this->uri->segment('3')) {
			redirect('errors/page_missing', 'refresh');
		}

		$post = $this->cache->model('Blog_model', 'get_post', array(array('blog_posts.url'=>$url, 'blog_categories.title'=>$category)), 900);
		

		if (!$post) {
			redirect('errors/page_missing', 'refresh');
		}

		if ($post->access === 'private') {
			if (!$this->ion_auth->is_admin() && $this->ion_auth->logged_in() && $post->author != $this->ion_auth->user()->row()->id || !$this->ion_auth->logged_in()) {
				redirect('errors/page_missing', 'refresh');
			}
		}


		$this->data['post'] = $post;

		$featured_image = $this->cache->get('images/blog/' . $post->url);

		if (!$featured_image) {
			
			if (file_exists(FCPATH . 'asset_uploads/' . $post->username . '/blog/' . $post->url . '/600_' . $post->featured_image)) {
            	$image = base_url('asset_uploads/' . $post->username . '/blog/' . $post->url . '/600_' . $post->featured_image);
       		} elseif (file_exists(FCPATH . 'asset_uploads/' . $post->username . '/blog/' . $post->url . '/' . $post->featured_image)) {
            	$image = base_url('asset_uploads/' . $post->username . '/blog/' . $post->url . '/' . $post->featured_image);
            } else {
            	$image = base_url('resources/img/placeholders/300_playlist_img.jpg');
            }

			$this->cache->write($image, 'images/blog/' . $post->url);
			$this->data['featured_image'] = $image;
		} else {
			$this->data['featured_image'] = $featured_image;
		}

        $meta_description = (strlen($post->content) > 300) ? substr($post->content,0,297) : $post->content;
        $meta_description = strip_tags($meta_description);
        $this->data['post_summary'] = $meta_description;

		$this->data['meta_name'] = array(
			'twitter:card'=>'summary_large_image',
			'twitter_site'=>'@hiphopvipcom',
			'twitter:creator'=>'@hiphopvipcom',
			'twitter:title'=>htmlspecialchars($post->title, ENT_QUOTES),
			'twitter:description'=>htmlspecialchars(strip_tags($meta_description), ENT_QUOTES),
			'twitter:image:src'=> $this->data['featured_image']
			);

		$this->data['meta_prop'] = array(
			'og:title'=> htmlspecialchars($post->title, ENT_QUOTES),
			'og:url'=> base_url('b/'. $post->category_title . '/' . $post->url),
			'og:image'=> $this->data['featured_image'],
			'og:site_name'=> 'hiphopVIP',
			'og:description'=> htmlspecialchars(strip_tags($meta_description), ENT_QUOTES)
			);

		$this->data['other_posts'] = $this->cache->model('Blog_model', 'get_posts', array(array('blog_posts.title !='=>$post->title,'status'=>'published', 'blog_categories.title'=>$category), 'id DESC', 5, 0), 900);

		$this->data['vendorCSS'] = array('social-likes/social-likes_classic.css');
		$this->data['vendorJS'] = array('social-likes/social-likes.min.js');
		$this->data['title'] = $post->title . ' on ' . $this->lang->line('meta_title');

		$this->_render('blog/single_post', $this->data);
	}


	/**
	 * View and form to create a post, returns success/error message via add_post function
	 * @return view
	 */
	public function create_post() {

		if (!$this->ion_auth->is_admin()) {
			redirect('errors/page_missing', 'refresh');
		}
		
		$this->load->helper('form');

		$this->data['categories'] = $this->Blog_model->get_categories(array('id >'=>'0'));
		
		$this->data['form_attributes'] = array('id'=>'new_post');
		$this->data['form_title'] = array('name'=>'title','id'=>'title','type'=>'text','size'=>'50','class'=>'form-control','placeholder'=>'Post Title');
     	$this->data['form_content'] = array('name'=>'post_content','id'=>'post_content','type'=>'text','size'=>'50','class'=>'form-control','placeholder'=>'Post Content','style'=>'margin-top:5px');
		$this->data['form_video'] = array('name'=>'video','id'=>'video','type'=>'text','size'=>'50','class'=>'form-control','placeholder'=>'Youtube Video (optional)','style'=>'margin-top:5px');

		$this->data['vendorJS'] = array('jquery.form.js','ckeditor/ckeditor.js');
		$this->data['vendorCSS'] = array('forms.css');
		$this->data['noSidebar'] = true;
		$this->_render('blog/admin/create_post', $this->data);

	}

	/**
	 * AJAX is posted to this
	 * Submits new post data to database and returns fail/success json
	 * @return  json validation and response success/fail error message
	 */
	public function add_post(){
		//ajax add post function used in conjunction with create_post
		if (!$this->ion_auth->is_admin()) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'Post Title', 'trim|required|min_length[2]|max_length[500]|xss_clean');
        $this->form_validation->set_rules('post_content', 'Post Content', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('video', 'Post Video', 'trim|min_length[2]|xss_clean');


		//check if there is an existing post
		$existing_post = $this->Blog_model->get_post(array('blog_posts.url'=>url_slug($this->input->post('title'))));

		if ($existing_post) {
			$output = array('validation'=>'error','response'=>'error','message'=>'Post Title/URL Already Exists');	
		} else {

			if ($this->form_validation->run() == FALSE) {
				$output = array('validation' => 'error', 'message' => validation_errors('<div class="alert alert-error"><strong>Error!</strong> ', '</div>'));
			} else {

				//if no category is selected, set category to 1 [[ uncategorized/news ]]
				$category = (!empty($this->input->post('category')) ? $this->input->post('category') : 1);

		         //make sure its a youtube video
		        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $this->input->post('video'))) { 
		            parse_str( parse_url($this->input->post('video'), PHP_URL_QUERY),$my_array_of_vars);
		            $video = $my_array_of_vars['v'];
		        } else {
		            $video = '';
		        } //if youtube

                if (isset($_FILES['featured_image'])) {
                    $uploads = $_FILES['featured_image'];

                    if ($uploads['error'] == 0) {
                        $this->load->library('images');
                        $this->images->uploadLocalFile($uploads, url_slug($this->input->post('title')), 'blog/' . url_slug($this->input->post('title')));

                            $ext = pathinfo($uploads['name']);
                            $ext = $ext['extension'];
                            $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username . '/blog/' . url_slug($this->input->post('title')) . '/';
                            

                            $file_path = $image_dir . url_slug($this->input->post('title')) . '.' . $ext;
                           	$size = getimagesize($file_path);

                           	if ($size[0] > 600) {
	                            $size_array = array('64','150','300','600');                       		
                           	} else {
                           		$size_array = array('64','150','300');
                           	}
                            
                            foreach ($size_array as $size) {
                                $resize = $this->images->resizeImage($image_dir, url_slug($this->input->post('title')), $ext, $size);
                            }
                            
                            if (!$resize) {
                            	print_r('IMAGE UPLOAD RESIZE FAILED');
                                $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to resize image');
                                $this->output->set_output(json_encode($output_array));
                            }

                        $featured_image = url_slug($this->input->post('title')) .'.'. $ext;
                    }
                } else {
                	$featured_image = '';
                }

					$data = array(
						'author'=>$this->ion_auth->user()->row()->id,
						'date_created'=>time(),
						'date_published'=>time(),
						'title'=>$this->input->post('title'),
						'content'=>$this->input->post('post_content'),
						'video'=>$video,
						'category'=>$category,
						'url'=>url_slug($this->input->post('title')),
						'status'=>'published',
						'access'=>$this->input->post('access'),
						'featured_image'=>$featured_image
						);

					$add = $this->Blog_model->add_post($data);

					if ($add) {
						$post = $this->Blog_model->get_post(array('blog_posts.id'=>$add));

						$output = array('validation'=>'valid','response'=>'success','message'=>'Post Added','post_details'=>array('id'=>$add,'url'=>url_slug($this->input->post('title')),'category'=>$post->category_title,'title'=>$this->input->post('title')));	
					} else {
						$output = array('validation'=>'valid','response'=>'error','message'=>'Unable to add post');
					} //if add
				} // if validation
		}// if existing post

		$this->output->set_output(json_encode($output));
	}

/**
 * Edit Post View
 * @param  int $id - post ID
 * @return view - Display Edit Post page view
 */
	public function edit_post($id){
		if (!$this->ion_auth->is_admin()) {
			redirect('errors/page_missing', 'refresh');
		}
		
		$this->load->helper('form');

		$post = $this->Blog_model->get_post(array('blog_posts.id'=>$id));
		if (!$post) {
			redirect('backend/blog', 'refresh');
		}

		$this->data['form_id'] = array('name'=>'id','id'=>'post_id','type'=>'hidden','value'=>$post->id);
		$this->data['form_title'] = array('name'=>'title','id'=>'title','type'=>'text','size'=>'50','class'=>'form-control','value'=>$post->title,'placeholder'=>'Post Title','style'=>'margin-bottom:5px');
     	$this->data['form_content'] = array('name'=>'post_content','id'=>'post_content','type'=>'text','size'=>'50','class'=>'form-control','value'=>$post->content,'placeholder'=>'Post Content','style'=>'margin-top:5px');
		$this->data['form_video'] = array('name'=>'video','id'=>'video','type'=>'text','size'=>'50','class'=>'form-control','value'=>$post->video,'placeholder'=>'Youtube Video','style'=>'margin-top:5px');

		$this->data['categories'] = $this->Blog_model->get_categories(array('id >'=>'0'));


		$this->data['vendorJS'] = array('jquery.form.js','ckeditor/ckeditor.js');
		$this->data['vendorCSS'] = array('forms.css');
		$this->data['noSidebar'] = true;

		$this->data['post'] = $post;

		$this->_render('blog/admin/edit_post', $this->data);
	}

	/**
	 * AJAX posts to update_post from the edit_post view.
	 * Returns validation and update pass/fail response
	 * @return json - returns an array of song data if successful, or an error message if validation or update fails
	 */
	public function update_post() {

		if (!$this->ion_auth->is_admin()) {
			redirect('errors/page_missing', 'refresh');
		}
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
     	$this->load->library('form_validation');


        $this->form_validation->set_rules('title', 'Post Title', 'trim|required|min_length[2]|max_length[500]|xss_clean');
        $this->form_validation->set_rules('post_content', 'Post Content', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('video', 'Post Video', 'trim|min_length[2]|xss_clean');


		//if no category is selected, set category to 1 [[ uncategorized/news ]]
		$category = (!empty($this->input->post('category')) ? $this->input->post('category') : 1);

         //make sure its a youtube video
        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $this->input->post('video'))) { 
            parse_str( parse_url($this->input->post('video'), PHP_URL_QUERY),$my_array_of_vars);
            $video = $my_array_of_vars['v'];
        } else {
            $video = '';
        }

		$where = array('id'=>$this->input->post('id'));
		$raw_data = array(
			'title'=>$this->input->post('title'),
			'content'=>$this->input->post('post_content'),
			'video'=>$video,
			'category'=>$category
		);

		$data = array_filter($raw_data);

		if ($this->form_validation->run() == FALSE) {
			$output = array('validation' => 'error', 'message' => validation_errors('<div class="alert alert-error"><strong>Error!</strong> ', '</div>'));
		} else {
			$update = $this->Blog_model->update_post($where, $data);


			if ($update) {			
				$post = $this->Blog_model->get_post(array('blog_posts.id'=>$this->input->post('id')));
				$output = array('validation'=>'valid','response'=>'success','message'=>'Post Updated', 'post_details'=>array('title'=>$post->title,'category'=>$post->category_title,'url'=>$post->url));
				
				$this->cache->model('Blog_model', 'get_post', array(array('blog_posts.url'=>$post->url, 'blog_categories.title'=>$post->category_title)), -1);
			

			} else {
				$output = array('validation'=>'valid','response'=>'error','message'=>'The post was not updated. Did you make any changes?');
			}
		}

		$this->output->set_output(json_encode($output));
	}

	/**
	 * AJAX function, deletes a post from the database
	 * @return json - returns success/error message upon ajax post
	 */
	public function delete_post() {

      	$this->output->set_header('Content-Type: application/json; charset=utf-8');

        if (!$this->ion_auth->is_admin()) {
        	redirect('errors/page_missing', 'refresh');
        }

        $where = array('id'=> $this->input->post('id'));

        $delete = $this->Blog_model->delete_post($where);
        
        if ($delete) {
			$this->session->set_flashdata('admin_blog_message', '<div class="alert alert-success" style="text-align:center">Successfully Deleted Blog Post!</div>');
        	$output = array('response'=>'success','message'=>'Successfully deleted post');
        } else {
        	$output = array('reponse'=>'error','message'=>'Failed to delete post.');
        }

        $this->output->set_output(json_encode($output));
	}

	public function add_category() {
//ajax add post function used in conjunction with create_post
		if (!$this->ion_auth->is_admin()) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$this->load->library('form_validation');

        $this->form_validation->set_rules('new_category', 'Category Name', 'trim|required|min_length[2]|max_length[255]|xss_clean');

        $new_category = url_slug($this->input->post('new_category'));

		//check if there is an existing post
		$existing = $this->Blog_model->get_category(array('title'=>$new_category));

		if ($existing) {
			$output = array('validation'=>'error','response'=>'error','message'=>'Category already exists.');	
		} else {

			if ($this->form_validation->run() == FALSE) {
				$output = array('validation' => 'error', 'message' => validation_errors());
			} else {

					$add = $this->Blog_model->add_category(array('title'=>$new_category));

					if ($add) {
						$output = array('validation'=>'valid','response'=>'success','message'=>'Category Added');	
					} else {
						$output = array('validation'=>'valid','response'=>'error','message'=>'Category already exists');
					} //if add
				} // if validation
		}// if existing category

		$this->output->set_output(json_encode($output));
	}


	public function upload_photo() {	
        	if (!isset($_GET['client'])) {
        		redirect('/','refresh');
        	}
        	if (!$this->ion_auth->is_admin()) {
        		redirect('/','refresh');
        	}
	              require_once FCPATH . '/resources/vendor/ckeditor/plugins/doksoft_uploader/uploader.php';
                // script will run and die with AJAX response

        }


}//end class
 
/* End of file blog.php */
/* Location: ./application/controllers/blog.php */