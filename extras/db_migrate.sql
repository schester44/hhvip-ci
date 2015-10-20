/*INSTALL GUIDE**/
/*
* 1. move the mp3's to the base_url('temp_audio') location, or to a CDN (Amazon, etc)
* 2. add hotaru db main mysql user account so we can copy over the data
* 3. run each sql query individually
*** must be logged in for backend commands (group id must be set to 1)
* 4. run backend/update_song_migrate (filters user data to remove encoding and other odd shit, sets initial song hotness value, also sets the songs to published so they're visible on the front end)
* 5. run backend/move_mp3 (moves mp3's from the temp dir to their correct location (base_dir('audio_uploads') or amazon/cdn))
*
* -- setup_song_images needs the hotaru_categories table inside of the core database.
*
* 6. run backend/setup_song_images (renames images and creates file structure for images and moves image. previous system used a category image for each image. for each song, get and that image and also set up the correct directory schema so we can fetch it for the front end)
****/

--inserts user's who have confirmed their email address and are not older than 2 years.
INSERT IGNORE INTO `hiphop_core`.`users`(
  `id`, 
  `username`, 
  `password`, 
  `email`,
  `created_on`,
  `active`)
SELECT 
`user_id`, 
`user_username`, 
`user_password`, 
`user_email`, 
UNIX_TIMESTAMP(user_date),
'1'
FROM `hiphop_hotaru`.`hotaru_users`
WHERE `user_id` != '1' AND `user_id` != '2' AND `user_id` != '3'

/* this didn't seem to set last time, might need to manually update row*/
--add greg to admin group
INSERT INTO `hiphop_core`.`users_groups`(`user_id`,`group_id`) VALUES ('1','1'),('2','1'),('3','1');

--set everyone to user_group 2
INSERT IGNORE INTO `hiphop_core`.`users_groups`(
`user_id`,
`group_id`)
SELECT `user_id`, '2'
FROM `hiphop_hotaru`.`hotaru_users`
WHERE `user_id` != '1' AND `user_id` != '2' AND `user_id` != '3'

--inserts songs
INSERT IGNORE INTO `hiphop_core`.`songs`(
  `song_id`,
  `user_id`,
  `uploader`,
  `song_artist`,
  `song_title`,
  `upload_date`,
  `published_date`,
  `song_url`,
  `song_description`,
  `video`,
  `file_name`,
  `can_download`,
  `upvotes`,
  `downvotes`)
SELECT `post_id`,`post_author`,`post_author`,`post_artist`,`post_title`,`post_date`,unix_timestamp(`post_date`),`post_url`,`post_content`,`video_link`,`post_audio`,`post_allow_dl`,`post_votes_up`,`post_votes_down`
FROM `hiphop_hotaru`.`hotaru_posts`

--inserts song votes
INSERT IGNORE INTO `hiphop_core`.`songs_postvotes`(
`vote_updatedts`,
`vote_song_id`,
`vote_user_id`,
`vote_user_ip`,
`vote_date`,
`vote_rating`)
SELECT `vote_updatedts`,`vote_post_id`,`vote_user_id`,`vote_user_ip`,`vote_date`,`vote_rating`
FROM `hiphop_hotaru`.`hotaru_postvotes`
