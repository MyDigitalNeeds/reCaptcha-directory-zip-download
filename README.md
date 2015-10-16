# reCaptcha directory-zip-download

##Need a quick way to let visitors download the contents of a directory as a zip file, but want to keep the bots at bay?

Google's reCaptcha service to the rescue!

##Prerequisites
You will need to create a reCaptcha key from https://www.google.com/recaptcha/admin. If your domain is example.com, be sure to add both example.com and www.example.com. Once you have the key, edit the markup in index.html to use the key or if you use the example.php script provided, modify the script to include your key. Each website should have it's own key.

PHP Implentation of a reCaptcha verified form-to-download of a pre-selected directory such as letting users download a copy front-end website assets.

##Implementation

We store our files in the following path: /ajax/libs from our sites' document root. Our script is placed in /ajax and upon
verification of the visitor not being a bot, we can proceed to zip up the /ajax/libs directory, saving it to disk, then streaming that file to the browser for download.

On thing to keep in mind, this is only an example for implementing Google's reCaptcha.
