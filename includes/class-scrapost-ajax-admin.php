<?php 
class SCRAPOST_Ajax_Admin 
{

    public function get_tags($number){

        $tags_list = [
            'Política',
            'Economia',
            'Cultura',
            'Deportes',
            'Sociedad',
            'Tecnología',
            'Gente',
            'Opinión',
            'Mundo',
            'Viajes',
            'Medio ambiente',
            'Salud',
            'Ciencia',
            'Educación',
            'Ciudad',
            'Cine',
            'Televisión',
            'Música',
            'Libros',
            'Gastronomía',
            'Motor',
            'Fútbol'
        ];

        // Get three random keys from array
        $rand_keys = array_rand($tags_list, $number);
        $tags = [ $tags_list[$rand_keys[0]], $tags_list[$rand_keys[1]], $tags_list[$rand_keys[2]] ];

        return $tags;
    }

    public function scrapost(){
        if( isset( $_POST[ 'action' ] ) ) 
        {

            /* Number post to publish */
            $number = $_POST['number'];

            /* Post type - by default is post */
            $postype = $_POST['postype'];
            if(!$postype) $postype = 'post';
            
            /* Category term - by default is category */
            $category_term = $_POST['category'];
            if(!$category_term) $category_term = 'category';

            /* Prevent if number is null or zero */
            if( !$number || $number == '' || $number == 0 ){

                $msg = 'The number must be different from zero';
                $result = null;
                
            }
            else {

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

                    /* Get tags */
                    $tags = $this->get_tags(3);

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
                    wp_set_object_terms( $post_id, $tags, 'post_tag', true );

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