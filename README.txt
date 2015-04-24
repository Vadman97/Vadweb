Don't forget to read me!

Welcome to the www.vadweb.us project! Featuring PHP :)

Check out the about page, it has most of the information about this site: www.vadweb.us/about.php

Mostly just a TODO list for now:


/*---- Prioritized TODO List -----
TODO MOVED TO GITHUB ISSUES 
https://github.com/Vadman97/Vadweb/issues
The following TODOs have not been moved yet.

*
*NEXT THING TO DO: Paginate views.php
*	Cache using a session array, which file IDs the user can view (at least for the thumbnails); look at APC_STORE()
*	Limit file requests per minute
*Make sure thumbnails can only be generated for real pictures, not gifs etc
*Make login bar look better
*Improve comments display for google crawling
*Display owner next to comments, add ratemgs for comments
*Ajax everytthing
*	Make sure all errors are grabbed from php and shown through javascript (might need backend change for this)
*Voting for both comments and files
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
*Improve frontend: make usernames/comments look better
*Add tracking of sources for link views, more information, improve data collection
*	Add stat graph generators?
*Add management of your uploads, file renaming, deleting, NSFW, moderation for admins
*Make uploading dialog AJAX using bootstrap loading bars
*Add file search or sorting by user etc
*Organize account settings page, add user pictures
*File copyright reporting
*Uploading videos: using php with avconv to convert for web formats *VIDEO CONVERSION
*CAPTCHA verification for uploads and whatnot
*Improve photo alt tags: make sure thumbnails don't show up on google but regular images do
*Improve frontend of the view.php
*Improve session management 
*Redo email verification; add age verification based on certain things like trying to upload a ton or email looks fake, not just for everyone after registering
*   Also maybe make it so if you didn't verify email you can still do things like read posts but not upload?
*BUG: Fix session expiration, new potential cause: if you do something (logout, click a file) while view.php is still loading...
*		Looks like this is caused by sessions overloaded by the file listing or by the pictures being too slow to compress via php
*		Paginate/Ajax loading
*		Save compressed version of file
*		USE DELAYS WHEN LOADING FILES?
*		LOOK AT HOW THE THUMBNAILS ARE GENERATED AND IMPROVE? CACHE?
*		Occurs when clicking/going back while loading files.php
*http://php.net/manual/en/function.shell-exec.php
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

//avconv -i <input.mov>  -c:v libx264 -i_qfactor 0.71 -qcomp 0.6 -qmin 10 -qmax 64 -qdiff 4 -trellis 0 -s 1280x720 -b:v 56k -ar 22050 <output.mp4>