<?php 
class SCRAPOST_Ajax_Admin 
{

    public function scrapost() 
    {
        if( isset( $_POST[ 'action' ] ) ) 
        {

            $number = $_POST['number'];

            $postype = $_POST['postype'];
            if(!$postype) $postype = 'post';
            
            $category_term = $_POST['category'];
            if(!$category_term) $category_term = 'category';

            if( !$number || $number == '' || $number == 0 ){

                $msg = 'The number must be different from zero';
                $result = null;
                
            } else {

                $tags = [ 'red', 'purple', 'blue', 'orange', 'yellow', 'brown', ' black', 'white', 'carrot', 'laptop', 'music', 'tiger', 'house' ];

                $msg      = 'It was scripted correctly';
                $url_api  = 'http://api.mediastack.com/v1/news?access_key=f2f33888eb14f62f168e6874889f4e4b&languages=en&limit=' . $number;
                $contents = file_get_contents($url_api);
                $result   = json_decode($contents);

                foreach ( $result->data as $key => $res ) 
                {
                    $title    = $res->title;
                    $content  = $res->description;
                    $category = $res->category;
                    $image    = $res->image;

                    if($image) {
                        $image = strtok($image, "?");
                    } else {
                        $files_images = preg_grep('~\.(jpeg|jpg|png)$~', scandir(SCRAPOST_DIR_PATH. 'admin/img'));
                        shuffle($files_images);
                        $image = SCRAPOST_DIR_URI . 'admin/img/' . $files_images[0]; 
                    }

                    /* Tags */
                    $rand_keys = array_rand($tags, 3);
                    $tag       = [ $tags[$rand_keys[0]], $tags[$rand_keys[1]], $tags[$rand_keys[2]] ]; 

                    /* insert */
                    $new_post = array(
                        'ID'            => '',
                        'post_title'    => $title,
                        'post_content'  => $content,
                        'post_status'   => 'publish',
                        'post_type'     => $postype,
                    );
                    $post_id = wp_insert_post($new_post);
                    $default_category = (int)get_option('default_category');
                    wp_remove_object_terms($post_id, $default_category, 'category');
                    wp_set_object_terms( $post_id, $category, $category_term, true );
                    wp_set_object_terms( $post_id, $tag, 'post_tag', true );

                    if($image){
                        Scrapost_Generate_Featured_Image( $image, $post_id );
                    }
                }

            }

            
    
            $res = [
                'res'    => $msg,
                'data'   => $result->data,
                'category' => $category
            ];
            echo json_encode($res);
            wp_die();
        }
    }
   
}