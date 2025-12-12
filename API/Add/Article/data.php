<?php



class AddArticle extends Db {
    public function add_article($user_id, $post_anyn, $type, $screen_size, $place_id, 
                                $place_local_id, $landmark_desc, $category_id, $headline, 
                                $description, $link, $linked_number, $linked_article, $tags){ 
                      
                     global $date;

            $tags = (explode(",",$tags));

            if($category_id == 5 || $category_id == 15 || $category_id == 16){
                $post_anyn = $post_anyn == '1' ? '1' : '0';
            }else{
                $post_anyn == '0';
            }

            if($landmark_desc != 'At' || $landmark_desc != 'In' || $landmark_desc != 'Near' ||
             $landmark_desc != 'In front of' || $landmark_desc != 'Behind' || $landmark_desc != 'At the side of'){
                $landmark_desc == 'Near';
            }

            $description = $description == '<p>&nbsp;</p>' ? "" : $description;

            $this->query("INSERT INTO Articles
                            (   TYPE, SCREEN, LINKED_NUMBER, LINKED_ARTICLE, USER_ID, USER_IF_ANONYMOUS, 
                                PLACE, PLACE_LOCAL, PLACE_LOCAL_DESC, CATEGORY, TITLE, ARTICLE, LINK, DATE  )
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
            $this->bind(1,$type);
            $this->bind(2,$screen_size);
            $this->bind(3,$linked_number);
            $this->bind(4,$linked_article);
            $this->bind(5,$user_id);
            $this->bind(6,$post_anyn);
            $this->bind(7,$place_id);
            $this->bind(8,$place_local_id);
            $this->bind(9,$landmark_desc);
            $this->bind(10,$category_id);
            $this->bind(11,$headline);
            $this->bind(12,$description);
            $this->bind(13,$link);
            $this->bind(14,$date);
            if($this->execute()){

                $this->query("SELECT * FROM Articles where USER_ID = '$user_id' ORDER BY ID DESC");
                $this->bind(1,$user_id);
                $row_article_id = $this->single();
                $article_id = $row_article_id['ID'];
            

                $this->query("UPDATE Article_Videos SET ARTICLE = ?, PUBLISH = ? WHERE USER = ? AND PUBLISH = ?");
                $this->bind(1,$article_id);
                $this->bind(2,1);
                $this->bind(3,$user_id);
                $this->bind(4,0);
                $this->execute();


                foreach($tags as $tag){
                    $this->query("INSERT INTO Article_Tags(USER,ARTICLE,TAG,DATE)VALUES(?,?,?,?)");
                    $this->bind(1,$user_id);
                    $this->bind(2,$article_id);
                    $this->bind(3,$tag);
                    $this->bind(4,$date);
                    $this->execute();
                }

                
                return 1;


            }else{
                return 2;
            }


    }
}