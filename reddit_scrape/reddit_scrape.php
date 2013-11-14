 <?php
 

	include $_SERVER['DOCUMENT_ROOT'] . '/reddit_scrape/includes/db.inc.php';
 
    function getDecodedJSON($subreddit_name)
    {
		$curl = curl_init("http://www.reddit.com/r/" . strtolower($subreddit_name) . ".json");
		//initializes curl with the url instead of seting the url with curl_setopt

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly
		
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		//The number of seconds to wait while trying to connect. Use 0 to wait indefinitely
		
		curl_setopt($curl, CURLOPT_USERAGENT, "student project"); 
		//reddit API requests that we use a unique and descriptive user agent name to help prevent abuse
		//see https://github.com/reddit/reddit/wiki/API for more info and limitations

		$decoded_json = (array) json_decode(curl_exec($curl), true);
		curl_close($curl);  //closes the session and frees all resources
			
		return $decoded_json;
    }

	function parseSubreddit($subreddit_name, PDO $pdo, &$msg)
    {
		$success = 0; //success counter
   		$duplicates = 0; //duplicate counter
   		$adult = 0; //adult content counter
       	$parent = getDecodedJSON($subreddit_name);

		//error check: if the index name of data does not exist in the $parent array
		//it is because the reddit API did not return the JSON values expected.
		//likely, this will be because the subreddit does not exist.
		if(!array_key_exists('data', $parent)) 
		{ 
		 	$msg .=  '<p>ERROR: Subreddit does not exist. (or Reddit might be having problems/made changes.)</p>';
		 	return false;
		}
		 
        foreach ($parent['data']['children'] as $post)
        {
			if(checkDuplicatePost($post['data']['id'], $pdo))
			{
				$duplicates++;
			}
			else if ($post['data']['over_18']=="true") //makes sure no adult content is added to the database
			{
				$adult++;		
			}
			else
			{
				$doessubredditexist = checkSubredditExists($post['data']['subreddit_id'], $pdo);
				if (!$doessubredditexist)
				{
					addSubreddit($post['data']['subreddit'], $post['data']['subreddit_id'], $pdo, $msg);
				}
					
				try
				{
					$sql = 'INSERT INTO post (id, score, up, down, permalink, url, subreddit_id, title) VALUES (:id, :score, :up, :down, :permalink, :url, :subreddit_id, :title)';

					$s = $pdo->prepare($sql);

					$s->bindValue(':id', $post['data']['id']);
					$s->bindValue(':score', $post['data']['score']);
					$s->bindValue(':up', $post['data']['ups']);
					$s->bindValue(':down', $post['data']['downs']);
					$s->bindValue(':permalink', $post['data']['permalink']);
					$s->bindValue(':url', $post['data']['url']);
					$s->bindValue(':subreddit_id', $post['data']['subreddit_id']);
					$s->bindValue(':title', $post['data']['title']);
					$s->execute();
				}
				catch (PDOException $e)
				{
					$msg .= 'DATABASE ERROR: Error adding post id <em>' . $post['data']['id'] . '</em>!';
				   
					return false;
				}  
					$success += 1;     
			}
		}
		
		$msg .=  '<p>Successfully added <em>' . $success . '</em> posts to the database.</p>';
		   
		$msg .=  '<p>Found and ignored <em>' . $duplicates . '</em> duplicate posts.</p>';
		   
		$msg .=  '<p>Identified and ignored <em>' . $adult . '</em> adult content.</p>';
			
		return true;
	}
   
   function checkDuplicatePost($id, PDO $pdo)
   {
             try
	    {
	       $sql = 'SELECT * FROM post WHERE id=:id';

	       $s = $pdo->prepare($sql);
	       $s->bindValue(':id', $id);
	       $s->execute();
	    }
	    catch (PDOException $e)
	    {
	       $error = 'Error searching for duplicate IDs: ' . $e->getMessage();
	       print $error;
	    }
	   if($s->rowCount() > 0)
	    {
	       return true;
	    }
            else
            {
               return false;
            }
	}


	function checkSubredditExists($subreddit_id, PDO $pdo)
    {
   
	    try
	    {
	       $sql = 'SELECT * FROM subreddit WHERE id=:id';
	       $s = $pdo->prepare($sql); //returns a pdostatement object, throws PDOException on failure
	       $s->bindValue(':id', $subreddit_id);
	       $s->execute();
	    }
	    catch (PDOException $e)
	    {
	       print $error = 'Error searching for duplicate subreddits: ' . $e->getMessage();
	       return false;
	    }
		
	    if($s->rowCount() > 0) //a duplicate subreddit has been identified
	    {
			return true;
	    }
        else
        {   
            return false; 
        }
   
    }

     function addSubreddit($subreddit_name, $subreddit_id, PDO $pdo, $msg)
     {
               $subreddit = strtolower($subreddit_name); //makes sure $subreddit is lowercase
			   
                //error check
                //makes sure that the subreddit does not exist in the database prior to adding it
                if(checkSubredditExists($subreddit_id, $pdo))
                { 
                  return false;
                }
                else
                { 
	            	try
			   		{
					  $sql = 'INSERT INTO subreddit (id, name) VALUES (:id, :name)';
					  $s = $pdo->prepare($sql);
					  $s->bindValue(':id', $subreddit_id);
					  $s->bindValue(':name', $subreddit_name);
					  $s->execute();
			   		}
			  		catch (PDOException $e)
			   		{
					  $error = 'Error adding subreddit ' . $subreddit_name . ' to database: ';
					  include 'error.html.php';
					  exit();
			  		}
			  		$msg .= "<p>Successfully added <em>" . $subreddit_name . "</em> to database!</p>";
		         	return true;
			    } 
     }

 



