# reCaptcha-directory-zip-download
PHP Implentation of a reCaptcha verified form-to-download of a pre-selected directory such as letting users download a copy front-end website assets.

##How to use

Here at Commnetivity, we host many websites that use many of the same Javascript and CSS libraries. While hard disk space may
be cheap these days, we don't see a real solid reason why for most of our websites and development projects, we couldn't use
our own little home-brew CDN to reduce duplicates of the same files across many of our projects. Since we are already using
CloudFlare's Railgun service, our little home-brew CDN solution works really well for us.

But what if we want to snatch up a copy to work locally? With this little utility, it's easy.

We store our files in the following path: /ajax/libs from our sites' document root. Our script is placed in /ajax and upon
verification of the visitor not being a bot, we can proceed to zip up the /ajax/libs directory, saving it to disk, then streaming
that file to the browser for download.

On thing to keep in mind, this is only an example for implementing Google's reCaptcha.
