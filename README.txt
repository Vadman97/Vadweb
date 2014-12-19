Welcome to the www.vadweb.us project! Featuring PHP :)

Check out the about page, it has most of the information about this site: www.vadweb.us/about.php

Mostly just a TODO list for now:


/*---- Prioritized TODO List -----
*Redo what I accidentally undid:
*	Displaying username
*	Overflow of large comments
*	Comment timeout
*	Backend of comment safety
*BUG: Figure out something to do with email verification letting you change email: make it so you can't change email to that of other people (other users); double check email verification/account.php
*BUG: Fix session expiration, new potential cause: if you do something (logout, click a file) while view.php is still loading...
*IMPORTANT: Viewing comments only for registered users?
*IMPORTANT: Go through my code and look for random todos cuz some of them are actually strangely important
*FEATURE: Email me when there is an issue in file uploads or something; just implement emailing for errors
*IMPORTANT: Make it so the upload titles are all the same length, eg they are packed into a tighter box (css frontend mostly)
*
*Paginate views.php
*Make login bar look better
*Finish commenting safety/html url parsing and html markup
*Improve comments display for google crawling
*Display owner next to comments, add ratomgs for comments
*Ajax everytthing
*	Make sure all errors are grabbed from php and shown through javascript (might need backend change for this)
*Improve uploads modal to include permissions for unlisted (checkbox) settings, make sure looks good, prepare for ajax uploads
*Add permissions management for user specific settings
*	Add unlisted viewing, figure that out in permissions and make sure the user can see own files?
*	Add the different highlights for your files etc
*Admin file management: viewing use permissions
*Terms and conditions, validation
*Readd multi-file uploads
*Ajax file view loading/updating
*Cache compressed images?
*Add lost username help
*Improve number of file types supported for embedding
*Add tracking of sources for link views, more information, improve data collection
*	Add stat graph generators?
*Add management of your uploads, file renaming, deleting, NSFW, moderation for admins
*Make uploading dialog AJAX using bootstrap loading bars
*Add file search or sorting by user etc
*Organize account settings page, add user pictures
*File copyright reporting
*Uploading videos: using php with avconv to convert for web formats
*CAPTCHA verification for uploads and whatnot
*Improve photo alt tags: make sure thumbnails don't show up on google but regular images do
*Improve frontend of the view.php
*Improve session management 
*Redo email verification; add age verification based on certain things like trying to upload a ton or email looks fake, not just for everyone after registering
*   Also maybe make it so if you didn't verify email you can still do things like read posts but not upload?
*/

RANDOM TODO NOTES

//TODO add file renaming feature built in, or in case error in file name
//TODO Figure out what happens if the file requested to be viewed is not found
//TODO Add better view tracking, with separate view from javascript and for the file from php
//TODO Work on about page
//TODO to views, add html origin of link
//TODO add user search and user sharing
//TODO add user settings
//TODO add file search, sorting
//TODO load files in pages
//TODO improve view counting tracking, add view count to file view page (also other details about file, user)
//TODO Ajax file uploading and turn error codes into useable things
//TODO display file permissions in files.php; only for admins
//TODO track source of clicks by using SERVER["HTML SOURCE OR WHATEVER IT IS"]
//TODO Fix issue of redirects from loggin in; make sure its obvious that registration/login was successful (especially when loggin in)
//TODO Add file alt tags for search engine, in general improve search engine apprearance
//TODO get ssl
//TODO improve about page photo alt tags

//TODO specific user blocking
//TODO NSFW tags/blocking
//TODO user filtration
//TODO User share / block when uploading
//TODO User settings for filtering certain users/innapropriate files
//TODO Read files.php in pages of n files, maybe by caching or sql coding
//TODO Make possible to view txt (all text code files) inline without downloading
//TODO MYSQL ERROR DISPLAYING
//TODO FILE DELETION
//TODO FILE COPYRIGHT REPORTING
//TODO If file name not exists or invalid (for view or files.php) do something about that?
//TODO CAPTCHA VERIFICATION FOR REGISTERING/UPLOADING
//TODO Captcha if uploading cooldown, for login if too many attempts, for email changing?
//TODO TERMS AND CONDITIONS
//TODO Switch from the default session management (settings ini calls session_start automatically everywhere)

//TODO ORGANIZE PRIORITIES
//TODO Add lost username help, improve login to be ajax?
//TODO improve the way images are displayed; css

//TODO Google images link to the thumbnail; need to fix that
//TODO The following is for video conversion
//avconv -i <input.mov>  -c:v libx264 -profile:v main -crf 30 -c:a libvorbis -qscale:a 8 -preset ultrafast -movflags +faststart <output.mp4>  <<<THIS IS WHAT WORKS WITH GCHROME
//avconv -i MVI_2563.mov  -c:v libx264 -profile:v main -crf 30 -c:a aac -strict experimental -preset ultrafast -movflags +faststart MVI_2563.mp4
//avconv -i <input.mov> -c:v libtheora -qscale:v 7 -c:a libvorbis -qscale:a 8 <output.ogg>
//ffmpeg -i video.flv -ss 0 -vframes 1 shot.png
